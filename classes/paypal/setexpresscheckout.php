<?php

defined('SYSPATH') or die('No direct script access.');

class PayPal_SetExpressCheckout extends PayPal {

    protected function required() {
        return array('AMT') + parent::required();
    }

}

