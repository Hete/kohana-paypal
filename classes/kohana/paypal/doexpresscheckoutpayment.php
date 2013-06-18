<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * DoExpressCheckoutPayment
 * 
 * @link https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoExpressCheckoutPayment_API_Operation_NVP/
 * 
 * @package PayPal
 * @author  Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @license http://kohanaframework.org/license
 */
class Kohana_PayPal_DoExpressCheckoutPayment extends Request_PayPal {

    public function filters() {
        return array(
            '.*AMT' => array(
                array('PayPal::number_format')
            )
        );
    }

}

?>
