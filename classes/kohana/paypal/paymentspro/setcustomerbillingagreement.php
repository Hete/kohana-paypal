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
class Kohana_PayPal_PaymentsPro_SetCustomerBillingAgreement extends PayPal_PaymentsPro {

    protected function redirect_params(Response_PayPal $response_data) {
        return array(
            "token" => $response_data["Token"]
        );
    }

    protected function rules() {
        return array();
    }

}

?>
