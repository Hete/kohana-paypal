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
class PayPal_ExpressCheckout_SetExpressCheckout extends PayPal_ExpressCheckout {

    protected function required() {
        return array(
            'AMT',
            'PAYMENTACTION'
        );
    }

    protected function redirect_command() {
        return "express-checkout";
        
    }

    protected function redirect_param(array $results) {
        return array(
            'token' => $results['token']
        );
        
    }

    protected function request_rules() {
        return array();
        
    }

    protected function response_rules() {
          return array();
        
    }

}