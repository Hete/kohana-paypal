<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * DoCapture
 * 
 * @see https://www.x.com/developers/paypal/documentation-tools/api/docapture-api-operation-nvp
 * 
 * @package   PayPal
 * @author    Hète.ca Team
 * @copyright (c) 2014, Hète.ca Inc.
 * @license   BSD-3-Clauses
 */
class Kohana_PayPal_DoCapture extends PayPal {

    const COMPLETE = 'Complete', NOT_COMPLETE = 'NotComplete';

    public static $COMPLETE_TYPES = array(
        'Complete', 'NotComplete'
    );

    public static function get_request_validation(Request $request) {

        return parent::get_request_validation($request)
                        ->rule('AUTHORIZATIONID', 'not_empty')
                        ->rule('AMT', 'not_empty')
                        ->rule('AMT', 'numeric')
                        ->rule('COMPLETETYPE', 'in_array', array(':value' => static::$COMPLETE_TYPES))
                        ->rule('INVNUM', 'max_length', array(':value', 127))
                        ->rule('NOTE', 'max_length', array(':value', 255));
    }

}
