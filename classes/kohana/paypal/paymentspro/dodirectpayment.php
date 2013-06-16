<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Implementation of DoDirectPayment.
 * 
 * @see https://www.x.com/developers/paypal/documentation-tools/api/dodirectpayment-api-operation-nvp
 * 
 * @todo write a regex for EXPDATE 
 * 
 * @package PayPal
 * @subpackage PaymentsPro
 * @author Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 */
class Kohana_PayPal_PaymentsPro_DoDirectPayment extends PayPal_PaymentsPro {

    const NONE = "None",
            COMPLETED = "Completed",
            DENIED = "Denied";

    public static $CREDIT_CARD_TYPES = array(
        "Visa", "MasterCard", "Discover", "Amex", "Maestro"
    );

    /**
     * Expected fields from customer. To use with values method.
     * 
     * @var array 
     */
    public static $EXPECTED = array(
        "FIRSTNAME", "LASTNAME", "EMAIL", "CREDITCARDTYPE", "ACCT", "CVV2",
        "EXPDATE", "COUNTRYCODE", "STREET", "STREET2", "CITY", "STATE", "ZIP",
        "SHIPTOPHONENUM"
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
                array("credit_card", array(":value", $this->data('CREDITCARDTYPE')))
            ),
            "CVV2" => array(
                array("not_empty"),
                array("max_length", array(":value", 4))
            ),
            "EXPDATE" => array(
                array("not_empty"),
                array("alpha_numeric",),
                array("exact_length", array(":value", 6))
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
            "SHIPTOPHONENUM" => array(
                array("phone"),
            ),
            "IPADDRESS" => array(
                array("ip")
            ),
        );
    }

}

?>
