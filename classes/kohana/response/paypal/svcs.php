<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 */
class Kohana_Response_PayPal_SVCS extends Response_PayPal {

    public static function factory(Response $response = NULL) {
        return new Response_PayPal_NVP($response);
    }

    public function __construct(Response $response) {
        parent::__construct($data);
    }

}

?>
