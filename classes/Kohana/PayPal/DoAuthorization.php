<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * DoAuthorization
 * 
 * @link https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoAuthorization_API_Operation_NVP/
 * 
 * @package   PayPal
 * @author    Hète.ca Team
 * @copyright (c) 2014, Hète.ca Inc.
 * @license   BSD-3-Clauses
 */
class Kohana_PayPal_DoAuthorization extends PayPal {

    public static function get_request_validation(Request $request) {

        return parent::get_request_validation($request)
                        ->rule('TRANSACTIONID', 'not_empty')
                        ->rule('AMT', 'not_empty');
    }

}
