<?php

/**
 * 
 */
abstract class Kohana_PayPal_Request extends Request {

    /**
     * Factory class for PayPal requests.
     * @param string $class is the class' name without the PayPal_
     * @param array $params are the initial parameters.
     * @return \class
     */
    public static function factory($class, array $params = array()) {
        $class = "PayPal_Request_" . $class;
        return new $class($params);
    }

    /**
     *
     * @var Validation 
     */
    protected $_validation;

    /**
     * Redirection command (if appliable).
     * @var string 
     */
    protected $_redirect_command = "";

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
     * 
     * @param array $params
     * @param HTTP_Cache $cache
     * @param type $injected_routes
     */
    public function __construct(array $params = array(), HTTP_Cache $cache = NULL, $injected_routes = NULL) {

        // Loading current environment
        $this->_environment = Kohana::$config->load("paypal.environment");

        // Config for current environment
        $this->_config = Kohana::$config->load('paypal.' . $this->_environment);

        parent::__construct($this->api_url(), $cache, $injected_routes);

        if (!$this->client() instanceof Kohana_Request_Client_Curl) {
            throw new Kohana_Exception("Client must be Curl.");
        }

        // Custom setup for the cURL client
        foreach (Kohana::$config->load("paypal.curl_options") as $key => $value) {
            $this->client()->options($key, $value);
        }

        // Setting default headers
        $this->headers('X-PAYPAL-SECURITY-USERID', $this->_config['username']);
        $this->headers('X-PAYPAL-SECURITY-USERID', $this->_config['password']);
        $this->headers('X-PAYPAL-SECURITY-USERID', $this->_config['signature']);
        $this->headers('X-PAYPAL-REQUEST-DATA-FORMA', 'NV');
        $this->headers('X-PAYPAL-RESPONSE-DATA-FORMAT', 'NV');
        $this->headers("X-PAYPAL-APPLICATION-ID", $this->_config['api_id']);

        // Setting default post
        $this->post('requestEnvelope', '');
        $this->post('requestEnvelope_detailLevel', 'ReturnAll');
        $this->post('requestEnvelope_errorLanguage', Kohana::$config->load("paypal.lang"));

        // Basic post values
        foreach ($params as $key => $value) {
            $this->post($key, $value);
        }
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

    protected abstract function rules();

    /**
     * Validates the request.
     * @param string $security_token You may set a custom security token to
     * make sure the request is handled by the same client.
     * @return \PayPal_Request for builder syntax.
     * @throws PayPal_Validation_Exception if the request is invalid
     */
    public function check($security_token = NULL) {

        if ($security_token === NULL) {
            $security_token = Security::token();
        }

        // Validate the request parameters
        $this->_validation = Validation::factory($this->post())
                ->rule('requestEnvelope.errorLanguage', 'not_empty')
                ->rule('securityToken', 'Security::check', array($security_token));

        // We add custom and basic rules proper to the request
        foreach ($this->rules() as $field => $rules) {
            $this->_validation->rules($field, $rules);
        }

        if (!$this->_validation->check()) {
            throw new PayPal_Exception($this, NULL, "Paypal request failed to validate :errors", array(":errors" => print_r($validation_request->errors(), TRUE)));
        }

        return $this;
    }

    /**
     * PayPal method name based on the class name.   
     * @return string 
     */
    public function method() {

        // Remove prefix to the class
        $method = preg_replace("(Kohana_)?PayPal_Request", "", get_class($this));

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

    public function execute($security_token = NULL) {

        if (Kohana::$profiling) {
            $benchmark = Profiler::start("PayPal", __FUNCTION__);
        }

        $this->check($security_token);

        // Execute the request
        try {
            $response = parent::execute();
        } catch (Request_Exception $re) {
            throw PayPal_Exception($this, NULL, $re->getMessage(), NULL, $re->getCode());
        }

        // Adding the redirect url to the datas
        $response['redirectUrl'] = $this->redirect_url($data);

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

?>
