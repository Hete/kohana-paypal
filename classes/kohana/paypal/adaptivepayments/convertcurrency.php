<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @link https://www.x.com/developers/paypal/documentation-tools/api/convertcurrency-api-operation
 * 
 * @package PayPal
 * @category AdaptativePayments
 * @author Quentin Avedissian <quentin.avedissian@gmail.com>
 * @copyright Hète.ca Inc.
 */
class Kohana_PayPal_AdaptivePayments_ConvertCurrency extends PayPal_AdaptivePayments {

    protected function rules() {
        return array(
            "baseAmountList.currency(0).code" => array(
                array("not_empty"),
                array("PayPal_Valid::contained", array(":value", static::$CURRENCY_CODES))
            ),
            "baseAmountList.currency(0).amount" => array(
                array("not_empty"),
                array("numeric")
            ),
            "convertToCurrencyList.currencyCode(0).currencyCode" => array(
                array("not_empty"),
                array("PayPal_Valid::contained", array(":value", static::$CURRENCY_CODES))
            ),
        );
    }

}

?>
