<?php

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

    public function api_url() {
        
    }

    protected function redirect_params(Response_PayPal $response_data) {
        
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

        $redirect_url = $this->redirect_url($response_data);

        $parsed = Response_PayPal_SOAP::factory($response, $redirect_url);

        if (isset($benchmark)) {
            Profiler::stop($benchmark);
        }

        return $parsed;
    }

}

?>
