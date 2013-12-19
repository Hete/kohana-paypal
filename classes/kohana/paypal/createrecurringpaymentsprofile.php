<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * CreateRecurringPaymentsProfile
 * 
 * @link https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/CreateRecurringPaymentsProfile_API_Operation_NVP/
 * 
 * @package   PayPal
 * @author    Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 * @license   http://kohanaframework.org/license
 */
class Kohana_PayPal_CreateRecurringPaymentsProfile extends PayPal {

    public static $BILLING_PERIODS = array(
        'Day', 'Week', 'SemiMonth', 'YEAR'
    );

    public static function get_request_validation(Request $request) {
        return parent::get_request_validation($request)
                ->rule('TOKEN', 'not_empty')
                ->rule('SUBSCRIBERNAME', 'max_length', array(':value', 32))
                ->rule('PROFILESTARTDATE', 'not_empty')
                ->rule('PROFILEREFERENCE', 'max_length', array(':value', 127));
    }

    public function rules() {
        return array(
           
            'DESC' => array(
                array('not_empty'),
                array('max_length', array(':value', 127))
            ),
            'MAXFAILEDPAYMENTS' => array(
                array('digit')
            ),
            'AUTOBILLOUTAMT' => array(
                array('in_array', array(':value', array('NoAutoBill', 'AddToNextBilling')))
            ),
            'BILLINGPERIOD' => array(
                array('not_empty'),
                array('in_array', array(':value', PayPal_CreateRecurringPaymentsProfile::$BILLING_PERIODS))
            ),
            'BILLINGFREQUENCY' => array(
                array('not_empty'),
                array('range', array(':value', 1, 52))
            ),
            'TRIALBILLINGPERIOD' => array(
                array('range', array(':value', 1, PHP_INT_MAX))
            ),
            'AMT' => array(
                array('not_empty'),
                array('numeric')
            ),
            'CURRENCYCODE' => array(
                array('not_empty'),
                array('in_array', PayPal::$CURRENCY_CODES)
            ),
        );
    }

}
