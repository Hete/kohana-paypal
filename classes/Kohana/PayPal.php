<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * PayPal base class.
 *
 * Basic usage
 *
 *     $setexpresscheckout = PayPal::factory('SetExpressCheckout');
 *
 *     $response = $setexpresscheckout
 *         ->request()
 *         ->query('key', 'value')
 *         ->execute();
 *
 * Validate your response.
 *
 *     $validation = PayPal_SetExpressCheckout::get_response_validation($response);
 *
 *     if ($validation->check()) {
 *         // Do some stuff
 *     }
 *
 * Fetch data
 *
 *     $data = PayPal::parse_response($response);
 *
 * Redirect the user if it is appliable.
 *
 *     PayPal_SetExpressCheckout->redirect($response);
 *
 * @link https://developer.paypal.com/docs/classic/
 * 
 * @package   PayPal
 * @author    Hète.ca Team
 * @copyright (c) 2014, Hète.ca Inc.
 * @license   BSD-3-Clause
 */
abstract class Kohana_PayPal {

    /**
     * PayPal environment.
     */
    public static $environment = 'sandbox';

    /**
     * Environment types.
     */
    const SANDBOX = 'sandbox', LIVE = 'live', SANDBOX_BETA = 'sandbox-beta';

    /**
     * None.
     */
    const NONE = 'None';

    /**
     * Acknowledgements
     */
    const SUCCESS = 'Success',
            SUCCESS_WITH_WARNING = 'SuccessWithWarning',
            FAILURE = 'Failure',
            FAILURE_WITH_WARNING = 'FailureWithWarning';

    /**
     * Short date format.
     */
    const SHORT_DATE_FORMAT = 'Y-m-d\T';

    /**
     * Long date format.
     */
    const DATE_FORMAT = 'Y-m-d\TH:i:s.BP';

    /**
     * Supported countries.
     *
     * @var array
     */
    public static $COUNTRIES = array(
        'AL', 'DZ', 'AS', 'AD', 'AI', 'AG', 'AR', 'AM', 'AW', 'AU', 'AT', 'AZ',
        'BS', 'BH', 'BD', 'BB', 'BY', 'BE', 'BZ', 'BJ', 'BM', 'BO', 'BA', 'BW',
        'BR', 'VG', 'BN', 'BG', 'BF', 'KH', 'CM', 'CA', 'CV', 'KY', 'CL', 'CN',
        'CO', 'CK', 'HR', 'CY', 'CZ', 'DK', 'DJ', 'DM', 'DO', 'TP', 'EG', 'SV',
        'EE', 'FJ', 'FI', 'FR', 'GF', 'PF', 'GA', 'GE', 'DE', 'GH', 'GI', 'GR',
        'GD', 'GP', 'GU', 'GT', 'GN', 'GY', 'HT', 'HN', 'HK', 'HU', 'IS', 'IN',
        'ID', 'IE', 'IL', 'IT', 'CI', 'JM', 'JP', 'JO', 'KZ', 'KE', 'KW', 'LA',
        'LV', 'LB', 'LS', 'LT', 'LU', 'MO', 'MK', 'MG', 'MY', 'MV', 'ML', 'MT',
        'MH', 'MQ', 'MU', 'MX', 'FM', 'MD', 'MN', 'MS', 'MA', 'MZ', 'NA', 'NP',
        'NL', 'AN', 'NZ', 'NI', 'MP', 'NO', 'OM', 'PK', 'PW', 'PS', 'PA', 'PG',
        'PY', 'PE', 'PH', 'PL', 'PT', 'PR', 'QA', 'RO', 'RU', 'RW', 'KN', 'LC',
        'VC', 'WS', 'SA', 'CS', 'SC', 'SG', 'SK', 'SI', 'SB', 'ZA', 'KR', 'ES',
        'LK', 'SZ', 'SE', 'CH', 'TW', 'TZ', 'TH', 'TG', 'TO', 'TT', 'TN', 'TR',
        'TM', 'TC', 'UG', 'UA', 'AE', 'GB', 'US', 'UY', 'UZ', 'VU', 'VE', 'VN',
        'VI', 'YE', 'ZM'
    );

    /**
     * Supported currencies.
     * 
     * @var array
     */
    public static $CURRENCY_CODES = array(
        'AUD', 'BRL', 'CAD', 'CZK', 'DKK',
        'EUR', 'HKD', 'HUF', 'ILS', 'JPY', 'MYR', 'MXN', 'NOK', 'NZD', 'PHP',
        'PLN', 'GBP', 'SGD', 'SEK', 'CHF', 'TWD', 'THB', 'USD'
    );

    /**
     * Supported days of week.
     * 
     * @var array
     */
    public static $DAYS_OF_WEEK = array(
        'NO_DAY_SPECIFIED', 'SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY',
        'THURSDAY', 'FRIDAY', 'SATURDAY'
    );

    /**
     * Supported months of year.
     * 
     * @var array
     */
    public static $MONTHS_OF_YEAR = array(
        'NO_MONTH_SPECIFIED', 'JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY',
        'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'
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
     * Defines all the possible acknowledgements values.
     *
     * @var array
     */
    public static $ACKNOWLEDGEMENTS = array(
        'Success',
        'PartialSuccess',
        'Failure',
        'SuccessWithWarning',
        'FailureWithWarning'
    );

    /**
     * Defines all the success acknowledgements
     *
     * @var array
     */
    public static $SUCCESS_ACKNOWLEDGEMENTS = array(
        'Success',
        'SuccessWithWarning',
        'PartialSuccess'
    );

    /**
     * Defines all the failure acknowledgements.
     *
     * @var array
     */
    public static $FAILURE_ACKNOWLEDGEMENTS = array(
        'Failure',
        'FailureWithWarning'
    );

    /**
     * Factory a PayPal request.
     * 
     * @uses Request::factory
     *
     * @param string $method        a PayPal method such as SetExpressCheckout.
     * @param array  $client_params see Request::factory $client_params.
     * @return Request
     */
    public static function factory($method, $client_params = array()) {

        $config = Kohana::$config->load('paypal.' . PayPal::$environment);

        $api = $config['signature'] ? 'api-3t' : 'api';

        $url = "https://$api." . PayPal::$environment . '.paypal.com/nvp';

        if (PayPal::$environment === PayPal::LIVE) {

            $url = "https://$api.paypal.com/nvp";
        }

        /**
         * Construct a basic Request.
         */
        return Request::factory($url, $client_params)
                        ->headers('Connection', 'close')
                        ->query(array(
                            'METHOD' => $method,
                            'USER' => $config['username'],
                            'PWD' => $config['password'],
                            'SIGNATURE' => $config['signature'],
                            'VERSION' => $config['api_version']
        ));
    }

    /**
     * Creates a Validation object for a PayPal Request.
     * 
     * @param Request $request
     * @return Validation
     */
    public static function get_request_validation(Request $request) {

        return Validation::factory($request->query())
                        ->rule('USER', 'not_empty')
                        ->rule('PWD', 'not_empty')
                        ->rule('VERSION', 'not_empty')
                        ->rule('METHOD', 'equals', array(':value', str_replace('PayPal_', '', get_called_class())));
    }

    /**
     * Creates a Validation object for a PayPal Response.
     *
     * Fields are kept flattened since Validation is one-dimensional.
     * 
     * @param Response $response
     * @return Validation
     */
    public static function get_response_validation(Response $response) {

        return Validation::factory(PayPal::parse_response($response, FALSE))
                        ->rule('ACK', 'not_empty')
                        ->rule('ACK', 'in_array', array(':value', PayPal::$SUCCESS_ACKNOWLEDGEMENTS));
    }

    /**
     * Parse a PayPal Response body.
     * 
     * The result will be automatically expanded unless you set the $expand
     * argument to false.
     *
     * @param Response $response 
     * @param boolean  $expand   expand PayPal array and dictionary syntax.
     * @return array the parsed body of the Response object.
     */
    public static function parse_response(Response $response, $expand = TRUE) {

        $data = NULL;

        parse_str($response->body(), $data);

        if ($data === NULL) {

            throw new Kohana_Exception('Couldn\'t parse Response body. :body', array(':body' => $response->body()));
        }

        return $expand ? PayPal::expand($data) : $data;
    }

    /**
     * Expand a flattened PayPal array into a multi-dimensional structure.
     *
     * It will parse
     *
     *     KEY1_n_KEY2 => VALUE
     *
     * Into
     *
     *     KEY1 => array(n => array(KEY2 => VALUE)
     *
     * And also
     *
     *     KEY1.KEY2 => VALUE
     *
     * Into
     *
     *     KEY1 => array(KEY2 => VALUE)
     * 
     * @param array $array PayPal array to expand.
     * @return array expanded array.
     */
    public static function expand(array $array) {

        $expanded = array();

        foreach ($array as $key => $value) {

            Arr::set_path($expanded, preg_replace('/\_/', '.', $key), $value);
        }

        return $expanded;
    }

    /**
     * Format numbers specifically for PayPal API.
     * 
     * 2 decimal places, period for the decimal point and comma for the 
     * optional thousands separator.
     *
     * @param number $number
     * @return string
     */
    public static function number_format($number) {

        return number_format($number, 2, '.', ',');
    }

    /**
     * Call HTTP::redirect to the PayPal::redirect_url() along with the
     * redirection query generated from the $response.
     *
     * @param Response $response
     */
    public static function redirect(Response $response) {

        HTTP::redirect(static::redirect_url() . URL::query(static::redirect_query($response)));
    }

    /**
     * Redirection url.
     *
     * @return string
     */
    public static function redirect_url() {

        $environment = PayPal::$environment;

        if ($environment === PayPal::LIVE) {

            return 'https://www.paypal.com/cgi-bin/webscr';
        }

        return "https://www.$environment.paypal.com/cgi-bin/webscr";
    }

    /**
     * Generates a redirect query.
     *
     * @param Response $response
     * @return array
     */
    public static function redirect_query(Response $response) {

        throw new Kohana_Exception('This PayPal method does not implement redirection.');
    }

}
