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

		return 'https://api-3t.'.$env.'paypal.com/nvp';
	}

    /**
     * Constructor. You may use it directly, but it is suggested to use the
     * factory, which is more convenient.
     * @param array $params request parameters.
     */
    public function __construct(array $params = array()) {

		// Add the command to the parameters
		$params = array('cmd' => '_'.$command) + $params;

		return 'https://www.'.$env.'paypal.com/webscr?'.http_build_query($params, '', '&');
	}

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

} // End PayPal
