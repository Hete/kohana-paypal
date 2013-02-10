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

    const VERIFIED = "VERIFIED";

    /**
     * Validate the request
     * @throws HTTP_Exception_401 if PayPal does not validate the request
     * @throws Validation_Exception if request is not valid internally
     */
    public function before() {

        parent::before();

        // Verifying the request by sending it back to PayPal

        $request = PayPal::factory("IPN_NotifyValidate", $this->request->query());

        $status = $request->execute()->body();

        if ($status !== static::VERIFIED) {
            throw new HTTP_Exception_401("This request is not validated by PayPal!");
        }

        $validation = Validation::factory($this->query())
                ->rule("receiver_email", "equals", array(":value", $request->config("ipn.receiver.email")))
                ->rule("receiver_id", "equals", array(":value", $request->config("ipn.receiver.id")))
                ->rule("residence_country", "equals", array(":value", $request->config("ipn.receiver.country")))
                ->rule("test_ipn", "equals", array(":value", (int) ($request->environment() === PayPal::SANDBOX)))
                ->rule("txn_type", "not_empty");

        if (!$validation->check()) {
            throw new Validation_Exception($validation);
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

}

?>
