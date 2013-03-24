<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @see https://www.x.com/developers/paypal/documentation-tools/api/docapture-api-operation-nvp
 * 
 * @package PayPal
 * @subpackage PaymentsPro
 * @author Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 */
class Kohana_PayPal_PaymentsPro_DoCapture extends PayPal_PaymentsPro {

    const COMPLETE = "Complete", NOT_COMPLETE = "NotComplete";

    public static $COMPLETE_TYPES = array(
        "Complete", "NotComplete"
    );

    public function rules() {
        return array(
            "AUTHORIZATIONID" => array(
                array("not_empty")
            ),
            "COMPLETETYPE" => array(
                array("not_empty"),
                array("PayPal_Valid::contained", array(":value", static::$COMPLETE_TYPES))
            ),
            "AMT" => array(
                array("not_empty"),
                array("PayPal_Valid::numeric")
            )
        );
    }

}

?>
