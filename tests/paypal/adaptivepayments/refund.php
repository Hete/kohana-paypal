<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Tests for AdaptivePayment Refund API.
 * 
 * @package Paypal
 * @category Tests
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright (c) 2012, Hète.ca Inc.
 */
class PayPal_AdaptivePayments_Refund_Test extends Unittest_TestCase {

    public function setUp() {
        parent::setUp();
        Request::$initial = '';
    }

    /**
     * 
     */
    public function test_complete_request() {

        // First we execute a payment
        $data = array(
            "actionType" => PayPal_AdaptivePayments_Pay::PAY,
            "cancelUrl" => "http://www.x.com",
            "returnUrl" => "http://www.x.com",
            "currencyCode" => "CAD",
            "receiverList.receiver(0).email" => "foo@gmail.com",
            "receiverList.receiver(0).amount" => PayPal::number_format(44.50),
        );

        $pay_key = PayPal::factory("AdaptivePayments_Pay", $data)->execute()->data("payKey");

        // The we refund it

        $data_refund = array(
            "payKey" => $pay_key,
            "currencyCode" => "CAD",
            "receiverList.receiver(0).email" => "foo@gmail.com",
            "receiverList.receiver(0).amount" => 22,
        );

        $request = PayPal::factory("AdaptivePayments_Refund", $data_refund);

        $this->assertInstanceOf("PayPal_AdaptivePayments_Refund", $request);

        $response = $request->execute();

        $this->assertInstanceOf("Response_Paypal", $response);
    }

}

?>
