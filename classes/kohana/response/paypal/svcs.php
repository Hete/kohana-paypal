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

    public function __construct(Request_PayPal $request, Response $response) {

        $data = NULL;

        // Data must be parsed before the constructor call
        parse_str($response->body(), $data);

        parent::__construct($request, $response, $data);

        // Adding default rules
        $this->rule("responseEnvelope_ack", "not_empty");
        $this->rule("responseEnvelope_ack", "PayPal_Valid::contained", array(":value", static::$SUCCESS_ACKNOWLEDGEMENTS));
    }

    /**
     * 
     * @throws PayPal_Exception
     * @return Response_PayPal_SVCS
     */
    public function check() {
        // Validate the response
        if (!parent::check()) {
            // Logging the data in case of..
            $message = "PayPal response failed with id :id at :category level. :message";
            $variables = array(
                ":category" => $this["error(0)_category"],
                ":message" => $this["error(0)_message"],
                ":id" => $this["error(0)_errorId"],
            );
            Log::instance()->add(Log::ERROR, $message, $variables);
            throw new PayPal_Exception($this->request, $this, $message, $variables, (int) $this["error(0)_errorId"]);
        }

        return $this;
    }

}

?>
