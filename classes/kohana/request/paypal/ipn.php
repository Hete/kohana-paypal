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
abstract class Kohana_Request_PayPal_IPN extends Request_PayPal {

    public function __construct($uri = TRUE, HTTP_Cache $cache = NULL, $injected_routes = array(), array $params = array()) {

        // It's a GET request
        $this->method(static::GET);

        parent::__construct($uri, $cache, $injected_routes, $params);

        $this->param("cmd", "_" . $this->_redirect_command);
    }

    public function api_url() {
        if ($this->_environment === 'live') {
            // Live environment does not use a sub-domain
            $env = '';
        } else {
            // Use the environment sub-domain
            $env = $this->_environment . '.';
        }

        return 'https://www.' . $env . 'paypal.com/cgi-bin/webscr';
    }

    /**
     * 
     * @return Response_PayPal_NVP
     * @throws PayPal_Exception
     */
    public function execute() {

        $this->check();

        $response = parent::execute();

        $paypal_response = new Response_PayPal_IPN($this, $response);

        $paypal_response->check();

        return $paypal_response;
    }

}

?>
