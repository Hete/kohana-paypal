<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @see https://www.x.com/developers/paypal/documentation-tools/api/getexpresscheckoutdetails-api-operation-nvp
 * 
 * @package PayPal
 * @subpackage PaymentsPro
 */
class Kohana_PayPal_PaymentsPro_GetExpressCheckoutDetails extends PayPal_PaymentsPro {

    /**
     * 
     * @param Response_PayPal_NVP $response
     * @param type $index
     * @return type
     */
    public static function paymentrequest(Response_PayPal_NVP $response, $index) {

        // find and format keys

        $paymentrequest = array();

        $regex = "/^PAYMENTREQUEST_$index" . "_/";

        $keys = preg_grep($regex, array_keys($response->data()));

        foreach ($keys as $key) {
            $new_key = preg_replace($regex, "", $key);
            $paymentrequest[$new_key] = $response[$key];
        }

        return $paymentrequest;
    }

    /**
     * 
     * @param Response_PayPal_NVP $response
     * @param type $index
     * @return type
     */
    public static function item(Response_PayPal_NVP $response, $index, $product_index) {

        // find and format keys

        $paymentrequest = array();

        $regex = "/^L_PAYMENTREQUEST_$index" . "_[a-zA-Z]+$product_index/";

        $keys = preg_grep($regex, array_keys($response->data()));

        foreach ($keys as $key) {
            $new_key = rtrim(preg_replace("/^L_PAYMENTREQUEST_$index" . "_/", "", $key), $product_index);
            $paymentrequest[$new_key] = $response[$key];
        }

        return $paymentrequest;
    }

    protected function rules() {
        return array();
    }

}

?>
