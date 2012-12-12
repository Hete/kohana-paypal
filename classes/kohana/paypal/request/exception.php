<?php

class PayPal_Request_Exception extends PayPal_Exception {
    
    public function __consturct($request, $response, $message, $variables, $code) {
        parent::__construct($request, $response, $message, $variables, $code);
        
    }
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
