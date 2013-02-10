<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * 
 * @todo implement SOAP protocol
 * 
 * @package PayPal
 * @author Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 */
class Kohana_Response_PayPal_SOAP extends Response_PayPal {

    /**
     * 
     * @todo parse soap body
     * 
     * @param Response $response
     */
    public function __construct(Request_PayPal $request, Response $response) {

        // Parse SOAP body

        $reader = new XMLReader();
        $reader->XML($response->body());

        $output = $this->read_array($reader);

        while ($reader->read()) {
            
        }

        parent::__construct($request, $response, $output);

        $this->rule();
    }

    private function read_array(XMLReader $reader) {

        $output = array();
    }

    /**
     * 
     * @throws PayPal_Validation_Exception
     * @return Response_PayPal_SOAP
     */
    public function check() {
        // Validate the response
        if (!parent::check()) {
            // Logging the data in case of..
            $message = "PayPal response failed.";

            Log::instance()->add(Log::ERROR, $message, NULL);
            throw new PayPal_Validation_Exception($this, $this->request, $this, $message, NULL);
        }

        return $this;
    }

}

?>
