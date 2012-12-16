<?php

class PayPal_AdaptivePayments_Preapproval_Test extends Unittest_TestCase {

    public function setUp() {
        parent::setUp();
        Request::$initial = '';
    }

    public function test_complete_request() {

        $data = array(
            "startingDate" => Date::formatted_time('now', PayPal::DATE_FORMAT),
            "endingDate" => Date::formatted_time('now', PayPal::DATE_FORMAT),
            "cancelUrl" => "http://www.x.com",
            "returnUrl" => "http://www.x.com",
            "currencyCode" => "CAD",
        );

        $request = PayPal::factory("AdaptivePayments_Preapproval", $data);

        $this->assertInstanceOf("PayPal_AdaptivePayments_Preapproval", $response);

        $response = $request->execute();

        $this->assertInstanceOf("Response_Paypal", $response);

        $this->assertArrayHasKey('preapprovalKey', $response->data());
    }

    /**
     * @expectedException PayPal_Exception
     */
    public function test_invalid_request_wrong_parameter() {
        $data = array(
            "startingDate" => Date::formatted_time('now', PayPal::DATE_FORMAT),
            "endingDate" => Date::formatted_time('now', PayPal::DATE_FORMAT),
            "cancelUrl" => "http://www.x.com",
            "returnUrl" => "http://www.x.com",
            "currencyCode" => "CADe", // Currency is wrong here
        );

        PayPal::factory("AdaptivePayments_Preapproval", $data)->execute();
    }

    /**
     * @expectedException PayPal_Exception
     */
    public function test_invalid_request_missing_required_parameter() {
        $data = array(
            "startingDate" => Date::formatted_time('now', PayPal::DATE_FORMAT),
            "endingDate" => Date::formatted_time('now', PayPal::DATE_FORMAT),
            "cancelUrl" => "http://www.x.com",
            "currencyCode" => "CADe", // Currency is wrong here
        );

        PayPal::factory("AdaptivePayments_Preapproval", $data)->execute();
    }

}

?>
