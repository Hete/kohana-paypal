<?php

defined('SYSPATH') or die('No direct script access.');


/**
 * 
 */
class Kohana_PayPal {

    /**
     * 
     * @param type $name
     * @param array $params
     * @param HTTP_Cache $cache
     * @param type $injected_routes
     * @return \class
     */
    public static function factory($name, array $params = array(), HTTP_Cache $cache = NULL, $injected_routes = array()) {
        $class = "PayPal_$name";
        return new $class(TRUE, $cache, $injected_routes, $params);
    }

}

?>
