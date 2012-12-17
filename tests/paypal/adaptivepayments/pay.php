<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 */
class PayPal_AdaptivePayments_Pay_Test extends Unittest_TestCase {

    public function setUp() {
        parent::setUp();
        Request::$initial = '';
    }

    /**
     * @expectedException PayPal_Exception
     */
    public function test_invalid_request_wrong_parameter() {
        $data = array(
            "actionType" => PayPal_AdaptivePayments_Pay::PAY,
            "cancelUrl" => "http://www.x.com",
            "returnUrl" => "http://www.x.com",
            "currencyCode" => "CAD",
            "receiverList.receiver(0).email" => "foogmail.com", // Invalid email
            "receiverList.receiver(0).amount" => 44.50,
        );

        PayPal::factory("AdaptivePayments_Pay", $data)->execute();
    }

    /**
     * @expectedException PayPal_Exception
     */
    public function test_invalid_request_wrong_action_type() {
        $data = array(
            "actionType" => "FOO!",
            "cancelUrl" => "http://www.x.com",
            "returnUrl" => "http://www.x.com",
            "currencyCode" => "CAD",
            "receiverList.receiver(0).email" => "foo@gmail.com", // Invalid email
            "receiverList.receiver(0).amount" => 44.50,
        );

        PayPal::factory("AdaptivePayments_Pay", $data)->execute();
    }

    /**
     * @expectedException PayPal_Exception
     */
    public function test_invalid_request_missing_parameter() {
        $data = array(
            "actionType" => PayPal_AdaptivePayments_Pay::PAY,
            "cancelUrl" => "http://www.x.com",
            "returnUrl" => "http://www.x.com",
            "currencyCode" => "CAD",
            "receiverList.receiver(0).email" => "foo@gmail.com",
                // amount is missing
        );

        PayPal::factory("AdaptivePayments_Pay", $data)->execute();
    }

    public function test_complete_request() {

        $data = array(
            "actionType" => PayPal_AdaptivePayments_Pay::PAY,
            "cancelUrl" => "http://www.x.com",
            "returnUrl" => "http://www.x.com",
            "currencyCode" => "CAD",
            "receiverList.receiver(0).email" => "foo@gmail.com",
            "receiverList.receiver(0).amount" => 44.50,
        );

        $response = PayPal::factory("AdaptivePayments_Pay", $data)->execute();

        $this->assertArrayHasKey("payKey", $response->data());

        $this->assertTrue(Valid::url($response->redirect_url));
    }

}

?>
