<?php

defined('SYSPATH') or die('No direct script access.');

/**

 * Abstract PayPal integration.
 *
 * @link  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/library_documentation
 * 
 * @package PayPal
 * @author     Guillaume Poirier-Morency
 * @copyright  Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
 */
abstract class PayPal {
    /**
     * Short date format supported by PayPal.
     */

    const SHORT_DATE_FORMAT = "Y-m-d\T";

    /**
     * Supported date format by PayPal.
     */
    const DATE_FORMAT = "Y-m-d\TH:i:s.BP";

    public static $CURRENCIES = array('AUD', 'BRL', 'CAD', 'CZK', 'DKK', 'EUR',
        'HKD', 'HUF', 'ILS', 'JPY', 'MYR', 'MXN', 'NOK', 'NZD', 'PHP', 'PLN',
        'GBP', 'SGD', 'SEK', 'CHF', 'TWD', 'THB', 'USD');

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

    /**
     * Environment type
     * @var string 
     */
    protected $_environment;

    /**
     * Configuration specific to the environnement
     * @var array 
     */
    protected $_config;

    /**
     * POST params values.
     * @var array 
     */
    private $_params = array();

    /**
     * Headers values.
     * @var array 
     */
    protected $_headers = array();

    /**
     * Redirection command (if appliable).
     * @var string 
     */
    protected $_redirect_command = "";

    /**
     * Basic request rules. Useful to override if targetting a special API.
     * @var array 
     */
    protected $_basic_request_rules = array(
        'requestEnvelope_errorLanguage' => array(
            array('not_empty')
        )
    );

    /**
     * Basic response rules. Useful to override if targetting a special API.
     * @var array 
     */
    protected $_basic_response_rules = array(
        'responseEnvelope_ack' => array(
            array('not_empty'),
            array('equals', array(":value", "Success"))
        )
    );

    /**
     * Build redirect url parameters.
     * 
     * The function from PayPal class does nothing, it has to be implemented
     * if the API method provide data to build a redirect url.
     * 
     * @param array $results is the PayPal response.
     * @return array are the url parameters.
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
     * Constructor. You may use it directly, but it is suggested to use the
     * factory, which is more convenient.
     * @param array $params request parameters.
     */
    public function __construct(array $params = array()) {

        $this->_environment = Kohana::$config->load("paypal.environment");

        $this->_config = Kohana::$config->load('paypal.' . $this->_environment);

        // Basic headers for PayPal request
        $this->_headers = array(
            'X-PAYPAL-SECURITY-USERID' => $this->_config['username'],
            'X-PAYPAL-SECURITY-PASSWORD' => $this->_config['password'],
            'X-PAYPAL-SECURITY-SIGNATURE' => $this->_config['signature'],
            'X-PAYPAL-REQUEST-DATA-FORMAT' => 'NV',
            'X-PAYPAL-RESPONSE-DATA-FORMAT' => 'NV',
            "X-PAYPAL-APPLICATION-ID" => $this->_config['api_id'],
        );

        $this->_params = $params + array(
            'requestEnvelope' => '',
            'requestEnvelope_errorLanguage' => Kohana::$config->load("paypal.error_lang"),
        );
    }

    /**
     * param() returns the param array, param($key) returns the value associated
     * to the key $key and param($key, $value) sets the $value at the specified
     * $key.
     * @param string $key
     * @param string $value
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
     * Headers access method. Same as param.
     * @param string $key
     * @param string $value
     * @return type
     */
    public function headers($key = NULL, $value = NULL) {
        if ($key === NULL) {
            return $this->_headers;
        } else if ($value === NULL) {
            return $this->_headers[$key];
        } else {
            $this->_headers[$key] = $value;
        }
    }

    /**
     * PayPal method name based on the class name.
     * @return string 
     */
    public function method() {

        $method = str_replace("PayPal_", "", get_class($this));

        return implode("/", explode("_", $method));
    }

    /**
     * Returns the NVP API URL for the current environment and method.
     *
     * @return string
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
     * @param   array   PayPal response data.   
     * @return  string
     */
    private function redirect_url(array $response_data) {

        if ($this->_environment === 'live') {
            // Live environment does not use a sub-domain
            $env = '';
        } else {
            // Use the environment sub-domain
            $env = $this->_environment . '.';
        }

        // Add the command to the parameters
        $params = array('cmd' => '_' . $this->_redirect_command) + $this->redirect_param($response_data);

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
        $validation_request = Validation::factory($this->param());

        // We add custom and basic rules proper to the request
        foreach ($this->request_rules() + $this->_basic_request_rules as $field => $rules) {
            $validation_request->rules($field, $rules);
        }

        if (!$validation_request->check()) {
            throw new PayPal_Validation_Exception($this, $validation_request);
        }
        // Create POST data        
        $request = Request::factory($this->api_url())
                ->method(Request::POST)
                ->body(http_build_query($this->param()));

        foreach ($this->_headers as $key => $value) {
            $request->headers($key, $value);
        }

        // Custom setup for the cURL client
        foreach (Kohana::$config->load("paypal.curl_options") as $key => $value) {
            $request->client()->options($key, $value);
        }


        try {
            // Execute the request and parse the response
            parse_str($request->execute()->body(), $data);
        } catch (Request_Exception $re) {
            throw new PayPal_Request_Exception($this, $re);
        }

        // Validate the response
        $validation_response = Validation::factory($data);

        // We add custom and basic response rules proper to the request
        foreach ($this->response_rules() + $this->_basic_response_rules as $field => $rules) {
            $validation_response->rules($field, $rules);
        }

        if (!$validation_response->check()) {
            throw new PayPal_Validation_Exception($this, $validation_response, $data);
        }



        return array(
            // Response data from PayPal
            "response" => $data,
            // Pre-computed redirect url
            "redirect_url" => $this->redirect_url($data),
            /* Token associated to the session that has initiated the request.
             * You should validate it when necessary with Security::check($token)
             * 
             * It is useful to check if it is still the same session that is 
             * requesting access when the user has been redirected from
             * PayPal.
             */
            "security_token" => Security::token()
        );
    }

}