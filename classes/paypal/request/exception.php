<?php

/**
 * Exception PayPal pour gérer les Request_Exception.
 *
 * @package PayPal
 * @author     Guillaume Poirier-Morency
 * @copyright  Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
 */
class PayPal_Request_Exception extends PayPal_Exception {

    /**
     *
     * @var type 
     */
    public $request_exception;

    public function __construct(Request_Exception $request_exception, PayPal $request, array $response = array(), $message = "", array $variables = NULL, $code = 0) {

        $variables += array(
            ':message' => $request_exception->getMessage(),
            ':url' => $request->api_url(),
            ':query' => print_r($this->_request->param(), true),
        );

        $message .= " :message :url :query";

        parent::__construct($request, $response, $message, $variables, $code);
        $this->request_exception = $request_exception;
    }

    /**
     * 
     * @return PayPal
     */
    public function paypal_request() {
        return $this->_paypal_request;
    }

    /**
     * 
     * @return Request_Exception
     */
    public function request() {
        return $this->_request;
    }

}

?>
