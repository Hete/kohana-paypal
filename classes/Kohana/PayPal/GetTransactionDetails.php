<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * GetTransactionDetails
 * 
 * @link https://developer.paypal.com/docs/classic/api/merchant/GetTransactionDetails_API_Operation_NVP/    
 * @package PayPal
 * @author  Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @license http://kohanaframework.org/license
 */
class Kohana_PayPal_GetTransactionDetails extends PayPal {

    const PAYMENT_STATUS_NONE = 'None',
            PAYMENT_STATUS_CANCELED_REVERSAL = 'Canceled-Reversal',
            PAYMENT_STATUS_COMPLETED = 'Completed',
            PAYMENT_STATUS_DENIED = 'Denied',
            PAYMENT_STATUS_EXPIRED = 'Expired',
            PAYMENT_STATUS_FAILED = 'Failed',
            PAYMENT_STATUS_IN_PROGRESS = 'In-Progress',
            PAYMENT_STATUS_PARTIALLY_REFUNDED = 'Partially-Refunded',
            PAYMENT_STATUS_PENDING = 'Pending',
            PAYMENT_STATUS_REFUNDED = 'Refunded',
            PAYMENT_STATUS_REVERSED = 'Reversed',
            PAYMENT_STATUS_PROCESSED = 'Processed',
            PAYMENT_STATUS_VOIDED = 'Voided',
            PAYMENT_STATUS_COMPLETED_FUNDS_HELD = 'Completed-Funds-Held';

    public static function get_request_validation(Request $request) {
        return parent::get_request_validation($request)
            ->rule('not_empty', 'TRANSACTIONID');
    }
}
