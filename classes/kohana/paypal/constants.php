<?php

/**
 * 
 * @link  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/library_documentation
 *
 * @package PayPal
 * @category ExpressCheckout
 * @author     Guillaume Poirier-Morency
 * @copyright  Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
 */
class Kohana_PayPal_Constants {
    // Booleans

    const TRUE = 'true', FALSE = 'false';
    // Preapproval status
    const ACTIVE = 'ACTIVE', CANCELED = 'CANCELED', DEACTIVED = 'DEACTIVED';

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
    public static $PERSONAL_IDENTIFICATION_NUMBER = array(
        'NOT_REQUIRED',
        'REQUIRED'
    );
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
    public static $PAYMENT_PERIODS = array(
        'NO_PERIOD_SPECIFIED',
        'DAILY',
        'WEEKLY',
        'BIWEEKLY',
        'SEMIMONTHLY',
        'MONTHLY',
        'ANNUALLY',
    );
    public static $REQUIRED_STATES = array(
        'REQUIRED', 'NOT_REQUIRED'
    );
    public static $FEES_PAYER = array(
        'SENDER',
        'PRIMARYRECEIVER',
        'EACHRECEIVER',
        'SECONDARYONLY'
    );

}

?>