<?php

defined('SYSPATH') or die('No direct script access.');


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
interface Kohana_PayPal_Constants {
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
    /**
     * Acknowledgement for success request containing a warning.
     */
    const SUCCESS_WITH_WARNING = "SuccessWithWarning";

}

?>
