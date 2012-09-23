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
class PayPal_Permissions_GetPermissions extends PayPal {

    protected function request_rules() {
        return array(
            'token' => array(
                array('not_empty')
            )
        );
    }

    protected function response_rules() {
        return array(
        );
    }

}

// End PayPal_ExpressCheckout
