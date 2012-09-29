<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * RequestPermissions API Operation.
 *
 * @link  https://cms.paypal.com/ca/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_PermissionsRequestPermissionsAPI
 *
 * @package PayPal
 * @category ExpressCheckout
 * @author     Guillaume Poirier-Morency
 * @copyright  Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
 */
class PayPal_ExpressCheckout_GetExpressCheckoutDetails extends PayPal_ExpressCheckout {

    protected function request_rules() {
        return array(
            'token'
        );
    }

    protected function response_rules() {
        
    }

}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
