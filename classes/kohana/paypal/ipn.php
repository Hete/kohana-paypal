<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Class to centralize PayPal IPN requests.
 * 
 * @see https://www.x.com/developers/paypal/documentation-tools/ipn/integration-guide/IPNIntro
 * 
 * @package PayPal
 * @subpackage IPN
 * @author Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 */
abstract class Kohana_PayPal_IPN extends Request_PayPal_NVP {

    public function __construct($uri = TRUE, HTTP_Cache $cache = NULL, $injected_routes = array(), array $params = array()) {

        // It's a GET request
        $this->method(static::GET);

        parent::__construct($uri, $cache, $injected_routes, $params);

        // Empty the nvp request for safety
        $this->param(array());
    }

}

?>
