<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * DoCapture
 * 
 * @see https://www.x.com/developers/paypal/documentation-tools/api/docapture-api-operation-nvp
 * 
 * @package   PayPal
 * @author    Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 * @license   http://kohanaframework.org/license
 */
class Kohana_PayPal_DoCapture extends PayPal {

    const COMPLETE = 'Complete', NOT_COMPLETE = 'NotComplete';

    public static $COMPLETE_TYPES = array(
        'Complete', 'NotComplete'
    );

 

    public function rules() {
        return array(
            'AUTHORIZATIONID' => array(
                array('not_empty')
            ),
            'AMT' => array(
                array('not_empty'),
                array('numeric')
            ),
            'COMPLETETYPE' => array(
                array('not_empty'),
                array('in_array', array(':value', PayPal_DoCapture::$COMPLETE_TYPES))
            ),
            'INVNUM' => array(
                array('max_length', array(':value', 127)),
            ),
            'NOTE' => array(
                array('max_length', array(':value', 255)),
            )
        );
    }

}

