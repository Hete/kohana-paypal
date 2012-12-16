<?php

defined('SYSPATH') or die('No direct script access.');

/**
 *
 * @see  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_ECGettingStarted
 *
 * @package PayPal
 * @author Permissions
 * @copyright (c) 2012, Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
 */
class Kohana_PayPal_Permissions_GetAccessToken extends PayPal_Permissions {

    /**
     * Need a token and the verifier
     * @return type
     */
    protected function rules() {
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
