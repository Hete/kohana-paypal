<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * RequestPermissions API Operation.
 *
 * @see  https://cms.paypal.com/ca/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_PermissionsRequestPermissionsAPI
 *
 */
class PayPal_Permissions_RequestPermissions extends PayPal {

    protected function redirect_param($results) {
        return array('request_token' => $results['token']);
    }

    protected function redirect_command() {
        return 'grant-permission';
    }

    protected function request_rules() {
        return array(
            'callback' => array(
                array('not_empty', array(':value'))
            )
        );
    }

    protected function response_rules() {
        return array(
        );
    }

}

// End PayPal_ExpressCheckout
