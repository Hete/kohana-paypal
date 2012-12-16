<?php

class PayPal_AdaptivePayments_Preapproval_Test extends Unittest_TestCase {

    public function setUp() {
        parent::setUp();
        Request::$initial = '';
    }
    
    public function test_AdaptivePayments_Preapproval_request() {


        $data = array(
            "startingDate" => Date::formatted_time('now', PayPal::DATE_FORMAT),
            "endingDate" => Date::formatted_time('now', PayPal::DATE_FORMAT),
            "cancelUrl" => "http://www.x.com",
            "returnUrl" => "http://www.x.com",
            "currencyCode" => "CAD",
        );

        PayPal::factory("AdaptivePayments_Preapproval", $data)->execute();
    }

}


?>
