<?php

/**
 * Exception thrown in PayPay module.
 *
 * @package PayPal
 * @author     Guillaume Poirier-Morency
 * @copyright  Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
 */
class PayPal_Request_Exception extends Request_Exception implements PayPal_Exception {

    private $_paypal_request, $_request;

    public function __construct(PayPal $request, Request_Exception $request_exception, $message = "", $code = 0) {
        $this->_request = $request;
        // Adding query and response
        $values = array(
            ':url' => $request->api_url(),
            ':query' => print_r($this->_request->param(), true),            
        );

        $message .= $request_exception->getMessage();

        $message .= " :url :query";

        parent::__construct($message, $values, $code);
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
