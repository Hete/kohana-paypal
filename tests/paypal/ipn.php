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

        $response = PayPal::factory("IPN_NotifyValidate", array())->execute();

        $this->assertEquals($response->response->body(), Response_PayPal_IPN::INVALID);
        
        $this->assertEquals($response->response->body(), $response["status"]);
    }

}

?>
