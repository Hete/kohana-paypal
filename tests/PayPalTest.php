<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * General tests for the PayPal module.
 * 
 * @package  PayPal
 * @category Tests
 * @author   Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @license  http://kohanaframework.org/license
 */
class PayPalTest extends Unittest_TestCase {

    private function println($message = NULL) {
        echo $message . "\n";
    }

    private function prompt($prompt = NULL) {

        $value = readline($prompt);

        echo "\n";

        return $value;
    }

    public function test_request() {
        
        $request = PayPal::factory('SetExpressCheckout');

        $this->assertInstanceOf('Request', $request);

        $this->assertEquals('https://api.sandbox.paypal.com/nvp', $request->uri());
    }

    public function test_parse_response() {
        
        $response = PayPal::factory('SetExpressCheckout')
            
            ->query('AMT', 12.27)
            ->execute();

        $data = PayPal::parse_response($response);    
    }

    public function expandables() {
        return array(
            array(
                array(
                    'FOO_0_BAR' => 'Teeest :)',
                    'FOO_1_BAR' => 'Example!'
                ),
                array(
                    'FOO' => array(
                        array( // 0
                            'BAR' => 'Teeest :)'
                        ),
                        array( // 1
                            'BAR' => 'Example!'
                        )
                    )
                )
            ),
            array(
                array('FOO'),
                array('FOO')
            ),
            array(
                array('FOO' => 'BAR'),
                array('FOO' => 'BAR')
            ),
            array(
                array('FOO.BAR'),
                array('FOO' => 'BAR')
            ),
            array(
                array('FOO.BAR_FOO'),
                array('FOO' => array('BAR' => 'FOO'))
            ),
        );    
    }

    /**
     * @dataProvider expandables
     */
    public function test_flatten(array $flattened, array $expanded) {
        $this->assertEquals($flattened, PayPal::flatten($expanded));
    }

    /**
     * @dataProvider expandables
     */
    public function test_expand(array $flattened, array $expanded) {
        $this->assertEquals($expanded, PayPal::expand($flattened));    
    }

    /**
     * Test a PayPal response. PayPal request must be correct in order to 
     * test responses.
     * 
     * @depends test_request
     */
    public function test_Response() {

        $request = PayPal::factory('SetExpressCheckout')
            
            ->query(array(
                'AMT' => 45,
                'RETURNURL' => 'http://example.com',
                'CANCELURL' => 'http://example.com'
        ));

        $response = $request->execute();

        $this->assertNotEmpty($response->body());

        $expanded_query = PayPal::parse_response($response);

        $flattened_query = PayPal::parse_response($response, FALSE);

        $this->assertEquals($flattened_query, PayPal::flatten($expanded_query));

        $this->assertTrue(Valid::url($setexpresscheckout->redirect_url));
    }

    private $token;

    public function test_SetExpressCheckout() {

        $response = PayPal::factory('SetExpressCheckout')
            
            ->query(array(
                    'AMT' => 45,
                    'RETURNURL' => 'http://example.com',
                    'CANCELURL' => 'http://example.com'
                ))->execute();

        $response = PayPal::parse_response($response);

        $this->token = $response->query('TOKEN');

        $this->assertNotEmpty($this->token);

        $this->println($response->redirect_url);
    }

    private $payer_id;

    /**
     * @depends test_SetExpressCheckout
     */
    public function test_DoExpressCheckoutPayment() {

        $this->payer_id = readline('Payer ID >');

        $this->payer_id = PayPal::factory('DoExpressCheckoutPayment')
            
                ->query('AMT', 45)
                ->query('PAYERID', $this->payer_id)
                ->query('TOKEN', $this->token)
                ->execute();
    }

    /**
     * @depends test_DoExpressCheckoutPayment
     */
    public function test_GetExpressCheckoutDetails() {

        $response = PayPal::factory('GetExpressCheckoutDetails')
            
                ->query('TOKEN', $this->token)
                ->query('PAYERID', $this->payer_id)
                ->execute();

        View::factory('paypal/getexpresscheckoutdetails', array('getexpresscheckoutdetails' => $response))
            ->render();

        $this->assertEquals($response->query('AMT'), 45);
    }

    private $authorization_id;
    private $transaction_id;

    public function test_DoDirectPayment() {

        $response = PayPal::factory('DoDirectPayment')
            
                ->query('EMAIL', 'info@example.com')
                ->query('ZIP', 'H0H 0H0')
                ->execute();

        $response = PayPal::parse_response($response);

        $this->transaction_id = $response['TRANSACTIONID'];

        View::factory('payal/dodirectpayment', array('dodirectpayment' => $response))
            ->render();

        // @todo add the Authorization method
        $this->authorization_id = PayPal::factory('DoDirectPayment')
            
                ->query('EMAIL', 'info@example.com')
                ->query('ZIP', 'H0H 0H0')
                ->execute();

        $response = PayPal::parse_response($response);

        $this->authorization_id = $response['AUTHORIZATIONID'];

        View::factory('payal/dodirectpayment', array('dodirectpayment' => $response))
            ->render();
    }

    /**
     * @depends test_DoDirectPayment
     */
    public function test_DoAuthorization() {

        $this->assertNotNull($this->authorization_id);

        PayPal::factory('DoAuthorization')
            
                ->query('AUTHORIZATIONID', $this->authorization_id)
                ->execute();
    }

    /**
     * @depends test_DoAuthorization
     */
    public function test_DoVoid() {

        PayPal::factory('DoVoid')
            
                ->query('AUTHORIZATIONID', $this->authorization_id)
                ->execute();
    }

    /**
     * @depends test_DoVoid
     */
    public function test_DoReauthorization() {

        PayPal::factory('DoAuthorization')
            
                ->query('AUTHORIZATIONID', $this->authorization_id)
                ->execute();
    }

    /**
     * @depends test_DoDirectPayment
     */
    public function test_DoCapture() {

        $this->assertNotNull($this->authorization_id);
        $this->assertNutNull($this->transaction_id);

        PayPal::factory('DoCapture')
            
                ->query('TRANSACTIONID', $this->authorization_id)
                ->execute();

        PayPal::factory('DoCapture')
            
                ->query('TRANSACTIONID', $this->transaction_id)
                ->execute();
    }

    /**
     * @depends test_DoDirectPayment
     */
    public function test_GetTransactionDetails() {

        $this->assertNotNull($this->authorization_id);
        $this->assertNutNull($this->transaction_id);

        $response = PayPal::factory('GetTransactionDetails')
            
                ->query('TRANSACTIONID', $this->authorization_id)
                ->execute();

        $this->assertTrue(PayPal_GetTransactionDetails::get_response_validation($response)->check());

        View::factory('paypal/gettransactiondetails', array('gettransactiondetails' => $response))
            ->render();

        $response = PayPal::factory('GetTransactionDetails')
            
                ->query('TRANSACTIONID', $this->transaction_id)
                ->execute();

        $this->assertTrue(PayPal_GetTransactionDetails::get_response_validation($response)->check());

        View::factory('paypal/gettransactiondetails', array('gettransactiondetails' => $response))
            ->render();
    }

    /**
     * @depends test_DoCapture
     */
    public function test_RefundTransaction() {

        $this->assertNutNull($this->transaction_id);

        PayPal::factory('RefundTransaction')
            
                ->query('TRANSACTIONID', $this->transaction_id)
                ->execute();
    }

    /**
     * @depends test_DoCapture
     */
    public function test_TransactionSearch() {

        $this->assertNutNull($this->transaction_id);

        PayPal::factory('TransactionSearch')
            
                ->query('TRANSACTIONID', $this->transaction_id)
                ->execute();
    }

    /**
     * @depends test_DoDirectPayment
     */
    public function test_SetCustomerBillingAgreement() {

        $this->assertNotNull($this->transaction_id);

        $this->token = PayPal::factory('RefundTransaction')
            
                ->query('TRANSACTIONID', $this->transaction_id)
                ->execute()
                ->query('TOKEN');
    }

    /**
     * @depends test_SetCustomerBillingAgreement
     */
    public function test_CreateBillingAgreement() {

        $this->payer_id = $this->prompt('Payer ID >');

        PayPal::factory('RefundTransaction')
            
                ->query('TOKEN', $this->token)
                ->query('TRANSACTIONID', $this->transaction_id)
                ->execute();
    }

    /**
     * @depends SetExpressCheckout
     */
    public function test_AddressVerify() {

        $this->assertNotNull($this->token);
        $this->assertNotNull($this->payer_id);

        PayPal::factory('AddressVerify')
            ->query('EMAIL', $this->token)
            ->query('ZIP', $this->payer_id)
            ->execute();
    }

    /**
     * @depends test_SetExpressCheckout
     */
    public function test_Callback() {

        $this->assertNotNull($this->token);
        $this->assertNotNull($this->payer_id);

        PayPal::factory('Callback')
            ->query('EMAIL', $this->token)
            ->query('ZIP', $this->payer_id)
            ->execute();
    }
}
