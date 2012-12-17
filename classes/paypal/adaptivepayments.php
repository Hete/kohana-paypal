<?php

defined('SYSPATH') or die('No direct script access.');

abstract class PayPal_AdaptivePayments extends Kohana_PayPal_AdaptivePayments {

    public static $FEES_PAYER = array(
        'SENDER',
        'PRIMARYRECEIVER',
        'EACHRECEIVER',
        'SECONDARYONLY'
    );

}

?>
