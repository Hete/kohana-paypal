<?php

/**
 * Exception thrown in PayPay module.
 *
 * @package PayPal
 * @author     Guillaume Poirier-Morency
 * @copyright  Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
 */
class PayPal_Validation_Exception extends Validation_Exception implements PayPal_Exception {

    private $_request;

    public function __construct(PayPal $request, Validation $array, array $response = array(), $message = "", $code = 0) {
        $this->_request = $request;
        // Adding query and response
        $values = array(':method' => $this->_request->method(),
            ':query' => print_r($this->_request->param(), true),
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

    public function request() {
        return $this->_request;
    }

}

?>
