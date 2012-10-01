<?php
/**
 * SETPAYMENTOPTIONS
 * 
 * @link https://www.x.com/developers/paypal/documentation-tools/api/setpaymentoptions-api-operation
 * @author Guillaume Poirier-Morency
 * @copyright Hète.ca
 */
class PayPal_AdaptivePayments_SetPaymentOptions extends PayPal_AdaptivePayments {

    public static $ACTION_TYPE = array(
        'PAY',
        'CREATE',
        'PAY_PRIMARY'
    );
    
    protected function request_rules() {
        return array(
            'payKey' => array(
                'not_empty',
                ),
            'shippingAddressId' => array(
            ),            
            'cancelUrl' => array(
                'not_empty',
                'url',
            ),
            'returnUrl' => array(
                'not_empty',
                'url',
            ),            
        );
    }

    protected function response_rules() {
        return array();
    }

}

?>
