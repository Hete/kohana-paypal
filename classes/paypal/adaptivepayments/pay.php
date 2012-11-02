<?php

class PayPal_AdaptivePayments_Pay extends PayPal_AdaptivePayments {

    public static $ACTION_TYPE = array(
        'PAY',
        'CREATE',
        'PAY_PRIMARY'
    );
    protected $_redirect_command = 'ap-payment';

    protected function redirect_params(array $results) {        
        return array("paykey" => $results['payKey']);
    }

    protected function rules() {
        return array(
            'actionType' => array(
                array('not_empty'),
            //array('regex', array(':value', '^'.implode('|', PayPal_AdaptivePayments_Pay::$ACTION_TYPE).'$')),
            ),
            'currencyCode' => array(
                array('not_empty'),
            //array('regex', array(':value', '^'.implode('|', PayPal_AdaptivePayments::$CURRENCIES).'$')),
            ),
            'cancelUrl' => array(
                array('not_empty'),
                array('url'),
            ),
            'returnUrl' => array(
                array('not_empty'),
                array('url'),
            ),
        );
    }

}

?>
