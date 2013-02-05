<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * 
 * @package PayPal
 * @category Response
 */
class Kohana_Response_PayPal_SOAP extends Response_PayPal {

    /**
     * 
     * @todo parse soap body
     * 
     * @param Response $response
     */
    public function __construct(Response $response) {

        // Parse SOAP body

        $reader = new XMLReader();
        $reader->XML($response->body());

        $output = $this->read_array($reader);

        while ($reader->read()) {
            
        }

        parent::__construct($response, $output);
    }

    private function read_array(XMLReader $reader) {

        $output = array();
    }

}

?>
