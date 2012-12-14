<?php

class PayPalTest extends Unittest_TestCase {

    public function testCompleteRequest() {
        
        // 
        Request::$initial = '';

        $data = array(
            "startingDate" => Date::formatted_time(),
            "endingDate" => Date::formatted_time(),
            "cancelUrl" => "http://www.x.com",
            "returnUrl" => "http://www.x.com",
            "currencyCode" => "CAD",
        );

        PayPal::factory("AdaptivePayments_Preapproval", $data)->execute();
    }

}

?>
