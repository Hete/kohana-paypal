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
class Kohana_PayPal_ExpressCheckout_SetExpressCheckout extends PayPal_ExpressCheckout {

    protected $_redirect_command = "express-checkout";

    protected function redirect_params(array $results) {
        return array(
            'token' => $results['TOKEN']
        );
    }

    protected function rules() {
        return array(
            'AMT' => array(
                array('not_empty'),
            ),
            'PAYMENTACTION' => array(
                array('not_empty'),
            ),
            'ReturnURL' => array(
                array('not_empty'),
                array('url'),
            ),
            'CancelURL' => array(
                array('not_empty'),
                array('url'),
            )
        );
    }

}