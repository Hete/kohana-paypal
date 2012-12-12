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
abstract class Kohana_PayPal extends PayPal_Constants {

    /**
     * Factory class for PayPal requests.
     * @param string $class is the class' name without the PayPal_
     * @param array $params are the initial parameters.
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
     * Constructor. You may use it directly, but it is suggested to use the
     * factory, which is more convenient.
     * @see
     * @param array $params request parameters.
     */
    protected function __construct(array $params = array()) {

        // Loading current environment
        $this->_environment = Kohana::$config->load("paypal.environment");

        // Config for current environment
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

        // Basic parameters for PayPal request
        $this->_params = $params + array(
            'requestEnvelope' => '',
            'requestEnvelope_detailLevel' => 'ReturnAll',
            'requestEnvelope_errorLanguage' => Kohana::$config->load("paypal.lang"),
        );
    }

    /**
     * Build redirect url parameters.
     * 
     * The function from PayPal class does nothing, it has to be implemented
     * if the API method provide data to build a redirect url.
     * 
     * @param array $results is the PayPal response.     
     * @return array are the url parameters.
     */
    protected function redirect_params(array $results) {
        return array();
    }

    /**
     * Rules to validate the PayPal response.
     * @return array
     */
    protected function response_rules() {
        return array(
            'redirectUrl' => array(
                array('url')
            ),
            'responseEnvelope.ack' => array(
                array('not_empty'),
                array('contained', array(":value", static::$SUCCESS_ACKNOWLEDGEMENTS))
            ),
        );
    }

    /**
     * Return rules array for this request.
     * @return array
     */
    protected abstract function rules();

    /**
     * Validates the request.
     * @param string $security_token You may set a custom security token to
     * make sure the request is handled by the same client.
     * @return \PayPal for builder syntax.
     * @throws PayPal_Validation_Exception if the request is invalid
     */
    public function check($security_token = NULL) {

        if ($security_token === NULL) {
            $security_token = Security::token();
        }

        // Validate the request parameters
        $validation_request = Validation::factory($this->param())
                ->rule('requestEnvelope.errorLanguage', 'not_empty')
                ->rule('securityToken', 'Security::check', array($security_token));

        // We add custom and basic rules proper to the request
        foreach ($this->rules() as $field => $rules) {
            $validation_request->rules($field, $rules);
        }

        if (!$validation_request->check()) {
            throw new PayPal_Exception($this, NULL, "Paypal request failed to validate :errors", array(":errors" => print_r($validation_request->errors(), TRUE)));
        }

        return $this;
    }

    /**
     * No arguments, it returs the parameters array.
     * Key only, it returns the matching value.
     * Key and value act as a setter.
     * 
     * @param string $key
     * @param string $value
     * @return type
     */
    public function param($key = NULL, $value = NULL) {

        if ($key === NULL) {
            return $this->_params;
        }

        if ($value === NULL) {
            return Arr::get($this->_params, $key);
        }

        // Simple setter
        $this->_params[$key] = $value;

        return $this;
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
        }

        if ($value === NULL) {
            return Arr::get($this->_headers, $key);
        }

        // Simple setter
        $this->_headers[$key] = $value;

        return $this;
    }

    /**
     * Config access method. Uses Arr::path.
     * @param string $key
     * @param string $value
     * @return type
     */
    public function config($path = NULL, $default = NULL, $delimiter = NULL) {
        return Arr::path($this->_config, $path, $default, $delimiter);
    }

    /**
     * PayPal method name based on the class name.   
     * @return string 
     */
    public function method() {

        // Remove prefix to the class
        $method = preg_replace("(Kohana_)?PayPal_", "", get_class($this));

        // _ => / and capitalized
        return ucfirst(str_replace("_", "/", $method));
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
    protected function redirect_url(array $response_data) {

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
     * Execute the PayPal POST request and returns the result.
     *
     * @see  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_nvp_NVPAPIOverview
     *
     * @throws PayPal_Exception if the PayPal request fails at any point.
     * @return PayPal_Response an associative array with the following keys :
     *     data response : which contains the PayPal NVP response.
     *     redirectUrl : which contains the precomputed redirection url.
     * All the responses are sanitized with dots.
     * client_number will become client.number.
     */
    public final function execute($security_token = NULL) {
        if (Kohana::$profiling) {
            $benchmark = Profiler::start("PayPal", __FUNCTION__);
        }

        // Validate the request
        $this->check($security_token);

        // Create POST data        
        $request = Request::factory($this->api_url())
                ->method(Request::POST)
                ->body(http_build_query($this->param()));

        // Load headers
        foreach ($this->_headers as $key => $value) {
            $request->headers($key, $value);
        }

        // Custom setup for the cURL client
        foreach (Kohana::$config->load("paypal.curl_options") as $key => $value) {
            $request->client()->options($key, $value);
        }

        try {
            // Execute the request and parse the response
            $data = NULL;
            parse_str($request->execute()->body(), $data);
        } catch (Request_Exception $re) {
            throw new PayPal_Exception($this, NULL, $re->getMessage(), array(), $re->getCode());
        }

        // Adding the redirect url to the datas
        $data['redirectUrl'] = $this->redirect_url($data);

        $response = PayPal_Response::factory($this, $data);

        foreach ($this->response_rules() as $field => $rules) {
            $response->rules($field, $rules);
        }

        // Validate the response
        if (!$response->check()) {
            // Logging the data in case of..
            $message = "PayPal response failed with id :id at :category level. :message";
            $variables = array(
                ":category" => $response["error.category"],
                ":message" => $response["error.message"],
                ":id" => $response["error.errorId"],
            );
            Log::instance()->add(Log::ERROR, $message, $variables);
            throw new PayPal_Exception($this, $data, $message, $variables);
        }

        // Was successful, we store the correlation id and stuff in logs
        $variables = array(
            ":ack" => $response["responseEnvelope.ack"],
            ":build" => $response["responseEnvelope.build"],
            ":correlation_id" => $response["responseEnvelope.correlationId"],
            ":timestamp" => $response["responseEnvelope.timestamp"],
        );

        Log::instance()->add(Log::INFO, "PayPal request was completed with :ack :build :correlation_id at :timestamp", $variables);

        if ($response["responseEnvelope.ack"] === static::SUCCESS_WITH_WARNING) {
            $variables += array(
                ":error_id" => $response["error.error_id"],
                ":category" => $response["error.category"],
                ":message" => $response["error.message"],
            );
            // In case of SuccessWithWarning, print the warning
            Log::instance()->add(Log::WARNING, "PayPal request was completed with :ack :build :correlation_id at :timestamp but a warning with id :error_id was raised :message at :category level", $variables);
        }


        if (isset($benchmark)) {
            Profiler::stop($benchmark);
        }

        return $response;
    }

}