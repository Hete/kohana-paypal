<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * PayPal request. This class inherit from Request to provide all the Kohana's
 * external request features.
 * 
 * @package   PayPal
 * @category  Requests
 * @author    Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright (c) 2013, Hète.ca Inc.
 * @license   http://kohanaframework.org/license
 */
abstract class Kohana_Request_PayPal extends Request {

    /**
     * Redirection command (if appliable).
     * 
     * You should also fill redirect_params in your request.
     * 
     * @var string 
     */
    protected $_redirect_command = '';

    /**
     * Validation object for this request.
     * 
     * @var Validation 
     */
    protected $_validation;

    /**
     * Configuration specific to the environnement. Use the config() method for
     * read-only access.
     * 
     * @var array 
     */
    protected $_config = array();

    /**
     * Request data
     * 
     * @var array 
     */
    protected $_data = array();

    /**
     * Filters
     * 
     * @var array
     */
    protected $_filters = array();

    /**
     * Constructor for the PayPal request. Using the factory method in the 
     * PayPal class is a much better approach.
     * 
     * @param array $data
     * @param HTTP_Cache $cache
     * @param array $injected_routes
     */
    public function __construct($uri = TRUE, HTTP_Cache $cache = NULL, $injected_routes = array(), array $data = NULL, array $expected = NULL) {

        parent::__construct($this->api_url(), $cache, $injected_routes);

        // Bind query to data
        $this->_get = &$this->_data;

        // Config for current environment
        $this->_config = Kohana::$config->load('paypal.' . PayPal::$environment);

        $parts = explode('_', get_class($this));
        $method = $parts[count($parts) - 1];

        $this->_data['METHOD'] = $method;
        $this->_data['USER'] = $this->_config['username'];
        $this->_data['PWD'] = $this->_config['password'];
        $this->_data['SIGNATURE'] = $this->_config['signature'];
        $this->_data['VERSION'] = PayPal::$API_VERSION;

        // Custom setup for the cURL client
        $this->client(Request_Client_External::factory($this->_config['client']));

        $this->values($data, $expected);

        // Load validations
        $this->_validation = Validation::factory($this->_data);

        // Add labels
        $this->_validation->labels($this->labels());

        // Add rules
        foreach ($this->rules() as $field => $rules) {
            $this->_validation->rules($field, $rules);
        }

        // Add filters
        $this->_filters = $this->filters();
    }

    protected function api_environment() {
        return PayPal::$environment === 'live' ? '' : PayPal::$environment . '.';
    }

    /**
     * Returns the API URL for the current environment and method.
     * 
     * @return string
     */
    public function api_url() {
        return "https://api-3t.{$this->api_environment()}paypal.com/nvp";
    }

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

        // Fetch specific parameters
        $params = $this->redirect_params($response_data);

        // Add the cmd 
        $params['cmd'] = '_' . $this->_redirect_command;

        return "https://www.{$this->api_environment()}paypal.com/cgi-bin/webscr" . URL::query($params);
    }

    /**
     * Paramètres de redirection générés à partir de la réponse de PayPal.
     * 
     * @param Response_PayPal $response_data
     * @return array
     */
    protected function redirect_params(Response_PayPal $response_data) {
        return array();
    }

    public function data($key = NULL, $value = NULL) {

        if (is_array($key)) {
            $this->_data = $key;
            return $this;
        }

        if ($key === NULL) {
            return $this->_data;
        }

        if ($value === NULL) {
            return Arr::get($this->_data, $key);
        }

        $value = $this->run_filter($key, $value);

        $this->_data[$key] = $value;

        return $this;
    }

    public function values(array $values, array $expected = NULL) {

        if ($expected === NULL) {
            $expected = array_keys($values);
        }

        foreach ($expected as $field) {

            if (!array_key_exists($field, $values))
                continue;

            $this->data($field, $values[$field]);
        }

        return $this;
    }

    protected function run_filter($field, $value) {

        $filters = $this->filters();

        // Get the filters for this column
        $wildcards = empty($filters[TRUE]) ? array() : $filters[TRUE];

        // Merge in the wildcards
        $filters = empty($filters[$field]) ? $wildcards : array_merge($wildcards, $filters[$field]);

        // Bind the field name and model so they can be used in the filter method
        $_bound = array
            (
            ':field' => $field,
            ':model' => $this,
        );

        foreach ($filters as $array) {
            // Value needs to be bound inside the loop so we are always using the
            // version that was modified by the filters that already ran
            $_bound[':value'] = $value;

            // Filters are defined as array($filter, $params)
            $filter = $array[0];
            $params = Arr::get($array, 1, array(':value'));

            foreach ($params as $key => $param) {
                if (is_string($param) AND array_key_exists($param, $_bound)) {
                    // Replace with bound value
                    $params[$key] = $_bound[$param];
                }
            }

            if (is_array($filter) OR !is_string($filter)) {
                // This is either a callback as an array or a lambda
                $value = call_user_func_array($filter, $params);
            } elseif (strpos($filter, '::') === FALSE) {
                // Use a function call
                $function = new ReflectionFunction($filter);

                // Call $function($this[$field], $param, ...) with Reflection
                $value = $function->invokeArgs($params);
            } else {
                // Split the class and method of the rule
                list($class, $method) = explode('::', $filter, 2);

                // Use a static method call
                $method = new ReflectionMethod($class, $method);

                // Call $Class::$method($this[$field], $param, ...) with Reflection
                $value = $method->invokeArgs(NULL, $params);
            }
        }

        return $value;
    }

    /**
     * Filters.
     * 
     * Filters are regex => callback.
     * 
     * @return array
     */
    public function filters() {
        return array();
    }

    /**
     * Get the validation object for this request.
     * 
     * @return Validation
     */
    public function validation() {
        return $this->_validation;
    }

    /**
     * 
     * @param type $key
     * @param type $value
     * @return Request_PayPal
     */
    public function bind($key, $value = NULL) {

        $this->_validation->bind($key, $value);

        return $this;
    }

    /**
     * 
     * @param type $key
     * @param type $value
     * @return Request_PayPal
     */
    public function label($field, $label) {
        $this->_validation->label($field, $label);
        return $this;
    }

    /**
     * 
     * @param type $key
     * @param type $value
     * @return Request_PayPal
     */
    public function rule($field, $rule, array $param = NULL) {
        $this->_validation->rule($field, $rule, $param);
        return $this;
    }

    /**
     * 
     * @param type $key
     * @param type $value
     * @return Request_PayPal
     */
    public function errors($file, array $param = NULL) {
        $this->_validation->error($file, $param);
        return $this;
    }

    /**
     * Validates the request based on its rules defined in the rules() function.
     * 
     * @param string $security_token You may set a custom security token to
     * make sure the request is handled by the same client.
     * @return PayPal_Request for builder syntax.
     * @throws PayPal_Validation_Exception if the request is invalid.
     */
    public function check() {

        // Update the validation
        $this->_validation = $this->_validation->copy($this->_data);

        if (!$this->_validation->check()) {
            throw new Validation_Exception($this->_validation, 'Failed to validate PayPal request using :environment :version', array(
        ':environment' => PayPal::$environment,
        ':version' => PayPal::$API_VERSION
            ));
        }

        return $this;
    }

    /**
     * Labels
     * 
     * @return array
     */
    public function labels() {
        return array();
    }

    /**
     * Rules
     * 
     * @return array 
     */
    public function rules() {
        return array();
    }

    /**
     * Overriden for auto-completion.
     * 
     * @return Response_PayPal
     */
    public function execute() {   

        // Validate the request
        $this->check();

        $response = new Response_PayPal(parent::execute());

        $response->check();

        $response->redirect_url = $this->redirect_url($response);

        return $response;
    }

}

?>
