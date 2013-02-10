<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @link https://cms.paypal.com/ca/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_APExecutePaymentAPI
 * 
 * @package PayPal
 * @subpackage AdaptivePayments
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com> 
 * @copyright Hète.ca Inc.
 */
class Kohana_PayPal_AdaptivePayments_ExecutePayment extends PayPal_AdaptivePayments {

    protected function rules() {
        return array(
            'payKey' => array(
                array('not_empty')
            ),
            'actionType' => array(
                array('not_empty'),
                array('PayPal_Valid::contained', array(':value', static::$ACTION_TYPES)),
            ),
        );
    }

}

?>
