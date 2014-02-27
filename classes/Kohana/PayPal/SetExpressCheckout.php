<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * SetExpressCheckout
 *  
 * @package Paypal
 * @author  Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @license http://kohanaframework.org/license 
 */
class Kohana_PayPal_SetExpressCheckout extends PayPal {

    public static function redirect_query(Response $response) {
    
        $response = PayPal::parse_response($response);

        return array(
            'cmd'   => 'express-checkout',
            'token' => $response['TOKEN']
        );
    }
}
