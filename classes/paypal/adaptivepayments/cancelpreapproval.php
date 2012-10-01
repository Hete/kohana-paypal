<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class PayPal_AdaptativePayments_CancelPreapproval extends PayPal_AdaptivePayments{
    
protected function request_rules() {
        return array(
            'preapprovalKey' => array(
                array('not_empty')
            )
        );
    }
protected function response_rules() {
        
    }
}
?>
