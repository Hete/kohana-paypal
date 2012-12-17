<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @link https://www.x.com/developers/paypal/documentation-tools/api/setpaymentoptions-api-operation
 * 
 * @package PayPal
 * @category AdaptivePayments
 * @author Guillaume Poirier-Morency
 * @copyright 2012 (c), Hète.ca Inc.
 */
class PayPal_AdaptivePayments_SetPaymentOptions_Test extends Unittest_TestCase {

    public function setUp() {
        parent::setUp();
        Request::$initial = '';
    }

    public function test_complete_request() {
        
        // First we execute a payment
        $data = array(
            "actionType" => PayPal_AdaptivePayments_Pay::PAY,
            "cancelUrl" => "http://www.x.com",
            "returnUrl" => "http://www.x.com",
            "currencyCode" => "CAD",
            "receiverList.receiver(0).email" => "foo@gmail.com",
            "receiverList.receiver(0).amount" => 44.50,
        );

        $pay_key = PayPal::factory("AdaptivePayments_Pay", $data)->execute()->data("payKey");


        $data_spo = array(
            "payKey" => $pay_key
          ,
        );

        $request = PayPal::factory("AdaptivePayments_SetPaymentOptions", $data_spo);

        $this->assertInstanceOf("PayPal_AdaptivePayments_SetPaymentOptions", $request);

        $response = $request->execute();

        $this->assertInstanceOf("Response_Paypal", $response);
    }

}

?>
