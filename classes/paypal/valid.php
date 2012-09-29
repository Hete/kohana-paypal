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
class PayPal_Valid {

    public static function boolean(string $str) {
        $str = (string) $str;
        return $str === "true" | $str === "false";
    }

    public static function date($str) {

        $time = strtotime($str);

        if ($time !== FALSE && date(PayPal::DATE_FORMAT, $time) === $str) {
            return Valid::date($str);
        }

        return Valid::date($str);
    }

}

?>
