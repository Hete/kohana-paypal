<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Tests for IPN.
 * 
 * @package  PayPal
 * @category Tests
 * @license  http://kohanaframework.org/license
 */
class IPNTest extends Unittest_TestCase {

    public function test_NotifyValidate() {

        $response = PayPal::factory('NotifyValidate')
            ->query(array(
                'txn_type'          => 'express_checkout',
                'receiver_id'       => '1234',
                'receiver_email'    => 'paypal@example.com',
                'residence_country' => 'US',
                'test_ipn'          => TRUE
            ))->execute();

        $this->assertEquals($response->body(), PayPal_NotifyValidate::INVALID);
    }

    public function test_reflective_request() {

        if (headers_sent()) {
            // Skip this test
            $this->markTestSkipped('Headers are already sent.');
        }

        $response = Request::factory("paypal/ipn")
            ->query(array(
                "txn_type" => "express_checkout",
                "receiver_id" => PayPal::factory("IPN_NotifyValidate")->config("ipn.receiver.id"),
                "receiver_email" => PayPal::factory("IPN_NotifyValidate")->config("ipn.receiver.email"),
                "residence_country" => PayPal::factory("IPN_NotifyValidate")->config("ipn.receiver.country"),
                "test_ipn" => (PayPal::factory("IPN_NotifyValidate")->environment() === PayPal::SANDBOX) ? "1" : "0",
            ))->execute();

        $this->assertNotEmpty($response->body());
    }
}
