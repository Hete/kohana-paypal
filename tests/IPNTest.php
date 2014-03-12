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

        $this->assertTrue(Kohana::$config->load('paypal.' . PayPal::$environment . '.ipn_enabled'));

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

    public function test_express_checkout() {

        $response = Request::factory('ipn')
            ->query(array(
                'txn_type'          => 'express_checkout',
                'receiver_id'       => '1234',
                'receiver_email'    => 'foo@example.com',
                'residence_country' => 'USA',
                'test_ipn'          => TRUE
            ))->execute();

        $this->assertEquals(200, $response->status());
    }
}
