<?php

class PayPal_AdaptivePayments_ConvertCurrency_Test extends Unittest_TestCase {

    public function setUp() {
        parent::setUp();
        Request::$initial = '';
    }

    public function test_complete_request() {

        $data = array(
            "baseAmountList.currency(0).code" => "CAD",
            "baseAmountList.currency(0).amount" => 55.25,
            "convertToCurrencyList.currency(0).code" => "USD",
        );

        $result = PayPal::factory("AdaptivePayments_ConvertCurrency", $data)->execute();


        $this->assertInstanceOf("Response_Paypal", $result);
    }

}

?>
