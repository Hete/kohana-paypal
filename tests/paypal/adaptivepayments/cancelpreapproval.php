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

        $preapproval_key = PayPal::factory("AdaptivePayments_Preapproval", $data)->execute()->data("preapprovalKey");



        $result = PayPal::factory("AdaptivePayments_CancelPreapproval", array(
                    "preapprovalKey" => $preapproval_key
                ))->execute();

        $this->assertInstanceOf("Response_Paypal", $result);
    }

}

?>
