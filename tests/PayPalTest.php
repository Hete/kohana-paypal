<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * General tests for the PayPal module.
 * 
 * You need to set up a sandbox account in order to test anything.
 * 
 * @package  PayPal
 * @category Tests
 * @author   Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @license  http://kohanaframework.org/license
 */
class PayPalTest extends Unittest_TestCase {

    public function setUp() {

        parent::setUp();

        $this->assertEquals(PayPal::SANDBOX, PayPal::$environment, 'Tests won\'t run on a live setup.');
    }

    /**
     * Simple utility to assert a Validation object and output errors.
     */
    public function assertValidation(Validation $validation) {

        return $this->assertTrue($validation->check(), print_r($validation->errors(), TRUE));
    }

    public function testRequest() {

        $request = PayPal::factory('SetExpressCheckout');

        $this->assertInstanceOf('Request', $request);

        $this->assertNotNull(Kohana::$config->load('paypal.sandbox.signature'), 'Set your sandbox credentials to run this test.');

        $this->assertEquals('https://api-3t.sandbox.paypal.com/nvp', $request->uri());

        $this->assertArrayHasKey('METHOD', $request->query());
        $this->assertArrayHasKey('USER', $request->query());
        $this->assertArrayHasKey('PWD', $request->query());
        $this->assertArrayHasKey('SIGNATURE', $request->query());
        $this->assertArrayHasKey('VERSION', $request->query());
    }

    public function testLiveRequest() {
        
        PayPal::$environment = PayPal::LIVE;    

        $request = PayPal::factory('SetExpressCheckout');

        $this->assertNotNull(Kohana::$config->load('paypal.live.signature'), 'Set your live credentials to run this test.');

        $this->assertEquals('https://api-3t.paypal.com/nvp', $request->uri());
    }

    public function testParseResponse() {

        if (!$this->hasInternet()) {
        
            $this->markTestSkipped();    
        }

        $response = PayPal::factory('SetExpressCheckout')
                ->query('AMT', 12.27)
                ->execute();

        $data = PayPal::parse_response($response);

        $this->markTestIncomplete();
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
    public function testExpand(array $flattened, array $expanded) {
        $this->assertEquals($expanded, PayPal::expand($flattened));
    }

    public function testSetExpressCheckout() {

        if (!$this->hasInternet()) {

            $this->markTestSkipped();    
        }

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
     * @depends testSetExpressCheckout
     */
    public function testDoExpressCheckoutPayment($token, $payer_id) {

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
     * @depends testDoExpressCheckoutPayment
     */
    public function testGetExpressCheckoutDetails($token) {

        $response = PayPal::factory('GetExpressCheckoutDetails')
                ->query('TOKEN', $token)
                ->query('PAYERID', $this->payer_id)
                ->execute();

        $this->assertEquals($response->query('AMT'), 45);
    }

    public function testDoDirectPayment() {
        
        if (!$this->hasInternet()) {
        
            $this->markTestSkipped();    
        }

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
     * @depends testDoDirectPayment
     */
    public function testDoAuthorization($authorization_id) {

        PayPal::factory('DoAuthorization')
                ->query('AUTHORIZATIONID', $authorization_id)
                ->execute();
    }

    /**
     * @depends testDoAuthorization
     */
    public function testDoVoid($authorization_id) {

        PayPal::factory('DoVoid')
                ->query('AUTHORIZATIONID', $authorization_id)
                ->execute();

        return $authorization_id;
    }

    /**
     * @depends testDoVoid
     */
    public function testDoReauthorization($authorization_id) {

        PayPal::factory('DoReauthorization')
                ->query('AUTHORIZATIONID', $authorization_id)
                ->execute();
    }

    /**
     * @depends testDoDirectPayment
     */
    public function testDoCapture($authorization_id) {

        PayPal::factory('DoCapture')
                ->query('AUTHORIZATIONID', $authorization_id)
                ->execute();
    }

    /**
     * @depends testDoDirectPayment
     */
    public function testGetTransactionDetails($transaction_id) {

        $response = PayPal::factory('GetTransactionDetails')
                ->query('TRANSACTIONID', $transaction_id)
                ->execute();

        $this->assertTrue(PayPal_GetTransactionDetails::get_response_validation($response)->check());
    }

    /**
     * @depends testDoCapture
     */
    public function testRefundTransaction($transaction_id) {

        PayPal::factory('RefundTransaction')
                ->query('TRANSACTIONID', $transaction_id)
                ->execute();
    }

    /**
     * @depends testDoCapture
     */
    public function testTransactionSearch($transaction_id) {

        $response = PayPal::factory('TransactionSearch')
                ->query('TRANSACTIONID', $transaction_id)
                ->execute();
    }

    /**
     * @depends testDoDirectPayment
     */
    public function testSetCustomerBillingAgreement($transaction_id) {

        $response = PayPal::factory('SetCustomerBillingAgreement')
                ->query('TRANSACTIONID', $transaction_id)
                ->execute();

        $response = PayPal::parse_response($response);

        return $response['TOKEN'];
    }

    /**
     * @depends testSetCustomerBillingAgreement
     */
    public function testCreateBillingAgreement($token, $payer_id, $transaction_id) {

        PayPal::factory('RefundTransaction')
                ->query('TOKEN', $token)
                ->query('PAYERID', $payer_id)
                ->query('TRANSACTIONID', $transaction_id)
                ->execute();
    }

    /**
     * @depends SetExpressCheckout
     */
    public function testAddressVerify($token, $payer_id) {

        PayPal::factory('AddressVerify')
                ->query('EMAIL', $token)
                ->query('ZIP', $payer_id)
                ->execute();
    }

    /**
     * @depends testSetExpressCheckout
     */
    public function testCallback($token, $payer_id) {

        $request = PayPal::factory('Callback')
                ->query('EMAIL', $token)
                ->query('ZIP', $payer_id)
                ->execute();
    }

    public function testIPN() {

        $response = Request::factory('ipn')
                        ->method(Request::POST)
                        ->post(array(
                            'txn_type' => 'express_checkout',
                            'receiver_id' => '1234',
                            'receiver_email' => 'foo@example.com',
                            'residence_country' => 'USA',
                            'test_ipn' => TRUE
                        ))->execute();

        $this->assertEquals(200, $response->status());
    }

    public function testLiveIPN() {

        if (! $this->hasInternet()) {
        
            $this->markTestSkipped();    
        }

        PayPal::$environment = PayPal::LIVE;
        
        $response = Request::factory('ipn')
                        ->method(Request::POST)
                        ->post(array(
                            'txn_type' => 'express_checkout',
                            'receiver_id' => '1234',
                            'receiver_email' => 'foo@example.com',
                            'residence_country' => 'USA',
                            'test_ipn' => FALSE
                        ))->execute();

        $this->assertEquals(403, $response->status());
    }

    public function testTestIPNOnLiveEnvironment() {
        
        PayPal::$environment = PayPal::LIVE;
        
        $response = Request::factory('ipn')    
                        ->method(Request::POST)
                        ->post('test_ipn', TRUE)
                        ->post('txn_type', 'express_checkout')
                        ->execute();

        $this->assertEquals(403, $response->status());

    }

    public function testLiveIPNOnSandboxEnvironment() {
        
        $this->assertEquals(PayPal::SANDBOX, PayPal::$environment);
        
        $response = Request::factory('ipn')    
                        ->method(Request::POST)
                        ->post('test_ipn', FALSE)
                        ->post('txn_type', 'express_checkout')
                        ->execute();

        $this->assertEquals(403, $response->status());
    }

    public function tearDown() {
        
        PayPal::$environment = PayPal::SANDBOX;

        parent::tearDown();    
    }
}

/**
 * Redefines the IPN controller for testing.
 */
class Controller_PayPal_IPN extends Kohana_Controller_PayPal_IPN {
            
    public function action_express_checkout() {
                
        // does nothing...    
    }    
}
