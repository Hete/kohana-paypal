<?php

/**
 * Class for Pay request.
 * 
 * @see https://www.x.com/developers/paypal/documentation-tools/api/pay-api-operation
 * 
 * @package PayPal
 * @author Hète.ca Team
 * @copyright (c) 2012, Hète.ca
 */
class Kohana_PayPal_AdaptivePayments_Pay extends PayPal_AdaptivePayments {

    const PAY = "PAY", CREATE = "CREATE", PAY_PRIMARY = "PAY_PRIMARY";

    /**
     * Possible values for paymentExecStatus in response.
     */
    /**
     * The payment request was received; funds will be transferred once the payment is approved
     */
    const CREATED = "CREATED",
            /**
             * The payment was successful
             */
            COMPLETED = "COMPLETED",
            /**
             * Some transfers succeeded and some failed for a parallel payment or, for a delayed chained payment, secondary receivers have not been paid
             */
            INCOMPLETE = "INCOMPLETE",
            /**
             * The payment failed and all attempted transfers failed or all completed transfers were successfully reversed
             */
            ERROR = "ERROR",
            /**
             * One or more transfers failed when attempting to reverse a payment
             */
            REVERSALERROR = "REVERSALERROR",
            /**
             * The payment is in progress
             */
            PROCESSING = "PROCESSING",
            /**
             * The payment is awaiting processing
             */
            PENDING = "PENDING";

    public static $ACTION_TYPE = array(
        'PAY',
        'CREATE',
        'PAY_PRIMARY'
    );
    public static $PAYMENT_EXEC_STATUS = array(
        "CREATED",
        "COMPLETED",
        "INCOMPLETE",
        "ERROR",
        "REVERSALERROR",
        "PROCESSING",
        "PENDING",
    );
    protected $_redirect_command = 'ap-payment';

    protected function redirect_params(Response_PayPal $results) {
        return array("paykey" => $results['payKey']);
    }

    protected function rules() {
        return array(
            'actionType' => array(
                array('not_empty'),
                array('PayPal_Valid::contained', array(':value', static::$ACTION_TYPE)),
            ),
            'currencyCode' => array(
                array('not_empty'),
                array('PayPal_Valid::contained', array(':value', static::$CURRENCIES)),
            ),
            'cancelUrl' => array(
                array('not_empty'),
                array('url'),
            ),
            'returnUrl' => array(
                array('not_empty'),
                array('url'),
            ),
            'receiverList.receiver(0).email' => array(
                array('not_empty'),
                array('email'),
            ),
            'receiverList.receiver(0).amount' => array(
                array('not_empty'),
                array('numeric'),
            ),
        );
    }

}

?>
