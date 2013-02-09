<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @see elementName
 * 
 * @package PayPal
 * @category PaymentsPro
 * @author Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
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
