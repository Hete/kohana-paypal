<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * PayPal ExpressCheckout integration.
 *
 * @see  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_ECGettingStarted
 *
 * @package    Kohana
 * @author     Kohana Team
 * @copyright  (c) 2009 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class PayPal_Permissions_GetAccessToken extends PayPal {

    protected function redirect_command() {
        // Pas de commande de redirection.
        return "";
    }

    protected function redirect_param($results) {
        return array();
    }

    protected function rules() {
        return array();
    }

    /**
     * Need a token and the verifier
     * @return type
     */
    protected function request_rules() {
        return array(
            'token' => array(
                array('not_empty')
            ),
            'verifier' => array(
                array('not_empty')
            )
        );
    }

    protected function response_rules() {
        return array(
            'scope' => array(
                array('not_empty')
            ),
            'token' => array(
                array('not_empty')
            ),
            'tokenSecret' => array(
                array('not_empty')
            ),
        );
    }

}

// End PayPal_ExpressCheckout
