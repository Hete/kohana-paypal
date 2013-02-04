<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 */
class Kohana_PayPal_PaymentsPro_DoDirectPayment extends PayPal_PaymentsPro {

    protected function rules() {

        return array(
            "PaymentAction" => array(),
            "IDAddress" => array(),
            "MerchantSessionId" => array(),
            "ReturnFMFDetails" => array(),
        );
    }

}

?>
