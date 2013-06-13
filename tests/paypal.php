<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * General tests for the PayPal module.
 * 
 * @package PayPal
 * @category Tests
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 */
class PayPal_Test extends Unittest_TestCase {

    public function setUp() {
        parent::setUp();
        Request::$initial = '';
    }

    /**
     * Various assertions about a PayPal request.
     */
    public function test_request() {

        // This must be sandbox
        $this->assertEquals(PayPal::$default_environment, PayPal::SANDBOX);

        $request = PayPal::factory('AdaptivePayments_Pay');

        $this->assertInternalType('array', $request->data());

        // API url must be a valid url
        $this->assertTrue(Valid::url($request->api_url()));

        // Curl key exists
        $this->assertNotNull($request->config('client'));

        // Curl options is at least an array
        $this->assertInternalType('array', $request->config('client'));
    }

    /**
     * Test a PayPal response. PayPal request must be correct in order to 
     * test responses.
     * 
     * @depends test_request
     */
    public function test_response() {

        $data = array(
            'actionType' => PayPal_AdaptivePayments_Pay::PAY,
            'cancelUrl' => 'http://www.x.com',
            'returnUrl' => 'http://www.x.com',
            'currencyCode' => 'CAD',
            'receiverList.receiver(0).email' => 'foo@gmail.com',
            'receiverList.receiver(0).amount' => PayPal::number_format(44.50),
        );

        $request = PayPal::factory('AdaptivePayments_Pay', $data);

        $response = $request->execute();

        $this->assertInstanceOf('Response_PayPal', $response);

        $this->assertInstanceOf('Validation', $response);

        // Testing the data() function
        $this->assertEquals($response['payKey'], $response->data('payKey'));

        // Redirect url, if not empty, must be a valid url
        if (Valid::not_empty($response->redirect_url)) {
            $this->assertTrue(Valid::url($response->redirect_url));
        }
    }

}

?>
