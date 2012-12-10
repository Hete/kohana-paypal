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
abstract class Kohana_PayPal_ExpressCheckout extends PayPal {

    protected function __construct(array $params = array()) {
        parent::__construct($params);
        // SetExpressCheckout require auth data in the POST.
        $this->param("METHOD", $this->method());
        $this->param("VERSION", 51.0);
        $this->param("USER", $this->_config['username']);
        $this->param("PWD", $this->_config['password']);
        $this->param("SIGNATURE", $this->_config['signature']);
    }

    protected function response_rules() {

        return array(
            'ACK' => array(
                array('not_empty'),
                array('equals', array(':value', 'Success'))
            )
        );
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
