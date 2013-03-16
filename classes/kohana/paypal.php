<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * PayPal main class. Contains constants and a factory method for requests.
 * 
 * Requests never inherit from this class, but rather from the Request_PayPal
 * class which provides basic Kohana request features such as post and headers
 * handling.
 * 
 * @package PayPal
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright (c) 2012, Hète.ca Inc.
 */
class Kohana_PayPal implements PayPal_Constants {

    /**
     * Default environment.
     * 
     * @var string 
     */
    public static $default_environment = "sandbox";

    /**
     * Factory method for Request_PayPal object. It is implemented here because
     * Request forces undesired parameters.
     * 
     * @see Request
     * 
     * @param string $name
     * @param array $params
     * @param HTTP_Cache $cache
     * @param type $injected_routes
     * @return Request_PayPal
     */
    public static function factory($name, array $params = array(), array $expected = NULL, HTTP_Cache $cache = NULL, $injected_routes = array()) {

        $class = "PayPal_$name";

        return new $class(TRUE, $cache, $injected_routes, $params, $expected);
    }

    /**
     * Format numbers specifically for PayPal API.
     * 
     * @param number $number
     * @return number
     */
    public static function number_format($number) {
        return number_format($number, 2, ".", "");
    }

    /**
     * Format dates specificaly for PayPal API.
     * 
     * @param string $date
     * @return string
     */
    public static function date_format($date) {
        return Date::format($date, static::DATE_FORMAT);
    }

}

?>
