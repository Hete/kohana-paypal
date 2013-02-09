<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 */
class Kohana_PayPal_PaymentsPro_SetExpressCheckout extends PayPal_PaymentsPro {

    protected $_redirect_command = "express-checkout";

    protected function redirect_params(Response_PayPal $response) {
        return array("token" => $response["TOKEN"]);
    }

    protected function rules() {
        return array();
    }

}

?>
