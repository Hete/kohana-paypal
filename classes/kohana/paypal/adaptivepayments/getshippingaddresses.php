<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @link https://cms.paypal.com/ca/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_APExecutePaymentAPI
 * 
 * @package PayPal
 * @subpackage AdaptivePayments
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com> 
 * @copyright Hète.ca Inc.
 */
class Kohana_PayPal_AdaptivePayments_GetShippingAddresses extends PayPal_AdaptivePayments {

    /**
     * @todo
     * @return type
     */
    protected function rules() {
        return array(
            "key" => array(
                array("not_empty")
            )
        );
    }

}

?>
