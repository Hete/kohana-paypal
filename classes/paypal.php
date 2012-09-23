<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Abstract PayPal integration.
 *
 * @link  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/library_documentation
 *
 * @author     Guillaume Poirier-Morency
 * @copyright  Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
 */
abstract class PayPal {

    /**
     * Valide de façon récursive un tableau associatif avec un tableau de clés.
     * @param type $data
     * @param type $req
     * @return boolean
     */
    private static function check_required_array_key_recursive($data, $req) {
        // no more required fields
        if (empty($req))
            return true;

        // no more data fields; obviously lacks required field(s)
        if (empty($data))
            return false;


        foreach ($req as $name => $subtree) {
            // unnamed; it's a list
            if (is_numeric($name)) {
                foreach ($data as $dataitem) {
                    if (PayPal::check_required_array_key_recursive($dataitem, $subtree) == false)
                        return false;
                }
            } else {
                // required field doesn't exist
                if (!isset($data[$name]))
                    return false;

                // fine so far; down we go
                if (!empty($subtree)
                        && PayPal::check_required_array_key_recursive($data[$name], $subtree) == false
                ) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * 
     * @param string $class 
     * @param array $params
     * @return \class
     */
    public static function factory($class, array $params = array()) {
        $class = "PayPal_" . $class;
        return new $class($params);
    }

    // Environment type
    /**
     *
     * @var type 
     */
    protected $_environment;

    /**
     * Basic params values.
     * @var type 
     */
    private $_params = array();

    /**
     * 
     * @var type 
     */
    private $_headers = array();

    /**
     * Commande de redirection.
     * @return string
     */
    protected function redirect_command() {
        return "";
    }

    /**
     * Construit les paramètres de redirection avec le résultat de la 
     * requête PayPal en API.
     * @param array $results
     * @return type
     */
    protected function redirect_param(array $results) {
        return array();
    }

    /**
     * Return the validation array for the specified request.
     * @return type
     */
    protected abstract function request_rules();

    /**
     * Return the validation array for the PayPal response.
     */
    protected abstract function response_rules();

    /**
     * PayPal method name based on the class name.
     * @var string 
     */
    public function method() {

        $method = str_replace("PayPal_", "", get_class($this));

        return implode("/", explode("_", $method));
    }

    /**
     * Creates a new PayPal instance for the given username, password,
     * and signature for the given environment.
     *
     * @param   string  API username
     * @param   string  API password
     * @param   string  API signature
     * @param   string  environment (one of: live, sandbox, sandbox-beta)
     * @return  void
     */
    public function __construct(array $params = array()) {

        $this->_environment = Kohana::$config->load("paypal.environment");

        $config = Kohana::$config->load('paypal.' . $this->_environment);

        // Basic headers
        $this->_headers = array(
            'X-PAYPAL-SECURITY-USERID' => $config['username'],
            'X-PAYPAL-SECURITY-PASSWORD' => $config['password'],
            'X-PAYPAL-SECURITY-SIGNATURE' => $config['signature'],
            'X-PAYPAL-REQUEST-DATA-FORMAT' => 'NV',
            'X-PAYPAL-RESPONSE-DATA-FORMAT' => 'NV',
            "X-PAYPAL-APPLICATION-ID" => $config['api_id'],
        );

        $this->_params = $params + array(
            'requestEnvelope_errorLanguage' => 'fr_CA',
        );
    }

    /**
     * param() returns the param array, param($key) returns the value associated
     * to the key $key and param($key, $value) sets the $value at the specified
     * $key.
     * @param type $key
     * @param type $value
     * @return type
     */
    public function param($key = NULL, $value = NULL) {
        if ($key === NULL) {
            return $this->_params;
        } else if ($value === NULL) {
            return $this->_params[$key];
        } else {
            $this->_params[$key] = $value;
        }
    }

    /**
     * Returns the NVP API URL for the current environment and method.
     *
     * @return  string
     */
    public function api_url() {
        if ($this->_environment === 'live') {
            // Live environment does not use a sub-domain
            $env = '';
        } else {
            // Use the environment sub-domain
            $env = $this->_environment . '.';
        }

        return 'https://svcs.' . $env . 'paypal.com/' . $this->method();
    }

    /**
     * Returns the redirect URL for the current environment.
     *
     * @see  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_html_Appx_websitestandard_htmlvariables#id08A6HF00TZS
     *
     * @param   string   PayPal command
     * @param   array    GET parameters
     * @return  string
     */
    private function redirect_url($response_data) {

        if ($this->_environment === 'live') {
            // Live environment does not use a sub-domain
            $env = '';
        } else {
            // Use the environment sub-domain
            $env = $this->_environment . '.';
        }

        // Add the command to the parameters
        $params = array('cmd' => '_' . $this->redirect_command()) + $this->redirect_param($response_data);

        return 'https://www.' . $env . 'paypal.com/webscr?' . http_build_query($params, '', '&');
    }

    /**
     * Execute the PayPal POST request and returns the result.
     *
     * @see  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_nvp_NVPAPIOverview
     *
     * @throws  Request_Exception if the connection to PayPal API fails.
     * @throws PayPal_Exception if the PayPal request fails. 
     * @return  array an associative array with the following keys :
     *     response : which contains the PayPal NVP response.
     *     redirect_url : which contains the precomputed redirection url.
     */
    public final function execute() {

        // Validate the request parameters
        $validation_request = Validation::factory($this->param())
                // We define basic rules
                ->rule('requestEnvelope_errorLanguage', 'not_empty');

        // We add custom rules proper to the request
        foreach ($this->request_rules() as $field => $rules) {
            $validation_request->rules($field, $rules);
        }

        if (!$validation_request->check()) {
            throw new PayPal_Exception($this, $validation_request);
        }

        // Create POST data        
        $request = Request::factory($this->api_url())
                ->method(Request::POST)
                ->body(http_build_query($this->param()));

        foreach ($this->_headers as $key => $value) {
            $request->headers($key, $value);
        }

        // Setup the client
        $request->client()->options(CURLOPT_SSL_VERIFYPEER, FALSE)
                ->options(CURLOPT_SSL_VERIFYHOST, FALSE);

        // Execute the request and parse the response
        parse_str($request->execute()->body(), $data);

        // Validate the response
        $validation_response = Validation::factory($data)
                // Basic response validations in response envelope.
                ->rule('responseEnvelope_ack', 'not_empty')
                ->rule('responseEnvelope_ack', 'equals', array(":value", "Success"));

        // We add custom response rules proper to the request
        foreach ($this->response_rules() as $field => $rules) {
            $validation_response->rules($field, $rules);
        }

        if (!$validation_response->check()) {
            throw new PayPal_Exception($this, $validation_response, $data);
        }

        return array(
            // Response data for multiple purposes
            "response" => $data,
            // Pre-computed redirect url
            "redirect_url" => $this->redirect_url($data)
        );
    }

}

// End PayPal
