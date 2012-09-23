<?php

/**
 * 
 */
class PayPal_Exception extends Kohana_Exception {

    public function __construct($request_params, $paypal_response) {
        $variables = array(':method' => "",
            ':error' => $paypal_response['error(0)_message'],
            ':code' => $paypal_response['error(0)_errorId'],
            ':query' => print_r($request_params, true),
            ':response' => print_r($paypal_response, true),
        );

        parent::__construct('PayPal API request for :method failed: :error (:code). :query :response', $variables);
    }

}

?>
