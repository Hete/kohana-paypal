<?php

/**
 * @link https://cms.paypal.com/ca/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_APPaymentDetails 
 */
class PaymentDetails extends PayPal_AdaptativePayments {

    protected function request_rules() {
        return array(
            'payKey' => array(
                array('not_empty')
            ),
            'transactionId' => array(
                array('not_empty')
            ),
            'trackingId' => array(
                array('not_empty')
            )
        );
    }

    protected function response_rules() {
        
    }

}

?>
