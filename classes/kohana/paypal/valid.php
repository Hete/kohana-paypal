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
class Kohana_PayPal_Valid {

    /**
     * Tells if the value is a valid PayPal boolean.
     * @param string $str
     * @return type
     */
    public static function boolean($str) {
        $str = (string) $str;
        return $str === "true" | $str === "false";
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

        if ($time !== FALSE && date(PayPal::DATE_FORMAT, $time) === $str) {
            return Valid::date($str);
        }

        return Valid::date($str);
    }

}

?>
