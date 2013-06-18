<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * DoAuthorization
 * 
 * @link https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoAuthorization_API_Operation_NVP/
 * 
 * @package   PayPal
 * @author    Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 * @license   http://kohanaframework.org/license
 */
class Kohana_PayPal_DoAuthorization extends Request_PayPal {

    public function filters() {
        return array(
            'AMT' => array(
                array('PayPal::number_format')
            )
        );
    }

    public function rules() {
        return array(
            'TRANSACTIONID' => array(
                array('not_empty')
            ),
            'AMT' => array(
                array('not_empty'),
                array('numeric')
            ),
        );
    }

}

?>
