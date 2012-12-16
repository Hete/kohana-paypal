<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * General tests for the PayPal module.
 * 
 * 
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 */
class PayPal_Test extends Unittest_TestCase {

    public function setUp() {
        parent::setUp();
        Request::$initial = "";
    }

    /**
     * Various assertions about a PayPal request.
     */
    public function test_request() {

        $request = PayPal::factory("AdaptivePayments_Pay");

        // API url must be a valid url
        $this->assertTrue(Valid::url($request->api_url()));

        // Param is an alias for post
        $this->assertEquals($request->param(), $request->post());

        // Curl key exists
        $this->assertNotNull($request->config("curl"));

        // Curl options is at least an array
        $this->assertTrue(Arr::is_array($request->config("curl.options")));
    }

    /**
     * Test a PayPal response. PayPal request must be correct in order to 
     * test responses.
     * @depends test_request
     */
    public function test_response() {

        $data = array(
            "actionType" => PayPal_AdaptivePayments_Pay::PAY,
            "cancelUrl" => "http://www.x.com",
            "returnUrl" => "http://www.x.com",
            "currencyCode" => "CAD",
            "receiverList.receiver(0).email" => "foo@gmail.com",
            "receiverList.receiver(0).amount" => 44.50,
        );

        $response = PayPal::factory("AdaptivePayments_Pay", $data)->execute();

        $this->assertInstanceOf("Response_PayPal", $response);

        $this->assertInstanceOf("Validation", $response);

        // Redirect url, if not empty, must be a valid url
        if (Valid::not_empty($response->redirect_url)) {
            $this->assertTrue(Valid::url($response->redirect_url));
        }
    }

}

?>
