<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Base class for Payments Pro api.
 * 
 * @package PayPal
 * @category PaymentsPro
 * @author Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 */
abstract class Kohana_PayPal_PaymentsPro extends Request_PayPal_NVP {

    const AUTHORIZATION = "Authorization", SALE = "Sale";

    public static $PAYMENT_ACTIONS = array(
        "Authorization",
        "Sale"
    );

   

}

?>
