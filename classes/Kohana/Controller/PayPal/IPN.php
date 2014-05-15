<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Controller to deal with IPN requests.
 *
 * Implemented action deals with a specific txn_type value. You could implement
 * the express_checkout action for instance.
 * 
 * @link https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/
 * 
 * @package   PayPal
 * @category  Controllers
 * @author    Hète.ca Team
 * @copyright (c) 2014, Hète.ca Inc.
 * @license   BSD-3-Clause
 */
class Kohana_Controller_PayPal_IPN extends Controller {

    public function before() {

        parent::before();

        $response = Request::factory('https://www.paypal.com/cgi-bin/webscr')
                ->query($this->request->post())
                ->query('cmd', '_notify-validate')
                ->execute();

        if ($response->body() === 'VERIFIED') {

            // Update action to be called
            $this->request->action($this->request->post('txn_type'));
        } else {

            throw new HTTP_Exception_403('Posted data does not match against PayPal.');
        }
    }

}
