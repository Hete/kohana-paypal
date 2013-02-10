<?php

/**
 * @package PayPal
 * @subpackage IPN
 * @author Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 */
class Kohana_PayPal_IPN_NotifyValidate extends PayPal_IPN {

    protected $_redirect_command = "notify-validate";

    protected function rules() {
        return array();
    }

}

?>
