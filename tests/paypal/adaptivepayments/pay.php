<?php

class PayPal_AdaptivePayments_Pay_Test extends Unittest_TestCase {

    public function setUp() {
        parent::setUp();
        Request::$initial = '';
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

        PayPal::factory("AdaptivePayments_Pay", $data)->execute();
    }

}

?>
