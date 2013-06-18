<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Callback
 * 
 * @link https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/Callback_API_Operation_NVP/
 * 
 * @package    PayPal
 * @subpackage PaymentsPro
 * @author     Hète.ca Team
 * @copyright  (c) 2013, Hète.ca Inc.
 * @license    http://kohanaframework.org/license
 */
class Kohana_PayPal_Callback extends Request_PayPal {

    public function rules() {
        return array(
            'CURRENCYCODE' => array(
                array('not_empty'),
                array('in_array', PayPal::$CURRENCY_CODES)
            )
        );
    }

}

?>
