<?php

class PayPalValidTest extends Unittest_TestCase {

    public function test_date() {
        $this->assertTrue(PayPal_Valid::date(Date::formatted_time("now", PayPal::DATE_FORMAT)));
    }

    public function test_short_date() {
        $this->assertTrue(PayPal_Valid::date(Date::formatted_time("now", PayPal::SHORT_DATE_FORMAT)));
    }

    public function test_wrong_date() {
        $this->assertFalse(PayPal_Valid::date(Date::formatted_time("now", "H:m:s")));
    }

}

?>
