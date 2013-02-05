<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Implementation for SVCS protocol.
 * 
 * @package payPal
 * @category Request
 * @author Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 */
abstract class Kohana_Request_PayPal_SVCS extends Request_PayPal {

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

    protected function _execute(array $data, Response $response) {

        $paypal_response = Response_PayPal::factory($data, $response);

        // Validate the response
        if (!$paypal_response->check()) {

            // Logging the data in case of..
            $message = "PayPal response failed with id :id at :category level. :message";
            $variables = array(
                ":category" => $paypal_response["error(0)_category"],
                ":message" => $paypal_response["error(0)_message"],
                ":id" => $paypal_response["error(0)_errorId"],
            );
            Log::instance()->add(Log::ERROR, $message, $variables);
            throw new PayPal_Exception($this, $paypal_response, $message, $variables, (int) $paypal_response["error(0)_errorId"]);
        }

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