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
        return array(
            "FIRSTNAME" => array(
                array("not_empty"),
            ),
            "LASTNAME" => array(
                array("not_empty"),
            ),
            "EMAIL" => array(
                array("not_empty"),
                array("email"),
            ),
            "CREDITCARDTYPE" => array(
                array("not_empty"),
                array("PayPal_Valid::contained", array(":value", static::$CREDIT_CARD_TYPES))
            ),
            "CVV2" => array(
                array("not_empty"),
                array("max_length", array(":value", 4))
            ),
            "STREET" => array(
                array("not_empty"),
            ),
            "CITY" => array(
                array("not_empty"),
            ),
            "ZIP" => array(
                array("not_empty"),
            ),
            "SHIPPHONENUM" => array(
                array("phone"),
            ),
        );
    }

}

?>
