<?php

defined('SYSPATH') or die('No direct script access.');

Route::set("ipn", "paypal/ipn(/<action>)")->defaults(array(
    "controller" => "paypal_ipn",
    "action" => "index"
));
?>
