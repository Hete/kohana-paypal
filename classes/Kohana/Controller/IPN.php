<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Controller to deal with IPN requests.
 * 
 * @link https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/
 * 
 * @package   PayPal
 * @category  Controllers
 * @author    Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 * @license   http://kohanaframework.org/license
 */
class Kohana_Controller_IPN extends Controller {

    public function before() {

        parent::before();

        $response = PayPal::factory('NotifyValidate')
                ->query($this->request->post())
                ->query('cmd', '_notify-validate')
                ->execute();

        $validation = PayPal_NotifyValidate::get_response_validation($response);

        if (!$validation->check()) {
            throw new HTTP_Exception_401('Posted data does not match against PayPal.');
        }

        // Update action to be called
        $this->request->action($this->request->post('txn_type'));
    }

    /**
     * Example of function to deal with express checkout ipn.
     */
    public function action_express_checkout() {
        $this->response->body('This action is only a test.');
    }

}
