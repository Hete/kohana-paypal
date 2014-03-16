<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * GetBalance
 * 
 * @link https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/GetBalance_API_Operation_NVP/
 * 
 * @package    PayPal
 * @subpackage PaymentsPro
 * @author     Hète.ca Team
 * @copyright  (c) 2013, Hète.ca Inc.
 * @license    http://kohanaframework.org/license
 */
class Kohana_PayPal_GetBalance extends PayPal {

    public static function get_request_validation(Response $response) {
        return parent::get_request_validation($response)
                        ->rule('RETURNALLCURRENCIES', 'in_array', array(':value', array(0, 1)));
    }

}
