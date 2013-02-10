<?php

/**
 * 
 * @see https://www.x.com/developers/paypal/documentation-tools/ipn/integration-guide/IPNIntro
 * 
 * @package PayPal
 * @subpackage IPN
 * @author Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 */
class Kohana_PayPal_IPN_NotifyValidate extends PayPal_IPN {

    protected $_redirect_command = "notify-validate";

    protected function rules() {

        return array(
            "cmd" => array(
                array("not_empty")
            ),
            "receiver_email" => array(
                array("not_empty"),
                array("equals", array(":value", $this->config("ipn.receiver.email"))),
            ),
            "receiver_id" => array(
                array("not_empty"),
                array("equals", array(":value", $this->config("ipn.receiver.id")))
            ),
            "residence_country" => array(
                array("not_empty"),
                array("equals", array(":value", $this->config("ipn.receiver.country")))
            ),
            "test_ipn" => array(
                array("not_empty"),
                array("equals", array(":value", (int) ($this->environment() === PayPal::SANDBOX)))
            ),
            "txn_type" => array(
                array("not_empty")
            )
        );
    }

}

?>
