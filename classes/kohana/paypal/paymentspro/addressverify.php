<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * The AddressVerify API operation confirms whether a postal address and postal code match those of the specified PayPal account holder.
 * 
 * @link https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/AddressVerify_API_Operation_NVP/
 * 
 * @package PayPal
 * @subpackage PaymentsPro
 * @author Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 */
class Kohana_PayPal_PaymentsPro_AddressVerify extends PayPal_PaymentsPro {
    /**
     * Indicates whether the address is a confirmed address on file at PayPal.
     */
    const CONFIRMED = 'Confirmed',
            UNCONFIRMED = 'Unconfirmed';

    /**
     * Indicates whether the street address matches address information on file at PayPal.
     * 
     * Indicates whether the ZIP address matches address information on file at PayPal.
     */
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
