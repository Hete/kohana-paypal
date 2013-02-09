<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * PayPal NVP response handler.
 * 
 * @package PayPal
 * @category Response
 * @author Hète.ca Team
 */
class Kohana_Response_PayPal_NVP extends Response_PayPal {

    public function __construct(Response $response = NULL) {

        $data = NULL;

        // Data must be parsed before the constructor call
        parse_str($response->body(), $data);

        parent::__construct($response, $data);

        // Adding default rules
        $this->rule("ACK", "not_empty");
        $this->rule("ACK", "PayPal_Valid::contained", array(":value", static::$SUCCESS_ACKNOWLEDGEMENTS));
    }

}

?>
