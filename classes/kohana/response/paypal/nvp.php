<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * PayPal NVP response handler.
 * 
 * @package PayPal
 * @author Hète.ca Team
 */
class Kohana_Response_PayPal_NVP extends Response_PayPal {

    public function __construct(Request_PayPal $request, Response $response) {

        $data = NULL;

        // Data must be parsed before the constructor call
        parse_str($response->body(), $data);

        parent::__construct($request, $response, $data);

        // Adding default rules
        $this->rule("ACK", "not_empty");
        $this->rule("ACK", "PayPal_Valid::contained", array(":value", static::$SUCCESS_ACKNOWLEDGEMENTS));
    }

    public function check() {

        // Validate the response
        if (!parent::check()) {

            // Logging the data in case of..
            $message = "PayPal response failed with code :code and version :version :shortmessage :longmessage";
            $variables = array(
                ":version" => $this["VERSION"],
                ":code" => $this["L_ERRORCODE0"],
                ":shortmessage" => $this["L_SHORTMESSAGE0"],
                ":longmessage" => $this["L_LONGMESSAGE0"],
            );
            Log::instance()->add(Log::ERROR, $message, $variables);
            throw new PayPal_Exception($this->request, $this, $message, $variables, (int) $this["L_ERRORCODE0"]);
        }

        return $this;
    }

}

?>
