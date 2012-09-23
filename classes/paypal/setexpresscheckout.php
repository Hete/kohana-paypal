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
class PayPal_SetExpressCheckout extends PayPal {

    protected function required() {
        return array(
            'AMT',
            'PAYMENTACTION'
        );
    }

    protected function redirect_command() {
        
    }

    protected function redirect_param($results) {
        
    }

}