<?php

class PayPal_AdaptivePayments_Pay extends PayPal_AdaptivePayments {

    public static $ACTION_TYPE = array(
        'PAY',
        'CREATE',
        'PAY_PRIMARY'
    );
    
    protected function request_rules() {
        return array(
            'actionType' => array(
                'not_empty',
                'regex' => array(':value', '^'.implode('|', PayPal_AdaptivePayments_Pay::$ACTION_TYPE).'$')
            ),
            'currencyCode' => array(
                'not_empty',
                'regex' => array(':value', '^'.implode('|', PayPal_AdaptivePayments::$CURRENCIES).'$')
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
