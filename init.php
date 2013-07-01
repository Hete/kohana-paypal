<?php

defined('SYSPATH') or die('No direct script access.');

if (Kohana::$config->load('paypal.' . PayPal::$environment . '.ipn_enabled') === TRUE) {
    /**
     * Instant payment notification.
     */
    Route::set('ipn', 'ipn', array(
    ))->defaults(array(
        'controller' => 'ipn',
        'action' => 'index'
    ));
}
?>
