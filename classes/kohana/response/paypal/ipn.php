<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @package PayPal
 * @subpackage IPN
 * @author Hète.ca
 * @copyright (c) 2013, Hète.ca Inc.
 */
class Kohana_Response_PayPal_IPN extends Response_PayPal {

    const VERIFIED = "VERIFIED", INVALID = "INVALID";

    public function __construct(Request_PayPal $request, Response $response) {

        $data = array("status" => $response->body());

        parent::__construct($request, $response, $data);

        // Adding default rules
        $this->rule("STATUS", "not_empty");
    }

}

?>
