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
class PayPal_Test extends Unittest_TestCase {

    private function println($message = NULL) {
        echo $message . "\n";
    }

    private function prompt($prompt = NULL) {

        $value = readline($prompt);

        echo "\n";

        return $value;
    }

    /**
     * Various assertions about a PayPal request.
     */
    public function test_PayPal() {

        // This must be sandbox
        $this->assertEquals(PayPal::$environment, PayPal::SANDBOX);

        $setexpresscheckout = PayPal::factory('SetExpressCheckout');

        $this->assertInternalType('array', $setexpresscheckout->data());

        $this->assertInstanceOf('Request', $setexpresscheckout->request());

        // API url must be a valid url
        $this->assertTrue(Valid::url(PayPal::api_url()));
    }

    public function test_parse_response() {
        
        $response = PayPal::factory('SetExpressCheckout')->request()->execute();

        Request::parse_response($response);    
        
    }

    public function test_flatten() {
        
        
        
    }

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

        $expanded_data = PayPal::parse_response($response);

        $flattened_data = PayPal::parse_response($response, FALSE);

        $this->assertEquals($flattened_data, PayPal::flatten($expanded_data));

        $this->assertTrue(Valid::url($setexpresscheckout->redirect_url));
    }

    private $token;

    public function test_SetExpressCheckout() {

        $response = PayPal::factory('SetExpressCheckout', array(
                    'AMT' => 45,
                    'RETURNURL' => URL::site('', 'https'),
                    'CANCELURL' => URL::site('', 'https')
                ))->execute();

        $this->token = $response->data('TOKEN');

        $this->assertNotEmpty($this->token);

        // Une personne physique doit accéder au site de PayPal
        $this->println($response->redirect_url);
    }

    private $payer_id;

    /**
     * @depends test_SetExpressCheckout
     */
    public function test_DoExpressCheckoutPayment() {

        $this->payer_id = readline('Payer ID >');

        $this->payer_id = PayPal::factory('DoExpressCheckoutPayment')
                ->data('AMT', 45)
                ->data('PAYERID', $this->payer_id)
                ->data('TOKEN', $this->token)
                ->execute();
    }

    /**
     * @depends test_DoExpressCheckoutPayment
     */
    public function test_GetExpressCheckoutDetails() {

        $response = PayPal::factory('GetExpressCheckoutDetails')
                ->data('TOKEN', $this->token)
                ->data('PAYERID', $this->payer_id)
                ->execute();

        $this->assertEquals($response->data('AMT'), 45);
    }

    private $authorization_id;
    private $transaction_id;

    public function test_DoDirectPayment() {

        $this->authorization_id = PayPal::factory('DoDirectPayment')
                ->data('EMAIL', $this->token)
                ->data('ZIP', $this->payer_id)
                ->execute();

        $this->transaction_id = PayPal::factory('DoDirectPayment')
                ->data('EMAIL', $this->token)
                ->data('ZIP', $this->payer_id)
                ->execute();
    }

    /**
     * @depends test_DoDirectPayment
     */
    public function test_DoAuthorization() {

        PayPal::factory('DoAuthorization')
                ->data('AUTHORIZATIONID', $this->authorization_id)
                ->execute();
    }

    /**
     * @depends test_DoAuthorization
     */
    public function test_DoVoid() {

        PayPal::factory('DoVoid')
                ->data('AUTHORIZATIONID', $this->authorization_id)
                ->execute();
    }

    /**
     * @depends test_DoVoid
     */
    public function test_DoReauthorization() {

        PayPal::factory('DoAuthorization')
                ->data('AUTHORIZATIONID', $this->authorization_id)
                ->execute();
    }

    /**
     * @depends test_DoDirectPayment
     */
    public function test_DoCapture() {

        PayPal::factory('DoCapture')
                ->data('TRANSACTIONID', $this->authorization_id)
                ->execute();

        PayPal::factory('DoCapture')
                ->data('TRANSACTIONID', $this->transaction_id)
                ->execute();
    }

    /**
     * @depends test_DoDirectPayment
     */
    public function test_GetTransactionDetails() {

        PayPal::factory('DoDirectPayment')
                ->data('TRANSACTIONID', $this->authorization_id)
                ->execute();

        PayPal::factory('DoDirectPayment')
                ->data('TRANSACTIONID', $this->transaction_id)
                ->execute();
    }

    /**
     * @depends test_DoCapture
     */
    public function test_RefundTransaction() {

        PayPal::factory('RefundTransaction')
                ->data('TRANSACTIONID', $this->transaction_id)
                ->execute();
    }

    /**
     * @depends test_DoCapture
     */
    public function test_TransactionSearch() {

        PayPal::factory('TransactionSearch')
                ->data('TRANSACTIONID', $this->transaction_id)
                ->execute();
    }

    public function test_SetCustomerBillingAgreement() {

        $this->token = PayPal::factory('RefundTransaction')
                ->data('TRANSACTIONID', $this->transaction_id)
                ->execute()
                ->data('TOKEN');
    }

    /**
     * @depends test_SetCustomerBillingAgreement
     */
    public function test_CreateBillingAgreement() {

        $this->payer_id = $this->prompt('Payer ID >');

        PayPal::factory('RefundTransaction')
                ->data('TOKEN', $this->token)
                ->data('TRANSACTIONID', $this->transaction_id)
                ->execute();
    }

    public function test_AddressVerify() {

        PayPal::factory('AddressVerify')
                ->data('EMAIL', $this->token)
                ->data('ZIP', $this->payer_id)
                ->execute();
    }

    public function test_Callback() {

        PayPal::factory('Callback')
                ->data('EMAIL', $this->token)
                ->data('ZIP', $this->payer_id)
                ->execute();
    }

}

?>
