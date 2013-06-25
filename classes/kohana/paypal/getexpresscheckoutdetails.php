<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * GetExpressCheckoutDetails
 * 
 * @link https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/GetExpressCheckoutDetails_API_Operation_NVP/
 * 
 * @package PayPal
 * @author  Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @license http://kohanaframework.org/license
 */
class Kohana_PayPal_GetExpressCheckoutDetails extends Request_PayPal {

    /**
     * 
     * @param Response_PayPal_NVP $response
     */
    public static function payment_requests(Response_PayPal_NVP $response) {
        
    }

    /**
     * 
     * @param Response_PayPal_NVP $response
     * @param type $index
     * @return type
     */
    public static function payment_request(Response_PayPal $response, $index) {

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

    public static function items(Response_PayPal $response, $index) {
        
    }

    /**
     * 
     * 
     * @param Response_PayPal_NVP $response
     * @param type $index
     * @return type
     */
    public static function item(Response_PayPal $response, $index, $product_index) {

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

}

?>
