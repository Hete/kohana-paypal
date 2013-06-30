<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * SetExpressCheckout
 * 
 * @link https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/SetExpressCheckout_API_Operation_NVP/
 *  
 * @package Paypal
 * @author  Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @license http://kohanaframework.org/license 
 */
class Kohana_PayPal_SetExpressCheckout extends Request_PayPal {

    protected $_redirect_command = 'express-checkout';

    protected function redirect_params(Response_PayPal $response) {
        return array('token' => $response['TOKEN']);
    }

    public function filters() {
        return array(
            'PAYMENTREQUEST_0_ITEMAMT' => array(
                array('PayPal::number_format')
            ),
            'PAYMENTREQUEST_0_SHIPPINGAMT' => array(
                array('PayPal::number_format')
            ),
            'PAYMENTREQUEST_0_INSURANCEAMT' => array(
                array('PayPal::number_format')
            ),
            'PAYMENTREQUEST_0_SHIPDISCAMT' => array(
                array('PayPal::number_format')
            ),
            'PAYMENTREQUEST_0_HANDLINGAMT' => array(
                array('PayPal::number_format')
            ),
            'PAYMENTREQUEST_0_TAXAMT' => array(
                array('PayPal::number_format')
            ),
            'PAYMENTREQUEST_0_AMT' => array(
                array('PayPal::number_format')
            )
        );
    }

}

?>
