<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Base class for AdaptativePayments api.
 *
 * @link  https://cms.paypal.com/ca/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_PermissionsAbout
 *
 * @package PayPal
 * @category AdaptativePayments
 * @author     Guillaume Poirier-Morency
 * @copyright  Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
 */
abstract class Kohana_PayPal_AdaptivePayments extends Request_PayPal_SVCS {
    // Preapproval status

    const ACTIVE = 'ACTIVE', CANCELED = 'CANCELED', DEACTIVED = 'DEACTIVED';

    public static $PREAPPROVAL_STATES = array(
        'ACTIVE',
        'DEACTIVED',
        'CANCELED'
    );
    public static $FEES_PAYER = array(
        'SENDER',
        'PRIMARYRECEIVER',
        'EACHRECEIVER',
        'SECONDARYONLY'
    );

    // Action types

    const PAY = "PAY", CREATE = "CREATE", PAY_PRIMARY = "PAY_PRIMARY";

    /**
     * Action types.
     * @var array 
     */
    public static $ACTION_TYPES = array(
        "PAY", "CREATE", "PAY_PRIMARY"
    );

    /**
     * Supported payment periods.
     * @var array 
     */
    public static $PAYMENT_PERIODS = array(
        'NO_PERIOD_SPECIFIED',
        'DAILY',
        'WEEKLY',
        'BIWEEKLY',
        'SEMIMONTHLY',
        'MONTHLY',
        'ANNUALLY',
    );

}

?>
