<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * SetExpressCheckout
 * 
 * @link https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/SetExpressCheckout_API_Operation_NVP/
 *  
 * @package Paypal
 * @author  Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @license http://kohanaframework.org/license 
 */
class Kohana_PayPal_SetExpressCheckout extends PayPal {

    public function redirect_query(array $response) {
        return array(
            'cmd' => 'express-checkout',
            'token' => $response['TOKEN']
        );
    }

}

