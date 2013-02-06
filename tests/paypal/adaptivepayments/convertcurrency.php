<?php

class PayPal_AdaptivePayments_ConvertCurrency_Test extends Unittest_TestCase {

    public function setUp() {
        parent::setUp();
        Request::$initial = '';
    }

    /**
     * @expectedException PayPal_Exception
     * @expectedExceptionCode 580027
     */
    public function test_complete_request() {

        $data = array(
            "baseAmountList.currency(0).code" => "EUR",
            "baseAmountList.currency(0).amount" => PayPal::number_format(44.50),
            "convertToCurrencyList.currencyCode(0).currencyCode" => "USD",
        );

        $result = PayPal::factory("AdaptivePayments_ConvertCurrency", $data)->execute();


        $this->assertInstanceOf("Response_Paypal", $result);
    }

}

?>
