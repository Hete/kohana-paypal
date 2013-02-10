<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Tests for IPN.
 * 
 * @package PayPal
 * @subpackage IPN
 * @category Tests
 */
class PayPal_IPN_Test extends Unittest_TestCase {

    public function setUp() {
        parent::setUp();
        Request::$initial = "";
    }

    public function test_notify_validate() {

        $param = array(
            "txn_type" => "express_checkout",
            "receiver_id" => PayPal::factory("IPN_NotifyValidate")->config("ipn.receiver.id"),
            "receiver_email" => PayPal::factory("IPN_NotifyValidate")->config("ipn.receiver.email"),
            "residence_country" => PayPal::factory("IPN_NotifyValidate")->config("ipn.receiver.country"),
            "test_ipn" => (int) (PayPal::factory("IPN_NotifyValidate")->environment() === PayPal::SANDBOX),
        );

        $response = PayPal::factory("IPN_NotifyValidate", $param)->execute();

        $this->assertEquals($response->response->body(), Response_PayPal_IPN::INVALID);

        $this->assertEquals($response->response->body(), $response["status"]);
    }

}

?>
