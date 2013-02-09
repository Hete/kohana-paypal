<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @package PayPal
 * @category PaymentsPro
 */
class PayPal_PaymentsPro_DoDirectPayment_Test extends Unittest_TestCase {

    public function setUp() {
        parent::setUp();
        Request::$initial = '';
    }

    public function test_complete_request() {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

}

?>
