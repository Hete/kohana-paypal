<?php

class PayPal_AdaptivePayments_CancelPreapproval_Test extends Unittest_TestCase {

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
        
        $result = PayPal::factory("AdaptivePayments_CancelPreapproval", $data)->execute();
        
        $this->assertInstanceOf("Response_Paypal", $result);
        
    }

}


?>
