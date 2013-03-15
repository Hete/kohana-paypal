<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * General PayPal constants are defined here.
 *
 * @package PayPal
 * @author Guillaume Poirier-Morency
 * @copyright Hète.ca Inc.
 */
interface Kohana_PayPal_Constants {    
    
    /**
     * Environment types.
     */
    const SANDBOX = 'sandbox', LIVE = 'live', SANDBOX_BETA = 'sandbox-beta';

    /**
     * Request client class
     */
    const REQUEST_CLIENT = "Request_Client_Curl";

    /**
     * Current version.
     */
    const VERSION = '2.1.0';

    /**
     * Booleans
     * 
     * Through the PayPal api, booleans are not consistent. Always use the
     * boolean constant from the class of the request you are specifically
     * using. For example, if you are using Pay api, obtaine the TRUE value
     * from PayPal_AdaptivePayments_Pay::TRUE.
     */
    const TRUE = 'true', FALSE = 'false';

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
     * Supported date format by PayPal. It is always a good thing to use this 
     * date format.
     */
    const DATE_FORMAT = "Y-m-d\TH:i:s.BP";

}

?>
