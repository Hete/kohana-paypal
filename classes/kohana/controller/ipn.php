<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Controller to deal with IPN requests.
 * 
 * @see https://www.x.com/developers/paypal/documentation-tools/ipn/integration-guide/IPNIntro
 * 
 * @package   PayPal
 * @category  Controllers
 * @author    Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 * @license   http://kohanaframework.org/license
 */
class Kohana_Controller_IPN extends Controller {

    public function action_index() {

        try {
            $method_name = PayPal::factory('NotifyValidate', $this->request->query())
                    ->execute()
                    ->data('txn_type');

            if (!method_exists($this, $method_name)) {
                throw new HTTP_Exception_404('Action not supported.');
            }
        } catch (Validation_Exception $ve) {
            throw new HTTP_Exception_401($ve->getMessage());
        }

        $this->{'action_' . $method_name};
    }

    /**
     * Example of function to deal with express checkout ipn.
     */
    public function action_express_checkout() {
        $this->response->body("This action is only a test.");
    }

}

?>
