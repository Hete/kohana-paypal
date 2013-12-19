<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * CreateBillingAgreement
 * 
 * @link https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/CreateBillingAgreement_API_Operation_NVP/
 * 
 * @package   PayPal
 * @author    Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 * @license   http://kohanaframework.org/license
 */
class Kohana_PayPal_CreateBillingAgreement extends PayPal {

    public static function get_request_validation(Request $request) {
        return parent::get_request_validation($request)
                ->rule('TOKEN', 'not_empty');
    }

}