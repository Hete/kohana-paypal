<?php

/**
 * 
 */
class PayPal_Exception extends Kohana_Exception {

    public function __construct($paypal_response) {
        $response = var_dump($paypal_response);
        $variables = array(':method' => "",
            ':error' => $paypal_response['error(0)_message'],
            ':code' => $paypal_response['error(0)_errorId'],
            ':response' => $response,
        );

        parent::__construct('PayPal API request for :method failed: :error (:code). :response', $variables);
    }

}

?>
