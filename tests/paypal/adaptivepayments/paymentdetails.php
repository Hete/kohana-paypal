<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @link https://cms.paypal.com/ca/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_APPaymentDetails 
 */
class PayPal_AdaptivePayments_PaymentDetails_Test extends Unittest_TestCase {

    public function setUp() {
        parent::setUp();
        Request::$initial = '';
    }

    public function test_complete_request() {

        // Recuperate a payKey
        $pay_key = PayPal::factory("AdaptivePayments_Pay", array(
                    "actionType" => PayPal_AdaptivePayments_Pay::CREATE,
                    "receiverList.receiver(0).email" => "foo@gmail.com",
                    "receiverList.receiver(0).amount" => 55,
                    "currencyCode" => "CAD",
                    "cancelUrl" => "https://www.x.com",
                    "returnUrl" => "https://www.x.com",
                ))->execute()->data("payKey");

        $data = array(
            "payKey" => $pay_key
        );

        $request = PayPal::factory("AdaptivePayments_PaymentDetails", $data);

        $this->assertInstanceOf("PayPal_AdaptivePayments_PaymentDetails", $request);

        $response = $request->execute();

        $this->assertInstanceOf("Response_Paypal", $response);

    }

}

?>
