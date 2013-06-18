<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * AddressVerify
 * 
 * @link https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/AddressVerify_API_Operation_NVP/
 * 
 * @package   PayPal
 * @author    Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 * @license   http://kohanaframework.org/license
 */
class Kohana_PayPal_AddressVerify extends Request_PayPal {

    const CONFIRMED = 'Confirmed',
            UNCONFIRMED = 'Unconfirmed';
    const MATCHED = 'Matched',
            UNMATCHED = 'Unmatched';

    public function rules() {
        return array(
            'EMAIL' => array(
                array('not_empty'),
                array('email')
            ),
            'STREET' => array(
                array('not_empty')
            ),
            'ZIP' => array(
                array('not_empty'),
            )
        );
    }
}

?>
