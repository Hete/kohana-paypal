<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Implementation for SVCS protocol. This only supported by AdaptiveAccounts
 * and AdaptivePayments api. 
 * 
 * @package payPal
 * @category Request
 * @author Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 */
abstract class Kohana_Request_PayPal_SVCS extends Request_PayPal {

    public function __construct($uri = TRUE, HTTP_Cache $cache = NULL, $injected_routes = array(), array $params = array()) {

        // It's a post request
        $this->method(static::POST);

        parent::__construct($uri, $cache, $injected_routes, $params);

        // Setting default headers
        $this->headers('X-PAYPAL-SECURITY-USERID', $this->config("username"));
        $this->headers('X-PAYPAL-SECURITY-PASSWORD', $this->config("password"));
        $this->headers('X-PAYPAL-SECURITY-SIGNATURE', $this->config("signature"));
        $this->headers('X-PAYPAL-REQUEST-DATA-FORMAT', 'NV');
        $this->headers('X-PAYPAL-RESPONSE-DATA-FORMAT', 'NV');
        $this->headers("X-PAYPAL-APPLICATION-ID", $this->config("api_id"));

        // Setting default post
        $this->param('requestEnvelope', '');
        $this->param('requestEnvelope_detailLevel', 'ReturnAll');
        $this->param('requestEnvelope_errorLanguage', $this->config("lang"));

        $this->rule("requestEnvelope_errorLanguage", "not_empty");
    }

    public function api_url() {
        if ($this->_environment === 'live') {
            // Live environment does not use a sub-domain
            $env = '';
        } else {
            // Use the environment sub-domain
            $env = $this->_environment . '.';
        }

        $unappended = preg_replace("/(Kohana_)?PayPal_/", "", get_class($this));
        // Remove prefix to the class, _ => / and capitalized
        $method = ucfirst(str_replace("_", "/", $unappended));

        return 'https://svcs.' . $env . 'paypal.com/' . $method;
    }

    /**
     * 
     * @return Response_PayPal_SVCS
     * @throws PayPal_Exception
     */
    public function execute() {

        $this->check();

        $response = parent::execute();

        $paypal_response = new Response_PayPal_SVCS($this, $response);

        $paypal_response->check();

        // Adding the redirect url to the datas
        $paypal_response->redirect_url = $this->redirect_url($paypal_response);

        // Was successful, we store the correlation id and stuff in logs
        $variables = array(
            ":ack" => $paypal_response["responseEnvelope_ack"],
            ":build" => $paypal_response["responseEnvelope_build"],
            ":correlation_id" => $paypal_response["responseEnvelope_correlationId"],
            ":timestamp" => $paypal_response["responseEnvelope_timestamp"],
        );

        Log::instance()->add(Log::INFO, "PayPal request was completed with :ack :build :correlation_id at :timestamp", $variables);

        if ($paypal_response["responseEnvelope_ack"] === static::SUCCESS_WITH_WARNING) {
            $variables += array(
                ":error_id" => $paypal_response["error(0)_error_id"],
                ":category" => $paypal_response["error(0)_category"],
                ":message" => $paypal_response["error(0)_message"],
            );
            // In case of SuccessWithWarning, print the warning
            Log::instance()->add(Log::WARNING, "PayPal request was completed with :ack :build :correlation_id at :timestamp but a warning with id :error_id was raised :message at :category level", $variables);
        }

        return $paypal_response;
    }

}

?>
