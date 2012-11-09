<?php

/**
 * Exception thrown in PayPay module.
 *
 * @package PayPal
 * @author     Guillaume Poirier-Morency
 * @copyright  Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
 */
class PayPal_Validation_Exception extends PayPal_Exception {

    /**
     *
     * @var Validation 
     */
    private $validation;

    public function __construct(Validation $validation, PayPal $request, array $response = array(), $message = "", array $variables = array(), $code = 0) {

        $message .= " :errors";

        $variables += array(
            ":errors" => print_r($validation->errors(), true)
        );
        
        parent::__construct($request, $response, $message,$variables, $code);

        $this->validation = $validation;
    }

}

?>
