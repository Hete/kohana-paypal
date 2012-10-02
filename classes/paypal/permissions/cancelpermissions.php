<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Cancel permissions of a specified token.
 *
 * @link  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/library_documentation
 *
 * @author     Guillaume Poirier-Morency
 * @copyright  Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
 */
class PayPal_Permissions_CancelPermissions extends PayPal_Permissions {

    protected function request_rules() {
        return array('token' => array(
                array('not_empty')
            )
        );
    }


}

// End PayPal_ExpressCheckout
