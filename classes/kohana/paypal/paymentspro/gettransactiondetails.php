<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @see https://www.x.com/developers/paypal/documentation-tools/api/gettransactiondetails-api-operation-nvp
 * 
 * @package PayPal
 * @subpackage PaymentsPro
 */
class Kohana_PayPal_PaymentsPro_GetTransactionDetails extends PayPal_PaymentsPro {

    public static function items(Response_PayPal $gettransactiondetails) {

        $items = array();

        for ($i = 0; preg_grep("/^L_[A-Z]+$i$/", array_keys($gettransactiondetails->data())); $i++) {
            $items[] = array(
                'NAME' => Arr::get($gettransactiondetails, "L_NAME$i"),
                'DESC' => Arr::get($gettransactiondetails, "L_DESC$i"),
                'QTY' => Arr::get($gettransactiondetails, "L_QTY$i"),
                'TAXAMT' => Arr::get($gettransactiondetails, "L_TAXAMT$i"),
                'AMT' => Arr::get($gettransactiondetails, "L_AMT$i")
            );
        }

        return $items;
    }

    protected function rules() {
        return array();
    }

}

?>
