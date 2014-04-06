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

    public function setUp() {
        
        parent::setUp();

        $this->assertEquals(PayPal::SANDBOX, PayPal::$environment, 'Do not run unittests with a live account: you might get some surprises.');
    }

    public function assertValidation(Validation $validation) {

        return $this->assertTrue($validation->check(), print_r($validation->errors(), TRUE));
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

    public function test_redirect() {
        
    }

    public function expandables() {
        return array(
            array(
                // one level array
                array('FOO'),
                array('FOO')
            ),
            array(
                // one level dictionary
                array('FOO' => 'BAR'),
                array('FOO' => 'BAR')
            ),
            array(
                // dot
                array('FOO.BAR' => 'BAR'),
                array('FOO' => array('BAR' => 'BAR'))
            ),
            array(
                // mixed dot and underscore
                array('FOO.BAR_FOO' => 'FOO'),
                array('FOO' => array('BAR' => array('FOO' => 'FOO')))
            ),
            array(
                // multiple keys
                array(
                    'FOO_0_BAR' => 'Teeest :)',
                    'FOO_1_BAR' => 'Example!'
                ),
                array(
                    'FOO' => array(
                        '0' => array(// 0
                            'BAR' => 'Teeest :)'
                        ),
                        '1' => array(// 1
                            'BAR' => 'Example!'
                        )
                    )
                )
            ),
        );
    }

    /**
     * @dataProvider expandables
     */
    public function test_expand(array $flattened, array $expanded) {
        $this->assertEquals($expanded, PayPal::expand($flattened));
    }

    public function test_SetExpressCheckout() {

        $request = PayPal::factory('SetExpressCheckout')
            ->query('AMT', 45)
            ->query('RETURNURL', 'http://example.com')
            ->query('CANCELURL', 'http://example.com');

        $this->assertValidation(PayPal_SetExpressCheckout::get_request_validation($request));

        $response = $request->execute();

        $this->assertValidation(PayPal_SetExpressCheckout::get_response_validation($response));

        $response = PayPal::parse_response($response);

        // redirection
        $this->assertEquals('https://www.sandbox.paypal.com/cgi-bin/webscr', PayPal_SetExpressCheckout::redirect_url());

        $this->assertArrayHasKey('TOKEN', $response);

        return array($response['TOKEN'], 'wert3t559t89i');
    }

    /**
     * @depends test_SetExpressCheckout
     */
    public function test_DoExpressCheckoutPayment($token, $payer_id) {

        $response = PayPal::factory('DoExpressCheckoutPayment')
                ->query('AMT', 45)
                ->query('PAYERID', $payer_id)
                ->query('TOKEN', $token)
                ->execute();

        $validation = PayPal_DoExpressCheckoutPayment::get_response_validation($response);

        $this->assertValidation($validation);

        $response = PayPal::parse_response($response);

        return $response['TRANSACTIONID'];
    }

    /**
     * @depends test_DoExpressCheckoutPayment
     */
    public function test_GetExpressCheckoutDetails($token) {

        $response = PayPal::factory('GetExpressCheckoutDetails')
                ->query('TOKEN', $token)
                ->query('PAYERID', $this->payer_id)
                ->execute();

        $this->assertEquals($response->query('AMT'), 45);
    }

    public function test_DoDirectPayment() {

        $response = PayPal::factory('DoDirectPayment')
                ->query('CREDITCARDTYPE', 'Visa')
                ->query('ACCT', '4222222222222')
                ->query('CVV2', '272')
                ->query('EXPDATE', '052020')
                ->query('EMAIL', 'info@example.com')
                ->query('ZIP', 'H0H 0H0')
                ->query('STREET', '55, Sesam street')
                ->query('AMT', 44.1)
                ->execute();

        $this->assertValidation(PayPal_DoDirectPayment::get_response_validation($response));

        $response = PayPal::parse_response($response);

        // @todo add the Authorization method
        $response = PayPal::factory('DoDirectPayment')
                ->query('CREDITCARDTYPE', 'Visa')
                ->query('ACCT', '4222222222222')
                ->query('CVV2', '272')
                ->query('EXPDATE', '052020')
                ->query('EMAIL', 'info@example.com')
                ->query('ZIP', 'H0H 0H0')
                ->query('STREET', '55, Sesam street')
                ->query('AMT', 44.1)
                ->execute();

        $this->assertValidation(PayPal_DoDirectPayment::get_response_validation($response));

        $response = PayPal::parse_response($response);

        return $response['AUTHORIZATIONID'];
    }

    /**
     * @depends test_DoDirectPayment
     */
    public function test_DoAuthorization($authorization_id) {

        PayPal::factory('DoAuthorization')
                ->query('AUTHORIZATIONID', $authorization_id)
                ->execute();
    }

    /**
     * @depends test_DoAuthorization
     */
    public function test_DoVoid($authorization_id) {

        PayPal::factory('DoVoid')
                ->query('AUTHORIZATIONID', $authorization_id)
                ->execute();

        return $authorization_id;
    }

    /**
     * @depends test_DoVoid
     */
    public function test_DoReauthorization($authorization_id) {

        PayPal::factory('DoReauthorization')
                ->query('AUTHORIZATIONID', $authorization_id)
                ->execute();
    }

    /**
     * @depends test_DoDirectPayment
     */
    public function test_DoCapture($authorization_id) {

        PayPal::factory('DoCapture')
                ->query('AUTHORIZATIONID', $authorization_id)
                ->execute();
    }

    /**
     * @depends test_DoDirectPayment
     */
    public function test_GetTransactionDetails($transaction_id) {

        $response = PayPal::factory('GetTransactionDetails')
                ->query('TRANSACTIONID', $transaction_id)
                ->execute();

        $this->assertTrue(PayPal_GetTransactionDetails::get_response_validation($response)->check());
    }

    /**
     * @depends test_DoCapture
     */
    public function test_RefundTransaction($transaction_id) {

        PayPal::factory('RefundTransaction')
                ->query('TRANSACTIONID', $transaction_id)
                ->execute();
    }

    /**
     * @depends test_DoCapture
     */
    public function test_TransactionSearch($transaction_id) {

        $response = PayPal::factory('TransactionSearch')
                ->query('TRANSACTIONID', $transaction_id)
                ->execute();
    }

    /**
     * @depends test_DoDirectPayment
     */
    public function test_SetCustomerBillingAgreement($transaction_id) {

        $response = PayPal::factory('SetCustomerBillingAgreement')
                ->query('TRANSACTIONID', $transaction_id)
                ->execute();

        $response = PayPal::parse_response($response);

        return $response['TOKEN'];
    }

    /**
     * @depends test_SetCustomerBillingAgreement
     */
    public function test_CreateBillingAgreement($token, $payer_id, $transaction_id) {

        PayPal::factory('RefundTransaction')
                ->query('TOKEN', $token)
                ->query('PAYERID', $payer_id)
                ->query('TRANSACTIONID', $transaction_id)
                ->execute();
    }

    /**
     * @depends SetExpressCheckout
     */
    public function test_AddressVerify($token, $payer_id) {

        PayPal::factory('AddressVerify')
                ->query('EMAIL', $token)
                ->query('ZIP', $payer_id)
                ->execute();
    }

    /**
     * @depends test_SetExpressCheckout
     */
    public function test_Callback($token, $payer_id) {

        $request = PayPal::factory('Callback')
                ->query('EMAIL', $token)
                ->query('ZIP', $payer_id)
                ->execute();
    }
    
    public function test_ipn() {

        $response = Request::factory('ipn')
            ->method(Request::POST)
            ->post(array(
                'txn_type'          => 'express_checkout',
                'receiver_id'       => '1234',
                'receiver_email'    => 'foo@example.com',
                'residence_country' => 'USA',
                'test_ipn'          => TRUE
            ))->execute();

        $this->assertEquals(403, $response->status());
    }

}
