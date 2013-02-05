<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @package PayPal
 * @category Response
 */
class Kohana_Response_PayPal_NVP extends Response_PayPal {

    public static function factory(Response $response = NULL) {
        return new Response_PayPal_NVP($response);
    }

    /**
     * 
     * @param Response $response
     * @return Response_PayPal
     */
    public function __construct(Response $response = NULL) {

        $data = NULL;

        // Data must be parsed before the constructor call
        parse_str($response->body(), $data);

        parent::__construct($response, $data);
    }

}

?>
