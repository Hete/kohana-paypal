<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @see https://cms.paypal.com/ca/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_APPreapprovalDetails
 * 
 * @package PayPal
 * @subpackage AdaptativePayments
 * @author Quentin Avedissian <quentin.avedissian@gmail.com>
 * @copyright (c) 2012, Hète.ca Inc.
 */
class Kohana_PayPal_AdaptivePayments_Refund extends PayPal_AdaptivePayments {

    protected function rules() {
        return array(
            // payKey, transactionId or trackingId
            "currencyCode" => array(
                array("not_empty")
            ),
            "receiverList.receiver(0).email" => array(
                array("not_empty"),
                array("email")
            ),
            "receiverList.receiver(0).amount" => array(
                array("not_empty"),
                array("numeric")
            ),
        );
    }

}

?>
