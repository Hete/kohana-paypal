<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @link https://www.x.com/developers/paypal/documentation-tools/api/setpaymentoptions-api-operation
 * 
 * @package PayPal
 * @category AdaptivePayments
 * @author Guillaume Poirier-Morency
 * @copyright 2012 (c), Hète.ca Inc.
 */
class Kohana_PayPal_AdaptivePayments_SetPaymentOptions extends PayPal_AdaptivePayments {

    const PAY = 'PAY',
            CREATE = 'CREATE',
            PAY_PRIMARY = 'PAY_PRIMARY';

    public static $ACTION_TYPE = array(
        'PAY',
        'CREATE',
        'PAY_PRIMARY'
    );

    /**
     * @todo finish rules here 
     */
    protected function rules() {
        return array(
            'payKey' => array(
                array('not_empty'),
            ),
            'shippingAddressId' => array(
            ),
            'cancelUrl' => array(
                array('not_empty'),
                array('url'),
            ),
            'returnUrl' => array(
                array('not_empty'),
                array('url'),
            ),
        );
    }

}

?>
