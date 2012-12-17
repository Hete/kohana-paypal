<?php

class Kohana_PayPal_AdaptivePayments_ConvertCurrency extends PayPal_AdaptivePayments {

    protected function rules() {
        return array(
            "baseAmountList.currency(0).code" => array(
                array("PayPal_Valid::contained", array(":value"), static::$CURRENCY_CODES)
            ),
            "baseAmountList.currency(0).amount" => array(
                array("numeric")
            ),
            "convertToCurrencyList.currency(0).code" => array(
                array("PayPal_Valid::contained", array(":value"), static::$CURRENCY_CODES)
            ),
        );
    }

}

?>
