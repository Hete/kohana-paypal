<?php

defined('SYSPATH') or die('No direct script access.');

if (Kohana::$config->load('paypal.' . PayPal::$environment . '.ipn_enabled') === TRUE) {

    /**
     * Capture IPN (Instant Payment Notification).
     * 
     * You have to configure your PayPal account to send its request to this
     * endpoint.
     */
    Route::set('ipn', 'ipn')->defaults(array('controller' => 'PayPal_IPN'));
}
