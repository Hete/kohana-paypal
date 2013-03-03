<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
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
            "ACCT" => array(
                array("not_empty"),
                array("credit_card")
            ),
            "CVV2" => array(
                array("not_empty"),
                array("max_length", array(":value", 4))
            ),
            "EXPDATE" => array(
                array("NOT_EMPTY"),
            ),
            "COUNTRYCODE" => array(
                array("not_empty"),
            ),
            "STREET" => array(
                array("not_empty"),
                array("max_length", array(":value", 100))
            ),
            "STREET2" => array(
                array("max_length", array(":value", 100))
            ),
            "CITY" => array(
                array("not_empty"),
            ),
            "STATE" => array(
                array("not_empty"),
            ),
            "ZIP" => array(
                array("not_empty"),
            ),
            "SHIPPHONENUM" => array(
                array("phone"),
            ),
            "IPADDRESS" => array(
                array("not_empty"),
                array("ip")
            ),
        );
    }

}

?>
