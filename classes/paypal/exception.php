<?php

/**
 * 
 */
class PayPal_Exception extends Validation_Exception {

    public function __construct(PayPal $request, Validation $array, array $response = array(), $message = "", $code = 0) {

        // Adding query and response
        $values = array(':method' => $request->method(),
            ':query' => print_r($request->param(), true),
            ':response' => print_r($response, true),
        );


        if (isset($response['error(0)_errorId'])) {
            $values += array(
                ':error' => $response['error(0)_message'],
                ':code' => $response['error(0)_errorId'],
            );
            $message .= "PayPal request failed.";
        } else {
            $message .= "PayPal request has failed to validate.";
        }


        $message .= " :method :query :response";



        parent::__construct($array, $message, $values, $code);
    }

}

?>
