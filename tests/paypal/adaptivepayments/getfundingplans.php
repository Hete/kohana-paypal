<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @link https://cms.paypal.com/ca/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_APExecutePaymentAPI
 * 
 * @package PayPal
 * @category AdaptivePayments
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com> 
 * @copyright Hète.ca Inc.
 */
class PayPal_AdaptivePayments_GetFundingPlans_Test extends Unittest_TestCase {

    public function setUp() {
        parent::setUp();
        Request::$initial = '';
    }

    /**
     * @expectedException PayPal_Exception
     * @expectedExceptionCode 550001
     */
    public function test_unauthorized_request() {

        // Recuperate a payKey
        $pay_key = PayPal::factory("AdaptivePayments_Pay", array(
                    "actionType" => PayPal_AdaptivePayments_Pay::CREATE,
                    "receiverList.receiver(0).email" => "foo@gmail.com",
                    "receiverList.receiver(0).amount" => 55,
                    "currencyCode" => "CAD",
                    "cancelUrl" => "https://www.x.com",
                    "returnUrl" => "https://www.x.com",
                ))->execute()->data("payKey");

        // At this point, the pay key is not activated


        $data = array(
            "payKey" => $pay_key
        );


        $request = PayPal::factory("AdaptivePayments_GetFundingPlans", $data);

        $this->assertInstanceOf("PayPal_AdaptivePayments_GetFundingPlans", $request);

        $request->execute();
    }

}

?>
