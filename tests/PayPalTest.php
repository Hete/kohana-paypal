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
                array('FOO.BAR'),
                array('FOO' => 'BAR')
            ),
            array(
                // mixed dot and underscore
                array('FOO.BAR_FOO'),
                array('FOO' => array('BAR' => 'FOO'))
            ),
            array(
                // multiple keys
                array(
                    'FOO_0_BAR' => 'Teeest :)',
                    'FOO_1_BAR' => 'Example!'
                ),
                array(
                    'FOO' => array(
                        array(// 0
                            'BAR' => 'Teeest :)'
                        ),
                        array(// 1
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

        $response = PayPal::factory('SetExpressCheckout')
                        ->query(array(
                            'AMT' => 45,
                            'RETURNURL' => 'http://example.com',
                            'CANCELURL' => 'http://example.com'
                        ))->execute();

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

        View::factory('paypal/getexpresscheckoutdetails', array('getexpresscheckoutdetails' => $response))
                ->render();

        $this->assertEquals($response->query('AMT'), 45);
    }

    public function test_DoDirectPayment() {

        $response = PayPal::factory('DoDirectPayment')
                ->query('EMAIL', 'info@example.com')
                ->query('ZIP', 'H0H 0H0')
                ->execute();

        $this->assertValidation(PayPal_DoDirectPayment::get_response_validation($response));

        $response = PayPal::parse_response($response);

        View::factory('paypal/dodirectpayment', array('dodirectpayment' => $response))
                ->render();

        // @todo add the Authorization method
        $response = PayPal::factory('DoDirectPayment')
                ->query('EMAIL', 'info@example.com')
                ->query('ZIP', 'H0H 0H0')
                ->execute();

        $this->assertValidation(PayPal_DoDirectPayment::get_response_validation($response));

        $response = PayPal::parse_response($response);

        View::factory('paypal/dodirectpayment', array('dodirectpayment' => $response))
                ->render();

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

        View::factory('paypal/gettransactiondetails', array('gettransactiondetails' => $response))
                ->render();
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

}
