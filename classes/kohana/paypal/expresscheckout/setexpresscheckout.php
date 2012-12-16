<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * SetExpressCheckout API Operation.
 *
 * @link  https://www.x.com/developers/paypal/documentation-tools/api/setexpresscheckout-api-operation-soap
 *
 * @package PayPal
 * @category ExpressCheckout
 * @author     Guillaume Poirier-Morency
 * @copyright  Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
 */
class Kohana_PayPal_ExpressCheckout_SetExpressCheckout extends PayPal_ExpressCheckout {

    protected $_redirect_command = "express-checkout";

    protected function redirect_params(Response_PayPal $results) {
        return array(
            'token' => $results['Token']
        );
    }

    protected function rules() {
        return array(
           
        );
    }

}