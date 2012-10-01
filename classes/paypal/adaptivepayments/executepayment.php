<?php

/**
 * @link https://cms.paypal.com/ca/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_APExecutePaymentAPI
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com> 
 */
class PayPal_AdaptativePayments_ExecutePayment extends PayPal_AdaptativePayments {
    
    protected function request_rules() {
        return array(
            'payKey' => array(
                array('not_empty')
            ), 'fundingPlanId' => array(
                array('not_empty')
            ),
        );
    }

    protected function response_rules() {
        return array(
            'paymentExecStatus' => array(
                array('not_empty')
            ),
        );
    }

}

?>
