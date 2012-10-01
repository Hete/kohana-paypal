<?php

/**
 * 
 * @link https://www.x.com/developers/paypal/documentation-tools/api/cancelpreapproval-api-operation
 * 
 * @package PayPal
 * @category AdaptativePayments
 * @author Quentin Avedissian <quentin.avedissian@gmail.com>
 * @copyright 
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
