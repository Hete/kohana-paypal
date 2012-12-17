<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @todo Finish rules for this request
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
