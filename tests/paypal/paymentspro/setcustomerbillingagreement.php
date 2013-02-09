<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @package PayPal
 * @category PaymentsPro
 */
class PayPal_PaymentsPro_SetCustomerBillingAgreement_Test extends Unittest_TestCase {

    public function setUp() {
        parent::setUp();
        Request::$initial = '';
    }

    public function test_complete_request() {

        $data = array(
            "startingDate" => Date::formatted_time('now', PayPal::DATE_FORMAT),
            "BILLINGTYPE" => "MerchantInitiatedBilling",
            "CANCELURL" => "http://www.x.com",
            "RETURNURL" => "http://www.x.com",
            "EMAIL" => "guillaumepoiriermorency@gmail.com",
        );

        $result = PayPal::factory("PaymentsPro_SetCustomerBillingAgreement", $data)->execute();

        $this->assertInstanceOf("Response_Paypal_NVP", $result);
    }

}

?>
