<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @package PayPal
 * @subpackage AdaptivePayments
 * @category Tests
 */
class PayPal_AdaptivePayments_Test extends Unittest_TestCase {

    public function setUp() {
        parent::setUp();
        Request::$initial = "";
    }

    /**
     * Generates a pay key for tests.
     * @return string
     */
    private function generate_paykey() {
        return PayPal::factory("AdaptivePayments_Pay", array(
                    "actionType" => PayPal_AdaptivePayments_Pay::CREATE,
                    "receiverList.receiver(0).email" => "foo@gmail.com",
                    "receiverList.receiver(0).amount" => PayPal::number_format(44.50),
                    "currencyCode" => "CAD",
                    "cancelUrl" => "https://www.x.com",
                    "returnUrl" => "https://www.x.com",
                ))->execute()->data("payKey");
    }

    public function test_cancel_preapproval() {

        $data = array(
            "startingDate" => Date::formatted_time('now', PayPal::DATE_FORMAT),
            "endingDate" => Date::formatted_time('now', PayPal::DATE_FORMAT),
            "cancelUrl" => "http://www.x.com",
            "returnUrl" => "http://www.x.com",
            "currencyCode" => "CAD",
        );

        $preapproval_key = PayPal::factory("AdaptivePayments_Preapproval", $data)->execute()->data("preapprovalKey");



        $result = PayPal::factory("AdaptivePayments_CancelPreapproval", array(
                    "preapprovalKey" => $preapproval_key
                ))->execute();

        $this->assertInstanceOf("Response_Paypal", $result);
    }

    public function test_ConvertCurrency() {
        $this->markTestIncomplete("This test has not been implemented yet.");
    }

    /**
     * @expectedException PayPal_Validation_Exception
     */
    public function test_ConvertCurrency_bad_currency() {

        $data = array(
            "baseAmountList.currency(0).code" => "FOO",
            "baseAmountList.currency(0).amount" => PayPal::number_format(44.50),
            "convertToCurrencyList.currencyCode(0).currencyCode" => "BAR",
        );

        $result = PayPal::factory("AdaptivePayments_ConvertCurrency", $data)->execute();


        $this->assertInstanceOf("Response_Paypal", $result);
    }

    /**
     * @expectedException PayPal_Exception
     * @expectedExceptionCode 550001
     */
    public function test_execute_payment() {

        // At this point, the pay key has not been authorized
        $data = array(
            "payKey" => $this->generate_paykey(),
            "actionType" => PayPal_AdaptivePayments_ExecutePayment::PAY,
        );


        $request = PayPal::factory("AdaptivePayments_ExecutePayment", $data);

        $this->assertInstanceOf("PayPal_AdaptivePayments_ExecutePayment", $request);

        $request->execute();
    }

    /**
     * @expectedException PayPal_Exception
     * @expectedExceptionCode 550001
     */
    public function test_get_funding_plans() {

        $data = array(
            "payKey" => $this->generate_paykey()
        );

        // At this point, the pay key is not activated

        $request = PayPal::factory("AdaptivePayments_GetFundingPlans", $data);

        $this->assertInstanceOf("PayPal_AdaptivePayments_GetFundingPlans", $request);

        $request->execute();
    }

    public function test_GetPaymentOptions() {

        $data = array(
            "payKey" => $this->generate_paykey()
        );

        $request = PayPal::factory("AdaptivePayments_GetPaymentOptions", $data);

        $this->assertInstanceOf("PayPal_AdaptivePayments_GetPaymentOptions", $request);

        $response = $request->execute();

        $this->assertInstanceOf("Response_Paypal", $response);
    }

    public function test_get_shipping_address() {

        $data = array(
            "key" => $this->generate_paykey()
        );

        $request = PayPal::factory("AdaptivePayments_GetShippingAddresses", $data);

        $this->assertInstanceOf("PayPal_AdaptivePayments_GetShippingAddresses", $request);

        $response = $request->execute();

        $this->assertInstanceOf("Response_Paypal", $response);
    }

    /**
     * @expectedException PayPal_Exception
     */
    public function test_Pay_invalid_request_wrong_parameter() {
        $data = array(
            "actionType" => PayPal_AdaptivePayments_Pay::PAY,
            "cancelUrl" => "http://www.x.com",
            "returnUrl" => "http://www.x.com",
            "currencyCode" => "CAD",
            "receiverList.receiver(0).email" => "foogmail.com", // Invalid email
            "receiverList.receiver(0).amount" => PayPal::number_format(44.50),
        );

        PayPal::factory("AdaptivePayments_Pay", $data)->execute();
    }

    /**
     * @expectedException PayPal_Exception
     */
    public function test_Pay_invalid_request_wrong_action_type() {
        $data = array(
            "actionType" => "FOO!",
            "cancelUrl" => "http://www.x.com",
            "returnUrl" => "http://www.x.com",
            "currencyCode" => "CAD",
            "receiverList.receiver(0).email" => "foo@gmail.com", // Invalid email
            "receiverList.receiver(0).amount" => PayPal::number_format(44.50),
        );

        PayPal::factory("AdaptivePayments_Pay", $data)->execute();
    }

    /**
     * @expectedException PayPal_Exception
     */
    public function test_Pay_invalid_request_missing_parameter() {
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

    public function test_Pay() {

        $data = array(
            "actionType" => PayPal_AdaptivePayments_Pay::PAY,
            "cancelUrl" => "http://www.x.com",
            "returnUrl" => "http://www.x.com",
            "currencyCode" => "CAD",
            "receiverList.receiver(0).email" => "foo@gmail.com",
            "receiverList.receiver(0).amount" => PayPal::number_format(44.50),
        );

        $response = PayPal::factory("AdaptivePayments_Pay", $data)->execute();

        $this->assertArrayHasKey("payKey", $response->data());

        $this->assertTrue(Valid::url($response->redirect_url));
    }

    public function test_PaymentDetails() {

        $data = array(
            "payKey" => $this->generate_paykey()
        );

        $request = PayPal::factory("AdaptivePayments_PaymentDetails", $data);

        $this->assertInstanceOf("PayPal_AdaptivePayments_PaymentDetails", $request);

        $response = $request->execute();

        $this->assertInstanceOf("Response_Paypal", $response);
    }

    public function test_Preapproval() {

        $data = array(
            "startingDate" => Date::formatted_time('now', PayPal::DATE_FORMAT),
            "endingDate" => Date::formatted_time('now', PayPal::DATE_FORMAT),
            "cancelUrl" => "http://www.x.com",
            "returnUrl" => "http://www.x.com",
            "currencyCode" => "CAD",
        );

        $request = PayPal::factory("AdaptivePayments_Preapproval", $data);

        $this->assertInstanceOf("PayPal_AdaptivePayments_Preapproval", $request);

        $response = $request->execute();

        $this->assertInstanceOf("Response_Paypal", $response);

        $this->assertArrayHasKey('preapprovalKey', $response->data());
    }

    /**
     * @expectedException PayPal_Exception
     */
    public function test_Preapproval_invalid_request_wrong_parameter() {
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
    public function test_Preapproval_invalid_request_missing_required_parameter() {
        $data = array(
            "startingDate" => Date::formatted_time('now', PayPal::DATE_FORMAT),
            "endingDate" => Date::formatted_time('now', PayPal::DATE_FORMAT),
            "cancelUrl" => "http://www.x.com",
            "currencyCode" => "CADe", // Currency is wrong here
        );

        PayPal::factory("AdaptivePayments_Preapproval", $data)->execute();
    }

    public function test_PreapprovalDetails() {

        $data = array(
            "startingDate" => Date::formatted_time('now', PayPal::DATE_FORMAT),
            "endingDate" => Date::formatted_time('now', PayPal::DATE_FORMAT),
            "cancelUrl" => "http://www.x.com",
            "returnUrl" => "http://www.x.com",
            "currencyCode" => "CAD",
        );

        $request = PayPal::factory("AdaptivePayments_Preapproval", $data);

        $this->assertInstanceOf("PayPal_AdaptivePayments_Preapproval", $request);

        $response = $request->execute();

        $this->assertInstanceOf("Response_Paypal", $response);

        $this->assertArrayHasKey('preapprovalKey', $response->data());
    }

    /**
     * 
     */
    public function test_Refund() {

        // The we refund it

        $data_refund = array(
            "payKey" => $this->generate_paykey(),
            "currencyCode" => "CAD",
            "receiverList.receiver(0).email" => "foo@gmail.com",
            "receiverList.receiver(0).amount" => 22,
        );

        $request = PayPal::factory("AdaptivePayments_Refund", $data_refund);

        $this->assertInstanceOf("PayPal_AdaptivePayments_Refund", $request);

        $response = $request->execute();

        $this->assertInstanceOf("Response_Paypal", $response);
    }

    public function test_SetPaymentOptions() {

        $data_spo = array(
            "payKey" => $this->generate_paykey()
                ,
        );

        $request = PayPal::factory("AdaptivePayments_SetPaymentOptions", $data_spo);

        $this->assertInstanceOf("PayPal_AdaptivePayments_SetPaymentOptions", $request);

        $response = $request->execute();

        $this->assertInstanceOf("Response_Paypal", $response);
    }

}

?>
