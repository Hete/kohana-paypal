<?php

/**
 * @package PayPal
 * @subpackage IPN
 */
class Kohana_PayPal_IPN_NotifyValidate extends PayPal_IPN {

    protected $_redirect_command = "notify-validate";

    protected function rules() {
        return array();
    }

}

?>
