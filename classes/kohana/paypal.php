<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * PayPal main class. Contains constants and a factory method for requests.
 * 
 * Requests never inherit from this class, but rather from the Request_PayPal
 * class which provides basic Kohana request features such as post and headers
 * handling.
 * 
 * @package PayPal
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright (c) 2012, Hète.ca Inc.
 */
class Kohana_PayPal {

    /**
     *
     * @var type PayPal environment.
     * 
     * Can be set to sandbox, live or sandbox-beta. You should preferably use
     * predefined constants.
     */
    public static $environment = 'sandbox';

    /**
     * Environment types.
     */

    const SANDBOX = 'sandbox', LIVE = 'live', SANDBOX_BETA = 'sandbox-beta';

    /**
     * API version.
     */
    public static $API_VERSION = '98.0';

    const TRUE = 'true', FALSE = 'false';

    const NONE = 'None';

    /**
     * Acknowledgements
     */
    const SUCCESS =
    'Success',
            SUCCESS_WITH_WARNING = 'SuccessWithWarning',
            FAILURE = 'Failure',
            FAILURE_WITH_WARNING = 'FailureWithWarning';


    /**
     * Short date format supported by PayPal.
     */
    const SHORT_DATE_FORMAT = 'Y-m-d\T',
            DATE_FORMAT = 'Y-m-d\TH:i:s.BP';

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
     * Supported currencies.
     * 
     * @var array
     */
    public static $CURRENCY_CODES = array('AUD', 'BRL', 'CAD', 'CZK', 'DKK',
        'EUR', 'HKD', 'HUF', 'ILS', 'JPY', 'MYR', 'MXN', 'NOK', 'NZD', 'PHP',
        'PLN', 'GBP', 'SGD', 'SEK', 'CHF', 'TWD', 'THB', 'USD');

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
     * Factory method for Request_PayPal object. It is implemented here because
     * Request forces undesired parameters.
     * 
     * @see Request
     * 
     * @param string $name
     * @param array $params
     * @param HTTP_Cache $cache
     * @param type $injected_routes
     * @return Request_PayPal
     */
    public static function factory($name, array $params = array(), array $expected = NULL, HTTP_Cache $cache = NULL, $injected_routes = array()) {

        $class = "PayPal_$name";

        return new $class(TRUE, $cache, $injected_routes, $params, $expected);
    }

    /**
     * Format numbers specifically for PayPal API.
     * 
     * @param number $number
     * @return number
     */
    public static function number_format($number) {
        return number_format($number, 2, ".", "");
    }

    /**
     * Format dates specificaly for PayPal API.
     * 
     * @param string $date
     * @return string
     */
    public static function date_format($date) {
        return Date::format($date, static::DATE_FORMAT);
    }

}

?>
