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
class PayPal {

    /**
     * @var  array  instances
     */
    public static $instances = array();

    /**
     * Returns a singleton instance of one of the PayPal classes.
     *
     * @param   string  class type (ExpressCheckout, PaymentsPro, etc)
     * @return  object
     */
    public static function instance($method) {
        if (!isset(PayPal::$instances[$method])) {
            // Set the class name
            $class = 'PayPal_' . $method;

            // Load default configuration
            $config = Kohana::$config->load('paypal');

            $parts = explode("_", $method);

            $paypal_method = $parts[count($parts) - 1];

            // Create a new PayPal instance with the default configuration
            PayPal::$instances[$method] = new $class($config['username'], $config['password'], $config['signature'], $paypal_method, $config['environment']);
        }

        return PayPal::$instances[$method];
    }

    // API username
    protected $_username;
    // API password
    protected $_password;
    // API signature
    protected $_signature;
    // Request method
    protected $_method;
    // Environment type
    protected $_environment = 'live';
    protected $_default = array();
    // Required fields
    protected $_required = array();

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
    public function __construct($username, $password, $signature, $method, $environment = 'live') {
        // Set the API username and password
        $this->_username = $username;
        $this->_password = $password;

        // Set the API signature
        $this->_signature = $signature;

        // Set the environment
        $this->_environment = $environment;

        if ($this->_method === NULL)
            $this->_method = $method;
    }

    public function post(array $param) {

        return $this->_post($param);
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
    private final function _post(array $params) {
        // Create POST data
        $post = array(
            'METHOD' => $this->_method,
            'VERSION' => 51.0,
            'USER' => $this->_username,
            'PWD' => $this->_password,
            'SIGNATURE' => $this->_signature,
                ) + $params + $this->_default;
      

        // Create the Request, using the client
        $request = Request::factory($this->api_url())                
                ->method(Request::POST)
                ->body(http_build_query($post));
        
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
