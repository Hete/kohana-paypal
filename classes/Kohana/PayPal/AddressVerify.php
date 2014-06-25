<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * AddressVerify
 * 
 * @link https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/AddressVerify_API_Operation_NVP/
 * 
 * @package   PayPal
 * @author    Hète.ca Team
 * @copyright (c) 2014, Hète.ca Inc.
 * @license   BSD-3-Clauses
 */
class Kohana_PayPal_AddressVerify extends PayPal {

    const CONFIRMED = 'Confirmed', UNCONFIRMED = 'Unconfirmed';
    const MATCHED = 'Matched', UNMATCHED = 'Unmatched';

    public static function get_request_validation(Request $request) {

        return parent::get_request_validation($request)
                        ->rule('EMAIL', 'not_empty')
                        ->rule('EMAIL', 'email')
                        ->rule('STREET', 'not_empty')
                        ->rule('ZIP', 'not_empty');
    }

}
