<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * RequestPermissions API Operation.
 *
 * @link  https://cms.paypal.com/ca/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_PermissionsRequestPermissionsAPI
 *
 * @package PayPal
 * @subpackage Permissions
 * @author Hète.ca Inc.
 * @copyright Hète.ca Inc.
 * @license http://kohanaphp.com/license.html
 */
class Kohana_PayPal_Permissions_RequestPermissions extends PayPal_Permissions {

    protected $_redirect_command = 'grant-permission';

    protected function redirect_param(array $results) {
        return array('request_token' => $results['token']);
    }

    protected function rules() {
        return array(
            'callback' => array(
                array('not_empty', array(':value'))
            )
        );
    }

}