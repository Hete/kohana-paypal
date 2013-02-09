<?php

defined('SYSPATH') or die('No direct script access.');

Route::set("paypal", "paypal(/<action>)")->defaults(array(
    "controller" => "paypal",
    "action" => "index"
));
?>
