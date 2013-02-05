<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 */
class Kohana_Response_PayPal_SOAP extends Response_PayPal {

    public static function factory(Response $response = NULL) {
        return new Response_PayPal_NVP($response);
    }

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
        
        while($reader->read()) {
            
            
        }

        parent::__construct();
    }
    
    private function read_array(XMLReader $reader) {
        
        $output = array();
        
        
        
    }

}

?>
