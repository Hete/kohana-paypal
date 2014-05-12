<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * MassPay
 * 
 * @link https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/MassPay_API_Operation_NVP/
 * 
 * @package   PayPal
 * @author    Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @coypright (c) 2014, Hète.ca Inc.
 * @license   BSD-3-Clauses
 */
class Kohana_PayPal_MassPay extends PayPal {

    public static $RECEIVER_TYPES = array(
        'EmaiLAddress', 'UserID', 'PhoneNumber'
    );

    public function rules() {

        return array(
            'EMAILSUBJECT' => array(
                array('max_length', array(':value', 255))
            ),
            'RECEIVERTYPE' => array(
                array('in_array', array(':value', PayPal_MassPay::$RECEIVER_TYPES))
            ),
            'L_EMAIL0' => array(
                array('email')
            ),
            'L_RECEIVERPHONE0' => array(
                array('phone')
            ),
            'L_AMT0' => array(
                array('not_empty'),
                array('numeric')
            ),
            'L_UNIQUEID0' => array(
                array('max_length', array(':value', 30))
            ),
            'L_NOTE0' => array(
                array('max_length', array(':value', 4000))
            )
        );
    }

}
