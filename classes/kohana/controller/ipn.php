<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Controller to deal with IPN requests.
 * 
 * @see https://www.x.com/developers/paypal/documentation-tools/ipn/integration-guide/IPNIntro
 * 
 * @package PayPal
 * @subpackage IPN
 * @author Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 */
class Kohana_Controller_PayPal_IPN extends Controller {

    /**
     * Validate the request
     * @throws HTTP_Exception_401 if PayPal does not validate the request
     * @throws Validation_Exception if request is not valid internally
     */
    public function before() {

        parent::before();

        // Verifying the request by sending it back to PayPal

        try {
            PayPal::factory("IPN_NotifyValidate", $this->request->query())->execute();
        } catch (PayPal_Exception $ppe) {
            Log::instance()->add(Log::ERROR, $ppe->getMessage());
            throw new HTTP_Exception_401("You request were invalid.");
        }
    }

    /**
     * Detect the request and call the appropriate action reflectively (external
     * request on itself).
     * @throws HTTP_Exception_401
     */
    public function action_index() {

        $reflective_request = clone $this->request;

        $reflective_request->action($this->request->query("txn_type"));

        $reflective_request->execute();
    }

    /**
     * Example of function to deal with express checkout ipn.
     */
    public function action_express_checkout() {
        $this->response->body("This action is only a test.");
    }

}

?>
