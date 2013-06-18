<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * GetTransactionDetails
 * 
 * @see https://www.x.com/developers/paypal/documentation-tools/api/gettransactiondetails-api-operation-nvp
 * 
 * @package    PayPal
 * @subpackage PaymentsPro
 * @author     Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @license    http://kohanaframework.org/license
 */
class Kohana_PayPal_GetTransactionDetails extends Request_PayPal {

    /**
     * Parse items in a GetTransactionDetails response. Result is a list of 
     * associative arrays respecting the following structure:
     * 
     * {
     *     NAME:   L_NAME$i,
     *     DESC:   L_DESC$i,
     *     QTY:    L_QTY$i,
     *     TAXAMT: L_TAXAMT$i, 
     *     AMT:    L_AMT$i, 
     * }
     * 
     * @param  Response_PayPal $gettransactiondetails
     * @return array
     */
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

}

?>
