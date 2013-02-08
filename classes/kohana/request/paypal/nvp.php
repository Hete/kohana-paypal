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

    const NVP_VERSION = "72.0";

    public function __construct($uri = TRUE, HTTP_Cache $cache = NULL, $injected_routes = array(), array $params = array()) {

        // It's a GET request
        $this->method(static::GET);

        parent::__construct($uri, $cache, $injected_routes, $params);

        $parts = explode("_", get_class($this));

        $method = $parts[count($parts) - 1];

        $method = ucfirst($method);

        // Setting the METHOD
        $this->param("METHOD", $method);

        // Setting credentials
        $this->param("USER", $this->config("username"));
        $this->param("PWD", $this->config("password"));
        $this->param("SIGNATURE", $this->config("signature"));
        $this->param("VERSION", static::NVP_VERSION);
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

    public function execute() {

        $this->check();

        $data = NULL;

        parse_str(parent::execute()->body(), $data);

        if ($data === NULL) {
            throw new PayPal_Exception($this, NULL, "Couldn't parse the response from PayPal.");
        }

        $response = parent::execute();

        $paypal_response = new Response_PayPal_NVP($response);

        // Validate the response
        if (!$paypal_response->check()) {

            // Logging the data in case of..
            $message = "PayPal response failed with code :code and version :version :shortmessage :longmessage";
            $variables = array(
                ":version" => $paypal_response["VERSION"],
                ":code" => $paypal_response["L_ERRORCODE0"],
                ":shortmessage" => $paypal_response["L_SHORTMESSAGE0"],
                ":longmessage" => $paypal_response["L_LONGMESSAGE0"],
            );
            Log::instance()->add(Log::ERROR, $message, $variables);
            throw new PayPal_Exception($this, $paypal_response, $message, $variables, (int) $paypal_response["L_ERRORCODE0"]);
        }

        $paypal_response->redirect_url = $this->redirect_url($paypal_response);

        // Was successful, we store the correlation id and stuff in logs
        $variables = array(
            ":ack" => $paypal_response["ACK"],
            ":build" => $paypal_response["BUILD"],
            ":correlation_id" => $paypal_response["CORRELATIONID"],
            ":timestamp" => $paypal_response["TIMESTAMP"],
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
            Log::instance()->add(Log::WARNING, "PayPal request was completed with :ack :build :correlation_id at :timestamp but a warning with code :code and version :version was raised :shortmessage :longmessage", $variables);
        }

        return $paypal_response;
    }

}

?>
