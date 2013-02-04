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
     * @return boolean
     */
    public static function boolean($str) {
        $str = (string) $str;
        return (boolean) ($str === Request_PayPal::TRUE | $str === Request_PayPal::FALSE);
    }

    /**
     * Tells if a value is container in the specified array.
     * @param type $str
     * @param array $array
     * @return type
     */
    public static function contained($str, array $array) {
        return array_search($str, $array) !== FALSE;
    }

    /**
     * Validate a float number for PayPal
     * @param type $str
     * @return boolean
     */
    public static function numeric($str) {
        return (bool) preg_match('/^-?+(?=.*[0-9])[0-9]*+' . preg_quote(".") . '?+[0-9]*+$/D', (string) $str);
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
     * @deprecated for naming convention, use currency_code instead.
     * @param string $str
     * @return boolean
     */
    public static function currency($str) {
        return static::contained($str, Request_PayPal::$CURRENCIES);
    }

    public static function currency_code($str) {
        return static::contained($str, Request_PayPal::$CURRENCY_CODES);
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
        return static::contained($str, PayPal_AdaptivePayments::$PAYMENT_PERIODS);
    }

    /**
     * 
     * @param type $str
     * @return type
     */
    public static function preapproval_status($str) {
        return static::contained($str, PayPal_AdaptivePayments_PreapprovalDetails::$PREAPPROVAL_STATES);
    }

    /**
     * 
     * @param type $str
     * @return type
     */
    public static function fee_payer($str) {
        return static::contained($str, Request_PayPal::$FEES_PAYER);
    }

    /**
     * 
     * @param type $str
     * @return type
     */
    public static function pin_type($str) {
        return static::contained($str, Request_PayPal::$REQUIRED_STATES);
    }

}

?>
