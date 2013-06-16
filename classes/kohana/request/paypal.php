<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * PayPal request. This class inherit from Request to provide all the Kohana's
 * external request features.
 * 
 * @package PayPal
 * @category Requests
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright (c) 2013, Hète.ca Inc.
 */
abstract class Kohana_Request_PayPal extends Request implements PayPal_Constants {

    /**
     *
     * @var array 
     */
    public static $ENVIRONMENTS = array(
        'sandbox',
        'sandbox-beta',
        'live'
    );

    /**
     * 
     * 
     * @deprecated use $CURRENCY_CODES 
     * @var array 
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
     * Supported months of year.
     * 
     * @var array
     */
    public static $MONTHS_OF_YEAR = array(
        'NO_MONTH_SPECIFIED',
        'JANUARY',
        'FEBRUARY',
        'MARCH',
        'APRIL',
        'MAY',
        'JUNE',
        'JULY',
        'AUGUST',
        'SEPTEMBER',
        'OCTOBER',
        'NOVEMBER',
        'DECEMBER',
    );

    /**
     * Required states.
     * 
     * @var array 
     */
    public static $REQUIRED_STATES = array(
        'REQUIRED',
        'NOT_REQUIRED'
    );

    /**
     * Redirection command (if appliable).
     * 
     * You should also fill redirect_params in your request.
     * 
     * @var string 
     */
    protected $_redirect_command = '';

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
    protected $_config = array();

    /**
     *
     * @var array 
     */
    protected $_data = array();

    /**
     * Validation object for this request.
     * 
     * @var Validation 
     */
    protected $_validation;

    /**
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
    public function __construct($uri = TRUE, HTTP_Cache $cache = NULL, $injected_routes = array(), array $data = array(), array $expected = NULL) {

        // Loading current environment
        $this->_environment = PayPal::$default_environment;

        // Config for current environment
        $this->_config = Kohana::$config->load('paypal.' . $this->_environment);

        // uri is defined by the api url.
        $uri = $this->api_url();

        parent::__construct($uri, $cache, $injected_routes);

        // Custom setup for the cURL client
        $this->client(Request_Client_External::factory($this->config('client', array())));

        $this->values($data, $expected);

        $this->_filters = $this->filters();

        // Load validations
        $this->_validation = Validation::factory($this->_data)
                ->labels($this->labels());

        // We add custom and basic rules proper to the request
        foreach ($this->rules() as $field => $rules) {
            $this->_validation->rules($field, $rules);
        }
    }

    /**
     * Returns the validation object for this request.
     * 
     * @return Validation
     */
    public function validation() {
        return $this->_validation;
    }

    /**
     * Returns the environment for this request. (ex. sandbox, live).
     * 
     * @return string
     */
    public function environment() {
        return $this->_environment;
    }

    /**
     * Config access method through Arr::path
     * 
     * @see Arr::path    
     */
    public function config($path, $default = NULL, $delimiter = NULL) {
        return Arr::path($this->_config, $path, $default, $delimiter);
    }

    /**
     * Alias the post() or query() depending on the method (POST or GET) used.
     * 
     * @param type $key
     * @param type $value
     * @param array $expected
     */
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

        $this->_data[$key] = $value;

        return $this;
    }

    /**
     * Alias the post() or query() depending on the method (POST or GET) used.
     * 
     * @deprecated use data
     * 
     * @param type $key
     * @param type $value
     * @param array $expected
     */
    public function param($key = NULL, $value = NULL) {
        return $this->data($key, $value);
    }

    /**
     * Pass values to the request. Expected values are keys that filters which
     * values are going to be added to the request.
     * 
     * @param array $values are key-value pairs being passed to the request 
     * param() method.
     * @param array $expected is an array of key to filter values passing.
     */
    public function values(array $values, array $expected = NULL) {

        if ($expected === NULL) {
            $expected = array_keys($values);
        }

        foreach ($expected as $field) {

            if (!array_key_exists($field, $values))
                continue;

            $this->_data[$field] = $values[$field];
        }

        return $this;
    }

    public function bind($key, $value = NULL) {
        return $this->_validation->bind($key, $value);
    }

    /**
     * Applies filters on param.
     */
    public function filter_apply() {

        foreach ($this->_filters as $field => $filters) {

            $keys = preg_grep("/^$field$/", array_keys($this->_data));

            foreach ($keys as $key) {

                $value = $this->_data[$key];

                // Apply each filters
                foreach ($filters as $filter) {

                    $params = Arr::get($filter, 1, array(':value'));

                    $variables = array(
                        ':field' => $key,
                        ':value' => $value
                    );

                    foreach ($params as &$param) {
                        $param = __($param, $variables);
                    }

                    $value = call_user_func($filter[0], $params);
                }

                $this->data[$key] = $value;
            }
        }
    }

    public function filter($field, $filter, $param = NULL) {
        $this->_filters[$field][] = array($filter, $param);
        return $this;
    }

    public function label($field, $label) {
        return $this->_validation->label($field, $label);
    }

    public function rule($field, $rule, array $param = NULL) {
        return $this->_validation->rule($field, $rule, $param);
    }

    public function errors($file, array $param = NULL) {
        return $this->_validation->error($file, $param);
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
    protected function rules() {
        return array();
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

        // Apply filters
        $this->filter_apply();

        // Update the validation
        $this->_validation = $this->_validation->copy($this->_data);

        if (!$this->_validation->check()) {
            throw new PayPal_Validation_Exception($this->_validation, $this, NULL, 'Paypal request failed to validate.');
        }

        return $this;
    }

    /**
     * Returns the API URL for the current environment and method.
     * 
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

        // Fetch specific parameters
        $params = $this->redirect_params($response_data);

        // Add the cmd 
        $params['cmd'] = '_' . $this->_redirect_command;

        return URL::site("www.{$env}paypal.com/cgi-bin/webscr", 'https') . URL::query($params);
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

    /**
     * Overriden for auto-completion.
     * 
     * @return Response_PayPal
     */
    public function execute() {
        return parent::execute();
    }

}

?>
