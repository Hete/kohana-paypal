<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * DoReferenceTransaction
 * 
 * @link https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoReferenceTransaction_API_Operation_NVP/
 * 
 * @package    PayPal
 * @subpackage PaymentsPro
 * @author     Hète.ca Team
 * @copyright  (c) 2013, Hète.ca Inc.
 * @license    http://kohanaframework.org/license
 */
class Kohana_PayPal_DoReferenceTransaction extends PayPal {

    public static $PAYMENT_ACTIONS = array('Authorization', 'Sale');

    public static function get_request_validation(Request $request) {
        return parent::get_request_validation($request)
                ->rule('REFERENCEID', 'not_empty')
                ->rule('PAYMENTACTION', 'in_array', array(':value', 'Authorization', 'Sale'))
                ->rule('PAYMENTTIPE', 'in_array', array(':value', 'Authorization', 'InstantOnly'));
    }

    public function rules() {
        return array(
            'REFERENCEID' => array(
                array('not_empty')
            ),
            'PAYMENTACTION' => array(
                array('in_array', array(':value', array('Authorization', 'Sale')))
            ),
            'PAYMENTTYPE' => array(
                array('in_array', array(':value', array('Any', 'InstantOnly')))
            ),
            'IPADDRESS' => array(
                array('ip')
            )
        );
    }

}
