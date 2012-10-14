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
abstract class PayPal extends PayPal_Constants {

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
     * Encode a multi-dimensional array into a PayPal valid array.
     *  
     * An already encoded PayPal array will not be affected.
     * 
     * $response['requestEnvelope']['details']
     * requestEnvelope.details
     * 
     * 
     * @param array $data is the data to encode.
     * @param array $base keeps track of hiearchy. Do not specify it.
     */
    public static function encode(array $data, array $base = array()) {

        $result = array();

        foreach ($data as $key => $value) {

            $local_base = clone $base;

            // In case of assoc, $key is a string
            if (Arr::is_assoc($data)) {
                array_push($local_base, $key);
            } else {
                // Simple array, we have to specify index is parenthesis of the lase element of $base
                // blabla.blabla.list => blabla.blabla.list($key)
                // As we modify a clone for this index, list is not gone for the next element.
                $base[count($base) - 1] .= "(" . $key . ")";
            }

            // The only case of recursivity, $value is a sub_array.
            if (is_array($value)) {
                // Encoding subarray
                $result += paypal_encode($value, $result, $local_base);
            }

            if ($value instanceof PayPal_Object) {
                // On rajoute les valeurs encodés
                $result = $result + $value->encode();
            } elseif (is_object($value)) {
                throw new Kohana_Exception("Object at key :key must implement PayPal_Encodable to be encoded.", array(":key", $key));
            }

            // TODO Imploding underscores and dots
            // Imploding dots to build hiearchy     
            $result[implode(".", $local_base)] = $value;
        }

        return $result;
    }

    /**
     * Not implemented yet.
     * @param array $data
     */
    public static function decode(array $data) {
        return $data;
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
     * Basic response rules. Useful to override if targetting a special API.
     * @var array 
     */
    protected $_response_rules = array(
        'responseEnvelope_ack' => array(
            array('not_empty'),
            array('equals', array(":value", "Success"))
        ),
    );

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
    protected function redirect_param(array $results) {
        return array();
    }

    /**
     * 
     * @return type
     */
    protected abstract function rules();

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
        return implode("/", explode("_", str_replace("PayPal_", "", get_class($this))));
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
     * @throws  Request_Exception if the connection to PayPal API fails.
     * @throws PayPal_Exception if the PayPal request fails. 
     * @return  array an associative array with the following keys :
     *     response : which contains the PayPal NVP response.
     *     redirect_url : which contains the precomputed redirection url.
     */
    public final function execute($security_token = NULL) {
        if (Kohana::$profiling) {
            $benchmark = Profiler::start("PayPal", __FUNCTION__);
        }
        // Validate the request parameters
        $validation_request = Validation::factory($this->param())
                ->rule('requestEnvelope_errorLanguage', 'not_empty');

        // We add custom and basic rules proper to the request
        foreach ($this->rules() + $this->_basic_request_rules as $field => $rules) {
            $validation_request->rules($field, $rules);
        }

        if (!$validation_request->check()) {
            throw new PayPal_Validation_Exception($this, $validation_request);
        }
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
            throw new PayPal_Request_Exception($this, $re);
        }

        // Validate the response
        $validation_response = Validation::factory($data)
                // If a specific Security token is providen, match against it.
                ->rule('securityToken', 'Security::check', array($security_token === NULL ? Security::token() : $security_token));

        // We add custom and basic response rules proper to the request
        foreach ($this->_response_rules as $field => $rules) {
            $validation_response->rules($field, $rules);
        }

        if (!$validation_response->check()) {
            throw new PayPal_Validation_Exception($this, $validation_response, $data);
        }

        // Decode data for better handling
        $decoded_data = PayPal::decode($data);

        if (isset($benchmark)) {
            Profiler::stop($benchmark);
        }

        return $decoded_data + array(
            // Pre-computed redirect url
            "redirect_url" => $this->build_redirect_url($data),
        );
    }

}