<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * ExpressCheckout base class.
 * 
 * It uses api-3t, so we overload few methods.
 *
 * @link  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/library_documentation
 *
 * @author     Guillaume Poirier-Morency
 * @copyright  Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
 */
abstract class PayPal_ExpressCheckout extends PayPal {

    /**
     * PayPal method name based on the class name.
     * @var string 
     */
    public function method() {

        $method = str_replace("PayPal_", "", get_class($this));

        return implode("/", explode("_", $method));
    }

    /**
     * Returns the NVP API URL for the current environment and method.
     *
     * @return  string
     */
    public function api_url() {
        if ($this->_environment === 'live') {
            // Live environment does not use a sub-domain
            $env = '';
        } else {
            // Use the environment sub-domain
            $env = $this->_environment . '.';
        }

        return 'https://api-3t.' . $env . 'paypal.com/nvp?' . $this->method();
    }

}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
