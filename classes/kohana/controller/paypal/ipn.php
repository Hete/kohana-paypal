<?php

/**
 * 
 */
class Kohana_Controller_PayPal_IPN extends Controller_PayPal_IPN {

    public function action_index() {

        // Verifying the request by sending it back to PayPal
        
        PayPal::factory();
        
        
        // Call the appropriate action
    }

}

?>
