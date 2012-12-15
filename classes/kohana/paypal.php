<?php

class Kohana_PayPal implements PayPal_Constants {

    /**
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
    
    ////////////////////////////////////////////////////////////////////////////
    //
    ////////////////////////////////////////////////////////////////////////////
    
    

}

?>
