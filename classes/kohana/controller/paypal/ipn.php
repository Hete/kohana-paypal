<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @package PayPal
 * @subpackage IPN
 */
class Kohana_Controller_PayPal_IPN extends Controller {

    const VERIFIED = "VERIFIED";

    public function action_index() {

        // Verifying the request by sending it back to PayPal

        $request = PayPal::factory("IPN_NotifyValidate", $this->request->query());

        $status = $request->execute()->body();

        if ($status !== static::VERIFIED) {
            throw new HTTP_Exception_401("This request is not validated by PayPal!");
        }

        // Validation

        $validation = Validation::factory($this->query())
                ->rule("receiver_email", "equals", array(":value", $request->config("ipn.receiver.email")))
                ->rule("receiver_id", "equals", array(":value", $request->config("ipn.receiver.id")))
                ->rule("residence_country", "equals", array(":value", $request->config("ipn.receiver.country")))
                ->rule("test_ipn", "equals", array(":value", (int) ($request->environment() === PayPal::SANDBOX)));

        if (!$validation->check()) {
            throw new Validation_Exception($validation);
        }        

        // Call the appropriate action

        $action_name = $this->request->query("txn_type");

        if (!Valid::not_empty($action_name)) {
            // It's empty, dangerous for recursive request (calling again index)
            throw new HTTP_Exception_401("No txn_type provided!");
        }

        // Reflective call with right action

        $reflective_request = clone $this->request;

        $reflective_request->action($action_name);

        $reflective_request->execute();
    }

}

?>
