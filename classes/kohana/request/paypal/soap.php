<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Request implementation for SOAP protocol.
 * 
 * @see https://www.x.com/developers/paypal/documentation-tools/api/PayPalSOAPAPIArchitecture
 * 
 * @todo implement soap protocol
 * 
 * @package PayPal
 * @category Request
 * @author Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 */
abstract class Kohana_Request_PayPal_SOAP extends Request_PayPal {

    public function __construct() {

        $this->method(static::POST);

        parent::__construct($uri, $cache, $injected_routes, $params);

        // Setting up credentials

        $cred = array(
            "Credentials" => array(
                "Username",
                "Password",
                "Signature",
                "Subject"
            )
        );

        $this->headers("RequesterCredentials", $cred);
    }

    public function api_url() {
        if ($this->_environment === 'live') {
            // Live environment does not use a sub-domain
            $env = '';
        } else {
            // Use the environment sub-domain
            $env = $this->_environment . '.';
        }

        return 'https://api-3t.' . $env . 'paypal.com/2.0/';
    }

    private function parse_array(array $array) {
        
    }

    public function headers($key = NULL, $value = NULL) {

        // Override $value to parse it to array

        return parent::headers($key, $value);
    }

    public function param($key = NULL, $value = NULL) {

        // Override $value to parse it to array

        return parent::param($key, $value);
    }

    /**
     * Recursively write array to document.
     * @param XMLWriter $writer
     * @param array $array
     */
    private function write_array(XMLWriter $writer, array $array) {

        foreach ($array as $key => $value) {

            $writer->startAttribute($key);

            if (Arr::is_array($value)) {
                $this->write_array($writer, $value);
            } else {
                $writer->writeRaw($value);
            }

            $writer->endAttribute();
        }
    }

    public function execute() {

        if (Kohana::$profiling) {
            $benchmark = Profiler::start("Request_PayPal", __FUNCTION__);
        }

        $this->check();

        // Parse params to XML

        $writer = new XMLWriter();

        $writer->startDocument();

        $writer->startElement("SOAP-ENV:Envelope");

        $writer->startElement("SOAP-ENV:Header");
        // Recursively write the document
        $this->write_array($writer, $this->headers());
        $writer->endElement();

        $writer->startElement("SOAP-ENV:Body");
        // Recursively write the document
        $this->write_array($writer, $this->param());

        $writer->endElement();

        $writer->endElement();

        $writer->endDocument();

        $this->request->body($writer->outputMemory());

        $response = parent::execute();

        $parsed = new Response_PayPal_SOAP($response);

        if (isset($benchmark)) {
            Profiler::stop($benchmark);
        }

        return $parsed;
    }

}

?>
