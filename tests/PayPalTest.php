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

    public function setUp() {
        parent::setUp();
        Request::$initial = '';    
    }

    public function test_request() {
        
        $request = PayPal::factory('SetExpressCheckout')->request();

        $this->assertEquals($request->url(), 'https://sandbox.paypal.com/nvp');
        $this->assertInstanceOf('Request', $setexpresscheckout->request());
    }

    public function test_parse_response() {
        
        $response = PayPal::factory('SetExpressCheckout')
            ->request()
            ->query('AMT', 12.27)
            ->execute();

        $data = PayPal::parse_response($response);    
    }

    public function test_flatten() {
        
    }

    public function test_expand() {}

    /**
     * Test a PayPal response. PayPal request must be correct in order to 
     * test responses.
     * 
     * @depends test_request
     */
    public function test_Response() {

        $request = PayPal::factory('SetExpressCheckout')
            ->request()
            ->query(array(
                'AMT' => 45,
                'RETURNURL' => URL::site('', 'https'),
                'CANCELURL' => URL::site('', 'https')
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
            ->request()
            ->query(array(
                    'AMT' => 45,
                    'RETURNURL' => URL::site('', 'https'),
                    'CANCELURL' => URL::site('', 'https')
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
            ->request()
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
            ->request()
                ->query('TOKEN', $this->token)
                ->query('PAYERID', $this->payer_id)
                ->execute();

        $this->assertEquals($response->query('AMT'), 45);
    }

    private $authorization_id;
    private $transaction_id;

    public function test_DoDirectPayment() {

        $this->authorization_id = PayPal::factory('DoDirectPayment')
            ->request()
                ->query('EMAIL', $this->token)
                ->query('ZIP', $this->payer_id)
                ->execute();

        $this->transaction_id = PayPal::factory('DoDirectPayment')
            ->request()
                ->query('EMAIL', $this->token)
                ->query('ZIP', $this->payer_id)
                ->execute();
    }

    /**
     * @depends test_DoDirectPayment
     */
    public function test_DoAuthorization() {

        PayPal::factory('DoAuthorization')
            ->request()
                ->query('AUTHORIZATIONID', $this->authorization_id)
                ->execute();
    }

    /**
     * @depends test_DoAuthorization
     */
    public function test_DoVoid() {

        PayPal::factory('DoVoid')
            ->request()
                ->query('AUTHORIZATIONID', $this->authorization_id)
                ->execute();
    }

    /**
     * @depends test_DoVoid
     */
    public function test_DoReauthorization() {

        PayPal::factory('DoAuthorization')
            ->request()
                ->query('AUTHORIZATIONID', $this->authorization_id)
                ->execute();
    }

    /**
     * @depends test_DoDirectPayment
     */
    public function test_DoCapture() {

        PayPal::factory('DoCapture')
            ->request()
                ->query('TRANSACTIONID', $this->authorization_id)
                ->execute();

        PayPal::factory('DoCapture')
            ->request()
                ->query('TRANSACTIONID', $this->transaction_id)
                ->execute();
    }

    /**
     * @depends test_DoDirectPayment
     */
    public function test_GetTransactionDetails() {

        PayPal::factory('DoDirectPayment')
            ->request()
                ->query('TRANSACTIONID', $this->authorization_id)
                ->execute();

        PayPal::factory('DoDirectPayment')
            ->request()
                ->query('TRANSACTIONID', $this->transaction_id)
                ->execute();
    }

    /**
     * @depends test_DoCapture
     */
    public function test_RefundTransaction() {

        PayPal::factory('RefundTransaction')
            ->request()
                ->query('TRANSACTIONID', $this->transaction_id)
                ->execute();
    }

    /**
     * @depends test_DoCapture
     */
    public function test_TransactionSearch() {

        PayPal::factory('TransactionSearch')
            ->request()
                ->query('TRANSACTIONID', $this->transaction_id)
                ->execute();
    }

    /**
     * @depends test_DoDirectPayment
     */
    public function test_SetCustomerBillingAgreement() {

        $this->assertNotNull($this->transaction_id);

        $this->token = PayPal::factory('RefundTransaction')
            ->request()
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
            ->request()
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
            ->request()
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
            ->request()
                ->query('EMAIL', $this->token)
                ->query('ZIP', $this->payer_id)
                ->execute();
    }
}
