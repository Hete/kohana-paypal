<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * PayPal response. It has all the flexibility of Kohana validations.
 * 
 * This object is immutable.
 * 
 * @see Validation
 * 
 * @package PayPal
 * @category Response
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright (c) 2012, Hète.ca Inc.
 */
abstract class Kohana_Response_PayPal extends Validation implements PayPal_Constants {

    /**
     * Defines all the possible acknowledgements values.
     * 
     * @var array 
     */
    public static $ACKNOWLEDGEMENTS = array(
        "Success",
        "PartialSuccess",
        "Failure",
        "SuccessWithWarning",
        "FailureWithWarning"
    );

    /**
     * Defines all the success acknowledgements
     * 
     * @var array 
     */
    public static $SUCCESS_ACKNOWLEDGEMENTS = array(
        "Success",
        "SuccessWithWarning",
        "PartialSuccess"
    );

    /**
     * Defines all the failure acknowledgements.
     * 
     * @var array 
     */
    public static $FAILURE_ACKNOWLEDGEMENTS = array(
        "Failure",
        "FailureWithWarning"
    );

    /**
     * Redirection url for this request.
     * @var string 
     */
    public $redirect_url;

    /**
     *
     * @var Request_PayPal 
     */
    public $request;

    /**
     * Original response.
     * @var Response 
     */
    public $response;

    /**
     * 
     * @param Response $response from a PayPal request.
     */
    public function __construct(Request_PayPal $request, Response $response = NULL, array $data = NULL) {

        parent::__construct($data);

        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Getter for data.
     * 
     * @param string $key
     * @param string $default
     * @return string
     */
    public function data($key = NULL, $default = NULL) {
        return $key === NULL ? parent::data() : Arr::get($this, $key, $default);
    }

    /**
     * Overwritten for auto-completion.
     * 
     * @return \Response_PayPal
     */
    public function check() {
        return parent::check();
    }

}

?>
