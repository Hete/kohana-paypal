<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Validations pour les requêtes PayPal. Complète Valid de Kohana.
 * 
 * @see Valid
 * 
 * @package PayPal
 * @category Valid
 * @author Guillaume Poirier-Morency
 * @copyright Hète.ca  
 */
class Kohana_PayPal_Valid extends Valid {

    /**
     * Tells if the value is a valid PayPal boolean.
     * @param string $str
     * @return type
     */
    public static function boolean($str) {
        $str = (string) $str;
        return $str === Request_PayPal::TRUE | $str === Request_PayPal::FALSE;
    }

    /**
     * Tells if a value is container in the specified array.
     * @param type $str
     * @param type $array
     * @return type
     */
    public static function contained($str, $array) {
        return array_search($str, $array) !== FALSE;
    }

    /**
     * Tells if the value matches PayPal date format.
     * @param type $str
     * @return type
     */
    public static function date($str) {

        $time = strtotime($str);



        return parent::date($str) && (date(PayPal::DATE_FORMAT, $time) === $str | date(PayPal::SHORT_DATE_FORMAT, $time) === $str);
    }

    /**
     * Tells if the specified value is a PayPal supported currency.
     * @param string $str
     * @return boolean
     */
    public static function currency($str) {
        return static::contained($str, Request_PayPal::$CURRENCIES);
    }

    public static function currency_code($str) {
        return static::currency($str);
    }

    /**
     * Tells if the specified value is a PayPal supported day of the week.
     * @param string $str
     * @return boolean
     */
    public static function day_of_week($str) {
        return static::contained($str, Request_PayPal::$DAYS_OF_WEEK);
    }

    /**
     * Tells if the specified value is a PayPal supported day of the week.
     * @param string $str
     * @return boolean
     */
    public static function payment_period($str) {
        return static::contained($str, Request_PayPal::$PAYMENT_PERIODS);
    }

    public static function preapproval_status($str) {
        return static::contained($str, Request_PayPal::$PREAPPROVAL_STATES);
    }

    public static function fee_payer($str) {
        return static::contained($str, Request_PayPal::$FEES_PAYER);
    }

    public static function pin_type($str) {
        return static::contained($str, Request_PayPal::$REQUIRED_STATES);
    }

}

?>
