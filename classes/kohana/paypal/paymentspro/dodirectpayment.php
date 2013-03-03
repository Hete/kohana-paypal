<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @see https://www.x.com/developers/paypal/documentation-tools/api/dodirectpayment-api-operation-nvp
 * 
 * @package PayPal
 * @subpackage PaymentsPro
 */
class Kohana_PayPal_PaymentsPro_DoDirectPayment extends PayPal_PaymentsPro {

    public static $CREDIT_CARD_TYPES = array(
        "Visa", "MasterCard", "Discover", "Amex", "Maestro"
    );

    protected function rules() {
        return array();
    }

}

?>
