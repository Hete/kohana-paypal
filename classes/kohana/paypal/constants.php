<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * General PayPal constants are defined here.
 * 
 * @link  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/library_documentation
 *
 * @package PayPal
 * @category ExpressCheckout
 * @author     Guillaume Poirier-Morency
 * @copyright  Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
 */
interface Kohana_PayPal_Constants {
    /**
     * Environment types.
     */

    const SANDBOX = 'sandbox', LIVE = '';

    /**
     * Request client class
     */
    const REQUEST_CLIENT = "Request_Client_Curl";

    /**
     * Current version.
     */
    const VERSION = '2.1.0';
    // Booleans
    const TRUE = 'TRUE', FALSE = 'FALSE';

    /**
     * Acknowledgements
     */
    const SUCCESS = "Success",
            FAILURE = "Failure",
            SUCCESS_WITH_WARNING = "SuccessWithWarning",
            FAILURE_WITH_WARNING = "FailureWithWarning";    

    // Required states
    const REQUIRED = "REQUIRED", NOT_REQUIRED = "NOT_REQUIRED";

    /**
     * Short date format supported by PayPal.
     */
    const SHORT_DATE_FORMAT = "Y-m-d\T";

    /**
     * Supported date format by PayPal.
     */
    const DATE_FORMAT = "Y-m-d\TH:i:s.BP";

}

?>
