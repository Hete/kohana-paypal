<?php

class Kohana_PayPal_Validation_Exception extends PayPal_Exception {

    /**
     *
     * @var Validation 
     */
    public $validation;

    public function __construct(Validation $validation, PayPal $request, array $response = array(), $message = "", array $variables = array(), $code = 0) {

        $message .= " :errors";

        $variables += array(
            ":errors" => print_r($validation->errors(), true)
        );

        parent::__construct($request, $response, $message, $variables, $code);

        $this->validation = $validation;
    }

}

?>
