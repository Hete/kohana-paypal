<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * DoDirectPayment
 * 
 * @link https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoDirectPayment_API_Operation_NVP/
 * 
 * @package    PayPal
 * @subpackage PaymentsPro
 * @author     Hète.ca Team
 * @copyright  (c) 2013, Hète.ca Inc.
 * @license    http://kohanaframework.org/license
 */
class Kohana_PayPal_DoDirectPayment extends PayPal {

    const NONE = 'None',
            COMPLETED = 'Completed',
            DENIED = 'Denied';

    public static $CREDIT_CARD_TYPES = array(
        'Visa', 'MasterCard', 'Discover', 'Amex', 'Maestro'
    );

    /**
     * Expected fields from customer. To use with values method.
     * 
     * @var array 
     */
    public static $EXPECTED = array(
        'FIRSTNAME', 'LASTNAME', 'EMAIL', 'CREDITCARDTYPE', 'ACCT', 'CVV2',
        'EXPDATE', 'COUNTRYCODE', 'STREET', 'STREET2', 'CITY', 'STATE', 'ZIP',
        'SHIPTOPHONENUM'
    );

    public static function get_request_validation(Request $request) {
        return parent::get_request_validation($request)
            ->rule('FIRSTNAME', 'not_empty')
            ->rule('LASTNAME', 'not_empty')
            ->rules('EMAIL', array(
                array('not_empty'),
                array('email')))
            ->rules('CREDITCARDTYPE', array(
                array('not_empty'),
                array('in_array', array(':value', PayPal_DoDirectPayment::$CREDIT_CARD_TYPES))))
            ->rules('ACCT', array(
                array('not_empty'),
                array('credit_card', array(':value', $request->query('CREDITCARDTYPE')))
            ))
            ->rules('CVV2', array(
                array('not_empty'),
                array('max_length', array(':value', 4))
            ))
            ->rules('EXPDATE', array(
                array('not_empty'),
                array('data', array(':value', 'mY'))
            ))
            ->rules('COUNTRYCODE', array(
                array('not_empty'),
                array('exact_length', array(':value', 2))
            ))
            ->rules('STREET',array(
                array('not_empty'),
                array('max_length', array(':value', 100))
            ))
            ->rule('STREET2', 'max_length', array(':value', 100))
            ->rule('CITY', 'not_empty')
            ->rule('STATE', 'not_empty')
            ->rule('ZIP', 'not_empty')
            ->rule('SHIPTOPHONENUM', 'phone')
            ->rule('IPADRESS', 'ip');
    }
}
