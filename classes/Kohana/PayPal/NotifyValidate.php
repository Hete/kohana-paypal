<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * NotifyValidate
 *
 * @package   PayPal
 * @author    Guillaume Poirier-Morency
 * @copyright (c) 2013, Hète.ca Inc.
 * @license   http://kohanaframework.org/license
 */
class Kohana_PayPal_NotifyValidate extends PayPal {

    public static function get_response_validation(Response $response) {
        return Validation::factory(array('ACK' => $response->body()))
                ->rule('ACK', 'equals', array($response->body(), 'SUCCESS'));
    }

}

