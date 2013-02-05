<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * PayPay request implementation for NVP protocol.
 * 
 * @see https://www.x.com/developers/paypal/documentation-tools/api/NVPAPIOverview
 * 
 * @package PayPal
 * @category Request
 * @author Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 */
abstract class Kohana_Request_PayPal_NVP extends Request_PayPal {

    public function __construct($uri = TRUE, HTTP_Cache $cache = NULL, $injected_routes = array(), array $params = array()) {

        parent::__construct($uri, $cache, $injected_routes, $params);

        $parts = explode("_", get_class($this));

        $method = $parts[count($parts) - 1];

        $method = ucfirst($method);

        // Setting the METHOD
        $this->param("METHOD", $method);
        $this->param("VERSION", 54.0);
    }

    public function api_url() {
        if ($this->_environment === 'live') {
            // Live environment does not use a sub-domain
            $env = '';
        } else {
            // Use the environment sub-domain
            $env = $this->_environment . '.';
        }

        return 'https://api-3t.' . $env . 'paypal.com/nvp';
    }

    protected function execute() {

        $this->check();

        $data = NULL;


        if ($data === NULL) {
            throw new PayPal_Exception($this, NULL, "Couldn't parse the response from PayPal.");
        }

        $response = parent::execute();

        $paypal_response = Response_PayPal_NVP::factory($response);

        // Computing redirect url
        $paypal_response->redirect_url = $this->redirect_url($paypal_response);

        // Validate the response
        if (!$paypal_response->check()) {

            // Logging the data in case of..
            $message = "PayPal response failed with code :code and version :version :shortmessage. :longmessage";
            $variables = array(
                ":version" => $paypal_response["VERSION"],
                ":code" => $paypal_response["L_ERRORCODE0"],
                ":shortmessage" => $paypal_response["L_SHORTMESSAGE0"],
                ":longmessage" => $paypal_response["L_LONGMESSAGE0"],
            );
            Log::instance()->add(Log::ERROR, $message, $variables);
            throw new PayPal_Exception($this, $paypal_response, $message, $variables, (int) $paypal_response["error(0)_errorId"]);
        }


        // Was successful, we store the correlation id and stuff in logs
        $variables = array(
            ":ack" => $paypal_response["responseEnvelope_ack"],
            ":build" => $paypal_response["responseEnvelope_build"],
            ":correlation_id" => $paypal_response["responseEnvelope_correlationId"],
            ":timestamp" => $paypal_response["responseEnvelope_timestamp"],
        );

        Log::instance()->add(Log::INFO, "PayPal request was completed with :ack :build :correlation_id at :timestamp", $variables);

        if ($paypal_response["ACK"] === static::SUCCESS_WITH_WARNING) {
            $variables += array(
                ":version" => $paypal_response["VERSION"],
                ":code" => $paypal_response["L_ERRORCODE0"],
                ":shortmessage" => $paypal_response["L_SHORTMESSAGE0"],
                ":longmessage" => $paypal_response["L_LONGMESSAGE0"],
            );
            // In case of SuccessWithWarning, log the warning
            Log::instance()->add(Log::WARNING, "PayPal request was completed with :ack :build :correlation_id at :timestamp but a warning with code :code and version :version was raised :shortmessage. :longmessage", $variables);
        }

        return $paypal_response;
    }

}

?>
