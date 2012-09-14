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
                ) + parent::required();
    }

}

// End PayPal_ExpressCheckout
