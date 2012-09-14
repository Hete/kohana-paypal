<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Abstract PayPal integration.
 *
 * @link  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/library_documentation
 *
 * @package    Kohana
 * @author     Kohana Team
 * @copyright  (c) 2009 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
abstract class PayPal {

    public static function factory($class, array $params) {
        $class = "PayPal_" . $class;
        $parts = explode("_", $class);
        $method = $parts[count($parts) - 1];
        return new $class($params, $method);
    }

    // Environment type
    /**
     *
     * @var type 
     */
    protected $_environment;
    /**
     *
     * @var type 
     */
    private $_params = array();
    /**
     * PayPal method name. Can be overwritten by specifying the key METHOD in
     * the param.
     * @var string 
     */
    protected $_method;

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
    public function __construct($method, array $params = array()) {
        $this->_params = $params;
        $this->_environment = Kohana::$config->load("paypal.environment");
        $this->_method = $method;
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
            return $this->_params + $this->defaults();
        } else if ($value === NULL) {            
            return $this->_params[$key];
        } else {
            $this->_params[$key] = $value;
        }
    }

    /**
     * Default values.
     * @return type
     */
    protected function defaults() {
        return array(
            // Data from config
            'METHOD' => $this->_method,
            'VERSION' => 51.0,
            'USER' => Kohana::$config->load('paypal.username'),
            'PWD' => Kohana::$config->load('paypal.password'),
            'SIGNATURE' => Kohana::$config->load('paypal.signature'),
        );
    }

    /**
     * Key tree of required values.
     * @return type
     */
    protected function required() {
        return array('METHOD', 'VERSION', 'USER', 'PWD', 'SIGNATURE');
    }

    /**
     * Returns the NVP API URL for the current environment.
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

        return 'https://api-3t.' . $env . 'paypal.com/nvp';
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
    public function redirect_url($command, array $params) {
        if ($this->_environment === 'live') {
            // Live environment does not use a sub-domain
            $env = '';
        } else {
            // Use the environment sub-domain
            $env = $this->_environment . '.';
        }

        // Add the command to the parameters
        $params = array('cmd' => '_' . $command) + $params;

        return 'https://www.' . $env . 'paypal.com/webscr?' . http_build_query($params, '', '&');
    }

    /**
     * Makes a POST request to PayPal NVP for the given method and parameters.
     *
     * @see  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_nvp_NVPAPIOverview
     *
     * @throws  Kohana_Exception
     * @param   string  method to call
     * @param   array   POST parameters
     * @return  array
     */
    public final function execute() {
        // Create POST data        
        $request = Request::factory($this->api_url())
                ->method(Request::POST)
                ->body(http_build_query($this->param()));

        // Setup the client
        $request->client()->options(CURLOPT_SSL_VERIFYPEER, FALSE)
                ->options(CURLOPT_SSL_VERIFYHOST, FALSE);


        try {
            // Get the Response for this Request
            $response = $request->execute();
        } catch (Request_Exception $e) {
            $code = $e->getCode();
            $error = $e->getMessage();

            throw new PayPal_Exception('PayPal API request for :method failed: :error (:code). :query',
                    array(':method' => $this->_method,
                        ':error' => $error,
                        ':code' => $code,
                        ':query' => wordwrap($request->body()),
            ));
        }

        // Parse the response
        parse_str($response->body(), $data);

        if (!isset($data['ACK']) OR strpos($data['ACK'], 'Success') === FALSE) {
            throw new PayPal_Exception('PayPal API request for :method failed: :error (:code). :query',
                    array(':method' => $this->_method,
                        ':error' => $data['L_LONGMESSAGE0'],
                        ':code' => $data['L_ERRORCODE0'],
                        ':query' => wordwrap($request->body()),
            ));
        }

        return $data;
    }

}

// End PayPal
