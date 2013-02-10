<?php

class Kohana_PayPal_Validation_Exception extends PayPal_Exception {

    /**
     *
     * @var Validation 
     */
    private $validation;

    public function __construct(Validation $validation, Request_PayPal $request, Response_PayPal $response = NULL, $message = "PayPal request failed.", array $variables = NULL, $code = 0) {

        parent::__construct($request, $response, $message, $variables, $code);

        $this->validation = $validation;
    }

}

?>
