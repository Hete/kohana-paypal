<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * DoExpressCheckoutPayment
 * 
 * @link https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoExpressCheckoutPayment_API_Operation_NVP/
 * 
 * @package PayPal
 * @author  Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @license http://kohanaframework.org/license
 */
class Kohana_PayPal_DoExpressCheckoutPayment extends Request_PayPal {
    /**
     * Reason the payment is pending.
     */

    const PENDING_REASON_NONE = 'none',
            PENDING_REASON_ADDRESS = 'address',
            PENDING_REASON_AUTHORIZATION = 'authorization',
            PENDING_REASON_ECHECK = 'echeck',
            PENDING_REASON_INTL = 'intl',
            PENDING_REASON_MULTI_CURRENCY = 'multi-currency',
            PENDING_REASON_ORDER = 'order',
            PENDING_REASON_PAYMENT_REVIEW = 'paymentreview',
            PENDING_REASON_REGULATORY_REVIEW = 'regulatoryreview',
            PENDING_REASON_UNILATERAL = 'unilateral',
            PENDING_REASON_VERIFY = 'verify',
            PENDING_REASON_OTHER = 'other';


    /**
     * The status of the payment.
     */
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

    /**
     * Indicates whether the payment is instant or delayed.
     */
    const PAYMENT_TYPE_NONE = 'none',
            PAYMENT_TYPE_ECHECK = 'echeck',
            PAYMENT_TYPE_INSTANT = 'instant';

    /**
     * Type of transaction.
     */
    const TRANSACTION_TYPE_CART = 'cart',
            TRANSACTION_TYPE_EXPRESS_CHECKOUT = 'express-checkout';

    public function filters() {
        return array(
            'PAYMENTREQUEST_0_ITEMAMT' => array(
                array('PayPal::number_format')
            ),
            'PAYMENTREQUEST_0_SHIPPINGAMT' => array(
                array('PayPal::number_format')
            ),
            'PAYMENTREQUEST_0_INSURANCEAMT' => array(
                array('PayPal::number_format')
            ),
            'PAYMENTREQUEST_0_SHIPDISCAMT' => array(
                array('PayPal::number_format')
            ),
            'PAYMENTREQUEST_0_HANDLINGAMT' => array(
                array('PayPal::number_format')
            ),
            'PAYMENTREQUEST_0_TAXAMT' => array(
                array('PayPal::number_format')
            ),
            'PAYMENTREQUEST_0_AMT' => array(
                array('PayPal::number_format')
            )
        );
    }

}

?>
