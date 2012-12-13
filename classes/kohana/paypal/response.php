<?php

/**
 * 
 */
class Kohana_PayPal_Response extends Validation {

    /**
     * 
     * @param array $data
     * @return \PayPal_Response
     */
    public static function factory(PayPal $request, array $data) {
        return new PayPal_Response($request, $data);
    }

    /**
     *
     * @var \PayPal
     */
    public $request;
    
    
    /**
     * Sanitize keys.
     * @param string $input
     * @return string
     */
    public function sanitize($input) {
        return str_replace("_", ".", $input);
    }

    /**
     * 
     * @param \PayPal $request request that originated the response.
     * @param array $data response datas.
     */
    public function __construct(PayPal $request, array $data) {


        // Sanitize data with dots
        array_walk_recursive($data, array($this, "sanitize"));

        $this->request = $request;

        // Building validation object
        parent::_construct($data);
    }

}

?>
