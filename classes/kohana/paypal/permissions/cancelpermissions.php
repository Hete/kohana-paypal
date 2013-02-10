<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Cancel permissions of a specified token.
 *
 * @see https://www.x.com/developers/paypal/documentation-tools/api/getexpresscheckoutdetails-api-operation-nvp
 *
 * @package PayPal
 * @subpackage Permissions
 * @author Guillaume Poirier-Morency
 * @copyright 2012 (c), Hète.ca Inc.
 */
class Kohana_PayPal_Permissions_CancelPermissions extends PayPal_Permissions {

    protected function rules() {
        return array('token' => array(
                array('not_empty')
            )
        );
    }


}

// End PayPal_ExpressCheckout
