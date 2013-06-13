<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @see https://www.x.com/developers/paypal/documentation-tools/api/setexpresscheckout-api-operation-nvp
 * 
 * @package Paypal
 * @subpackage PaymentsPro
 */
class Kohana_PayPal_PaymentsPro_SetExpressCheckout extends PayPal_PaymentsPro {

    protected $_redirect_command = "express-checkout";

    protected function redirect_params(Response_PayPal $response) {
        return array("token" => $response["TOKEN"]);
    }
    
    protected function filters() {
        return array(
            'PAYMENTREQUEST_.+_AMT' => array(
                array('PayPal::number_format') // Number format amounts
            )
        );
    }

    protected function rules() {
        return array();
    }

}

?>
