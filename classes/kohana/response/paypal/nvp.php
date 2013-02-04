<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 */
class Kohana_Response_PayPal_NVP extends Response_PayPal {

    /**
     * 
     * @param Response $response
     * @return Response_PayPal
     */
    public static function factory(array $data, Response $response = NULL, $redirect_url = NULL) {
        return new Response_PayPal_NVP($data, $response, $redirect_url);
    }

}

?>
