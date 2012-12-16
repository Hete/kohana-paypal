<?php

class Paypal_ExpressCheckout_SetExpressCheckout_Test extends Unittest_TestCase {

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

        PayPal::factory("ExpressCheckout_SetExpressCheckout", $data)->execute();
    }

}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
