<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Base class for Payments Pro api.
 * 
 * @package PayPal
 * @subpackage PaymentsPro
 * @author Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 */
abstract class Kohana_PayPal_PaymentsPro extends Request_PayPal_NVP {

    const AUTHORIZATION = "Authorization", SALE = "Sale";

    /**
     * 
     */
    const PAYMENT_ACTION_NOT_INITIATED = "PaymentActionNotInitiated",
            PAYMENT_ACTION_FAILED = "PaymentActionFailed",
            PAYMENT_ACTION_IN_PROGRESS = "PaymentActionInProgress",
            PAYMENT_ACTION_COMPLETED = "PaymentActionCompleted";

    /**
     * Possible status for PAYMENTSTATUS
     */
    const NONE = "None",
            CANCELED_REVERSAL = "Canceled-Reversal",
            COMPLETED = "Completed",
            DENIED = "Denied",
            EXPIRED = "Expired",
            FAILED = "Failed",
            IN_PROGRESS = "In-Progress",
            PARTIALLY_REFUNDED = "Partially-Refunded",
            PENDING = "Pending",
            REFUNDED = "Refunded",
            REVERSED = "Reversed",
            PROCESSED = "Processed",
            VOIDED = "Voided";

    /**
     * Available payment actions.
     * 
     * @var array 
     */
    public static $PAYMENT_ACTIONS = array(
        "Authorization",
        "Sale"
    );

    /**
     * Available payment action status.
     * 
     * @var array
     */
    public static $PAYMENT_ACTION_STATUS = array(
        "PaymentActionNotInitiated",
        "PaymentActionFailed",
        "PaymentActionInProgress",
        "PaymentActionCompleted"
    );

}

?>
