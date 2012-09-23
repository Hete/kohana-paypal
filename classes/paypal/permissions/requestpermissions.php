<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * RequestPermissions API Operation.
 *
 * @see  https://cms.paypal.com/ca/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_PermissionsRequestPermissionsAPI
 *
 */
class PayPal_Permissions_RequestPermissions extends PayPal {

    // Default parameters

    public function required() {

        return array(
            'scope' => array(),
            'callback',
            'requestEnvelope' => array(
                'detailLevel',
                'errorLanguage'
            )
        );
    }

    protected function redirect_param($results) {
        
    }

    protected function redirect_command() {
        
    }

}

// End PayPal_ExpressCheckout
