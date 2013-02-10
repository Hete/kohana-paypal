<?php

defined('SYSPATH') or die('No direct script access.');

/**
 *
 * 
 * @package PayPal
 * @subpackage Permissions
 * @author Hète.ca Team
 * @copyright  (c) 2013, Hète.ca Inc.
 */
class Kohana_PayPal_Permissions_GetPermissions extends PayPal_Permissions {

    protected function rules() {
        return array(
            'token' => array(
                array('not_empty')
            )
        );
    }

}

