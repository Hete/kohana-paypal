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

    protected function rules() {
        return array(
            'payKey' => array(
                array('not_empty'),
            ),
        );
    }

}

?>
