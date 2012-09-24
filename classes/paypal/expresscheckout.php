<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * ExpressCheckout base class.
 * 
 * It uses api-3t, so we overload few methods.
 *
 * @link  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/library_documentation
 *
 * @package PayPal
 * @category ExpressCheckout
 * @author     Guillaume Poirier-Morency
 * @copyright  Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
 */
abstract class PayPal_ExpressCheckout extends PayPal {

    public function __construct(array $params = array()) {
        parent::__construct($params);
        // Adding METHOD to params
        $this->param("METHOD", $this->method());
    }

    /**
     * PayPal method name based on the class name.
     * In express checkout, method is stored in a key from the request.
     * @var string 
     */
    public function method() {
        $parts = explode("_", get_class($this));
        return $parts[count($parts) - 1];
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

        return 'https://api-3t.' . $env . 'paypal.com/nvp';
    }

}

?>
