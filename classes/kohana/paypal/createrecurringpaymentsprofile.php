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
class Kohana_PayPal_CreateRecurringPaymentsProfile extends Request_PayPal {

    public static $BILLING_PERIODS = array(
        'Day', 'Week', 'SemiMonth', 'YEAR'
    );

    public function filters() {
        return array(
            'TAXAMT' => array(
                array('PayPal::number_format')
            ),
            'SHIPPINGAMT' => array(
                array('PayPal::number_format')
            ),
            'AMT' => array(
                array('PayPal::number_format')
            )
        );
    }

    public function rules() {
        return array(
            'TOKEN' => array(
                array('not_empty')
            ),
            'SUBSCRIBERNAME' => array(
                array('max_length', array(':value', 32))
            ),
            'PROFILESTARTDATE' => array(
                array('not_empty'),
                array('date', array(':value', PayPal::DATE_FORMAT))
            ),
            'PROFILEREFERENCE' => array(
                array('max_length', array(':value', 127))
            ),
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

?>
