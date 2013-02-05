<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @package PayPal
 * @category Response
 * @author 
 * @category 
 */
class Kohana_Response_PayPal_SVCS extends Response_PayPal {

    public function __construct(Response $response) {

        $data = NULL;

        // Data must be parsed before the constructor call
        parse_str($response->body(), $data);

        parent::__construct($response, $data);

        // Adding default rules
        $this->rule("responseEnvelope_ack", "not_empty");
        $this->rule("responseEnvelope_ack", "PayPal_Valid::contained", array(":value", static::$SUCCESS_ACKNOWLEDGEMENTS));
    }

}

?>
