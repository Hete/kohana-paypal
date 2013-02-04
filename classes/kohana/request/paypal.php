<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * PayPal request. This class inherit from Request to provide all the Kohana's
 * external request features.
 * 
 * @see Request
 * 
 * @package PayPal
 * @category Request
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com> * 
 * @copyright (c) 2012, Hète.ca Inc.
 */
abstract class Kohana_Request_PayPal extends Request implements PayPal_Constants {

    public static $ENVIRONMENTS = array(
        static::SANDBOX,
        static::SANDBOX_BETA,
        static::LIVE
    );

    /**
     * 
     * 
     * @deprecated use $CURRENCY_CODES 
     * @var type 
     */
    public static $CURRENCIES = array('AUD', 'BRL', 'CAD', 'CZK', 'DKK', 'EUR',
        'HKD', 'HUF', 'ILS', 'JPY', 'MYR', 'MXN', 'NOK', 'NZD', 'PHP', 'PLN',
        'GBP', 'SGD', 'SEK', 'CHF', 'TWD', 'THB', 'USD');

    /**
     * Supported currencies.
     * 
     * @var array
     */
    public static $CURRENCY_CODES = array('AUD', 'BRL', 'CAD', 'CZK', 'DKK', 'EUR',
        'HKD', 'HUF', 'ILS', 'JPY', 'MYR', 'MXN', 'NOK', 'NZD', 'PHP', 'PLN',
        'GBP', 'SGD', 'SEK', 'CHF', 'TWD', 'THB', 'USD');

    /**
     * Supported days of week.
     * 
     * @var array
     */
    public static $DAYS_OF_WEEK = array(
        'NO_DAY_SPECIFIED',
        'SUNDAY',
        'MONDAY',
        'TUESDAY',
        'WEDNESDAY',
        'THURSDAY',
        'FRIDAY',
        'SATURDAY',
    );

    /**
     * Required states.
     * 
     * @var array 
     */
    public static $REQUIRED_STATES = array(
        static::REQUIRED,
        static::NOT_REQUIRED
    );

    /**
     * Redirection command (if appliable).
     * 
     * @var string 
     */
    protected $_redirect_command = "";

    /**
     * Environment (sandbox, live or sandbox-beta). You may change this value
     * in application/config/paypal.php
     * 
     * @var string 
     */
    protected $_environment;

    /**
     * Configuration specific to the environnement. Use the config() method for
     * read-only access.
     * 
     * @var array 
     */
    private $_config;

    /**
     * Security token. This avoid request being instanciated from a client to be
     * executed by another.
     * 
     * @var string 
     */
    private $_security_token;

    /**
     * Validation object for this request.
     * 
     * @var Validation 
     */
    private $_validation;

    /**
     * Constructor for the PayPal request. Using the factory method in the 
     * PayPal class is a much better approach.
     * 
     * @param array $params
     * @param HTTP_Cache $cache
     * @param array $injected_routes
     */
    public function __construct($uri = TRUE, HTTP_Cache $cache = NULL, $injected_routes = array(), array $params = array()) {

        // Loading current environment
        $this->_environment = Kohana::$config->load("paypal.environment");

        // Config for current environment
        $this->_config = Kohana::$config->load('paypal.' . $this->_environment);

        // uri is defined by the api url.a
        $uri = $this->api_url();

        parent::__construct($uri, $cache, $injected_routes);


        // Setting client to curl
        $this->client(Request_Client_External::factory($this->config("curl.options"), static::REQUEST_CLIENT));

        // Custom setup for the cURL client
        foreach ($this->config("curl.options") as $key => $value) {
            $this->client()->options($key, $value);
        }

        // Setting default headers
        $this->headers('X-PAYPAL-SECURITY-USERID', $this->config("username"));
        $this->headers('X-PAYPAL-SECURITY-PASSWORD', $this->config("password"));
        $this->headers('X-PAYPAL-SECURITY-SIGNATURE', $this->config("signature"));
        $this->headers('X-PAYPAL-REQUEST-DATA-FORMAT', 'NV');
        $this->headers('X-PAYPAL-RESPONSE-DATA-FORMAT', 'NV');
        $this->headers("X-PAYPAL-APPLICATION-ID", $this->config("api_id"));

        // It's a post request
        $this->method(static::POST);

        $this->post($params);

        // Setting default post
        $this->post('requestEnvelope', '');
        $this->post('requestEnvelope_detailLevel', 'ReturnAll');
        $this->post('requestEnvelope_errorLanguage', $this->config("lang"));

        $this->_security_token = Security::token();
    }

    /**
     * Config access method. Uses Arr::path.
     * 
     * @param string $key
     * @param string $value
     * @return type
     */
    public function config($path, $default = NULL, $delimiter = NULL) {
        return Arr::path($this->_config, $path, $default, $delimiter);
    }

    /**
     * Alias for post() method. Defined for retrocompatibility and clearness.
     * 
     * @param type $key
     * @param type $value
     */
    public function param($key = NULL, $value = NULL) {
        return $this->post($key, $value);
    }

    /**
     * Validation rules. Must be implemented by request type.
     * 
     * @return array array of rules.
     */
    protected abstract function rules();

    /**
     * Validates the request based on its rules defined in the rules() function.
     * 
     * @param string $security_token You may set a custom security token to
     * make sure the request is handled by the same client.
     * @return PayPal_Request for builder syntax.
     * @throws PayPal_Exception if the request is invalid.
     */
    public function check() {

        // Validate the request parameters
        $this->_validation = Validation::factory($this->post())
                ->rule('requestEnvelope_errorLanguage', 'not_empty')
                ->rule('securityToken', 'Security::check', array($this->_security_token));

        // We add custom and basic rules proper to the request
        foreach ($this->rules() as $field => $rules) {
            $this->_validation->rules($field, $rules);
        }

        if (!$this->_validation->check()) {
            throw new PayPal_Exception($this, NULL, "Paypal request failed to validate :errors", array(":errors" => print_r($this->_validation->errors(), TRUE)));
        }

        return $this;
    }

    /**
     * Returns the API URL for the current environment and method.
     * @return string
     */
    public abstract function api_url();

    /**
     * Returns the redirect URL for the current environment. 
     * 
     * To edit parameters to be sent, override the redirect_params method.
     *
     * @see  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_html_Appx_websitestandard_htmlvariables#id08A6HF00TZS
     *
     * @param   array   PayPal response data.   
     * @return  string
     */
    protected function redirect_url(Response_PayPal $response_data) {

        if ($this->_environment === 'live') {
            // Live environment does not use a sub-domain
            $env = '';
        } else {
            // Use the environment sub-domain
            $env = $this->_environment . '.';
        }

        // Add the command to the parameters
        $params = array('cmd' => '_' . $this->_redirect_command) + $this->redirect_params($response_data);

        return 'https://www.' . $env . 'paypal.com/webscr?' . http_build_query($params, '', '&');
    }

    /**
     * Paramètres de redirection générés à partir de la réponse de PayPal.
     * @param Response_PayPal $response_data
     * @return array
     */
    protected function redirect_params(Response_PayPal $response_data) {
        return array();
    }

    /**
     * Execution for specific context (NVP, SOAP, etc..)
     */
    protected abstract function _execute(array $data, Response $response);

    /**
     * Validate and send the request. It also logs non-sensitive data for 
     * statistical and legal purpose.
     * 
     * @see Response_PayPal
     * 
     * @return Response_PayPal 
     * @throws PayPal_Exception if anything went wrong. Always set a try-catch
     * for it.
     */
    public function execute() {

        if (Kohana::$profiling) {
            $benchmark = Profiler::start("Request_PayPal", __FUNCTION__);
        }

        // Validate the request
        $this->check();

        // Execute the request
        $response = parent::execute();
        
        $data = NULL;

        // Data must be parsed before the constructor call
        parse_str($response->body(), $data);
        
        if($data === NULL) {
            throw new PayPal_Exception($this, NULL, "Couldn't parse the response from PayPal.");
        }

        // Parse the response in the specific execution context
        $paypal_response = $this->_execute($data, $response);

        if (isset($benchmark)) {
            Profiler::stop($benchmark);
        }

        return $paypal_response;
    }

}

?>
