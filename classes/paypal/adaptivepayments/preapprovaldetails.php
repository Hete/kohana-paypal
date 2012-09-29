<?php

/**
 * 
 * 
 * @link https://cms.paypal.com/ca/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_APPreapprovalDetails
 * 
 * @package PayPal
 * @category AdaptativePayments
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright 
 */
class PayPal_AdaptativePayments_PreapprovalDetails extends PayPal_AdaptativePayments {

    protected function request_rules() {
        
        return array(
            'getBillingAddress' => array(
                array('boolean')
                
            ),
            
            
            
        );
        
        
    }

    protected function response_rules() {
        
    }

}


?>
