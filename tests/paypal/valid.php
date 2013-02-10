<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @package PayPal
 * @category Tests
 */
class PayPal_Valid_Test extends Unittest_TestCase {

    public function test_boolean() {
        $this->assertTrue(PayPal_Valid::boolean(PayPal::TRUE));
        $this->assertTrue(PayPal_Valid::boolean(PayPal::FALSE));

        // Regular boolean should not be accepted
        $this->assertFalse(PayPal_Valid::boolean(true));
        $this->assertFalse(PayPal_Valid::boolean(false));

        $this->assertFalse(PayPal_Valid::boolean("TRUE"));
        $this->assertFalse(PayPal_Valid::boolean("FALSE"));

        // Random values
        $this->assertFalse(PayPal_Valid::boolean("trUe"));
        $this->assertFalse(PayPal_Valid::boolean("crap"));

        // Numeric values
        $this->assertFalse(PayPal_Valid::boolean(1));
        $this->assertFalse(PayPal_Valid::boolean(0));
    }

    /**
     * 
     */
    public function test_contained() {

        $array = array("a", 1, "b" => 2);

        $this->assertTrue(PayPal_Valid::contained("a", $array));

        // b is a key, not a value
        $this->assertFalse(PayPal_Valid::contained("b", $array));

        $this->assertTrue(PayPal_Valid::contained(1, $array));
    }

    public function test_date() {
        $this->assertTrue(PayPal_Valid::date(Date::formatted_time("now", PayPal::DATE_FORMAT)));
    }

    public function test_short_date() {
        $this->assertTrue(PayPal_Valid::date(Date::formatted_time("now", PayPal::SHORT_DATE_FORMAT)));
    }

    public function test_wrong_date() {
        $this->assertFalse(PayPal_Valid::date(Date::formatted_time("now", Date::$timestamp_format)));
    }

}

?>
