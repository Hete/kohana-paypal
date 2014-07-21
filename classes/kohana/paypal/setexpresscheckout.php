<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * SetExpressCheckout
 *  
 * @package   Paypal
 * @author    Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright (c) 2014, Hète.ca Inc.
 * @license   BSD-3-Clauses
 */
class Kohana_PayPal_SetExpressCheckout extends PayPal {

    public static function redirect_query(Response $response) {

        $response = PayPal::parse_response($response);

        return array(
            'cmd' => '_express-checkout',
            'token' => $response['TOKEN']
        );
    }

}
