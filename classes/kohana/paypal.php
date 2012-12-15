<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * PayPal main class. Contains constants and a factory method for requests.
 * 
 * @package PayPal
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright (c) 2012, Hète.ca Inc.
 */
class Kohana_PayPal implements PayPal_Constants {

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
    public static function factory($name, array $params = array(), HTTP_Cache $cache = NULL, $injected_routes = array()) {
        $class = "PayPal_$name";
        return new $class(TRUE, $cache, $injected_routes, $params);
    }

}

?>
