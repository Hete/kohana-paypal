<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * PayPal response. It has all the flexibility of Kohana validations.
 * 
 * This object is immutable.
 * 
 * @see Validation
 * 
 * @package   PayPal
 * @category  Response
 * @author    Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright (c) 2012, Hète.ca Inc.
 * @license   http://kohanaframework.org/license
 */
class Kohana_Response_PayPal extends Validation {

    /**
     * Defines all the possible acknowledgements values.
     * 
     * @var array 
     */
    public static $ACKNOWLEDGEMENTS = array(
        'Success',
        'PartialSuccess',
        'Failure',
        'SuccessWithWarning',
        'FailureWithWarning'
    );

    /**
     * Defines all the success acknowledgements
     * 
     * @var array 
     */
    public static $SUCCESS_ACKNOWLEDGEMENTS = array(
        'Success',
        'SuccessWithWarning',
        'PartialSuccess'
    );

    /**
     * Defines all the failure acknowledgements.
     * 
     * @var array 
     */
    public static $FAILURE_ACKNOWLEDGEMENTS = array(
        'Failure',
        'FailureWithWarning'
    );

    /**
     * Redirection url for this request.
     * @var string 
     */
    public $redirect_url;

    /**
     * Original response.
     * 
     * @var Response 
     */
    public $response;

    /**
     * 
     * @param Response $response from a PayPal request.
     */
    public function __construct(Response $response = NULL) {

        $data = NULL;

        // Data must be parsed before the constructor call
        parse_str($response->body(), $data);

        if ($data === NULL) {
            throw new Kohana_Exception("Couldn't parse the response from PayPal. :body", array(':body' => $response->body()));
        }

        parent::__construct($data);

        $this->response = $response;

        $this->rules('ACK', array(
            array('not_empty'),
            array('in_array', array(':value', Response_PayPal::$SUCCESS_ACKNOWLEDGEMENTS))
        ));
    }

    /**
     * 
     * @param string $key
     * @param variant $default
     * @return variant
     */
    public function data($key = NULL, $default = NULL) {

        if ($key === NULL) {
            return parent::data();
        }

        return Arr::get($this, $key, $default);
    }

    /**
     *      
     * @throws Validation_Exception 
     * @return Response_PayPal 
     */
    public function check() {

        // Validate the response
        if (!parent::check()) {

            $message = 'Failed to validate PayPal response using :environment :version. An :code :severity occured. :longmessage';
            $variables = array(
                ':environment' => PayPal::$environment,
                ':version' => PayPal::$API_VERSION,
                ':ack' => $this->data('ACK'),
                ':code' => $this->data('L_ERRORCODE0'),
                ':severity' => $this->data('L_SEVERITYCODE0'),
                ':longmessage' => $this->data('L_LONGMESSAGE0')
            );

            throw new Validation_Exception($this, $message, $variables);
        }

        return $this;
    }

}

?>
