<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * PayPal exception.
 *
 * @package PayPal
 * @category Exceptions
 * @author Guillaume Poirier-Morency
 * @copyright (c) 2013, Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
 */
class Kohana_PayPal_Exception extends Kohana_Exception {

    /**
     *
     * @var Request_PayPal
     */
    public $request;

    /**
     *
     * @var Response_PayPal 
     */
    public $response;

    /**
     * Constructor for PayPal exception.
     * @param Request_PayPal $request is the request that originated the exception.
     * @param Response_PayPal $response if available, the response gived by PayPal.
     * @param string $message 
     * @param array $variables
     * @param integer $code
     */
    public function __construct(Request_PayPal $request, Response_PayPal $response = NULL, $message = "PayPal request failed.", array $variables = NULL, $code = 0) {
        parent::__construct($message, $variables, $code);
        $this->request = $request;
        $this->response = $response;
    }

}

?>
