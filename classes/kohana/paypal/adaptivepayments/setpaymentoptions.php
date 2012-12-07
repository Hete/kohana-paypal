<?php
/**
 * SETPAYMENTOPTIONS
 * 
 * @link https://www.x.com/developers/paypal/documentation-tools/api/setpaymentoptions-api-operation
 * @author Guillaume Poirier-Morency
 * @copyright Hète.ca
 */
class Kohana_PayPal_AdaptivePayments_SetPaymentOptions extends PayPal_AdaptivePayments {

    public static $ACTION_TYPE = array(
        'PAY',
        'CREATE',
        'PAY_PRIMARY'
    );
    
    protected function rules() {
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

    

}

?>
