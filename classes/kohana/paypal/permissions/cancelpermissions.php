<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Cancel permissions of a specified token.
 *
 * @link  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/library_documentation
 *
 * @package PayPal
 * @category Permissions
 * @author Guillaume Poirier-Morency
 * @copyright 2012 (c), Hète.ca Inc.
 * @license http://kohanaphp.com/license.html
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