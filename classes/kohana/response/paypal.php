<?php

defined('SYSPATH') or die('No direct script access.');


/**
 * PayPal response.
 */
class Kohana_Response_PayPal extends Response implements ArrayAccess {

    /**
     *
     * @var Validation 
     */
    private $_validation;

    public static function factory(Response $response) {
        return new Response_PayPal($response);
    }

    /**
     * 
     * @param \PayPal $request request that originated the response.
     * @param array $data response datas.
     */
    public function __construct(Response $response) {

        parent::__construct();

        $data = parse_str($response->body());

        // Sanitize data with dots
        array_walk_recursive($data, array($this, "sanitize"));

        $this->_validation = Validation::factory($data);
    }

    /**
     * Sanitize keys.
     * @param string $input
     * @return string
     */
    public function sanitize($input) {
        return str_replace("_", ".", $input);
    }

    // Bindings for validation object

    /**
     * 
     * @param type $field
     * @param array $rules
     * @return Validation
     */
    public function rules($field, array $rules) {
        $this->_validation->rules($field, $rules);
        return $this;
    }

    public function offsetExists($offset) {
        return $this->_validation->offsetExists($offset);
    }

    public function offsetGet($offset) {
        return $this->_validation->offsetGet($offset);
    }

    public function offsetSet($offset, $value) {
        return $this->_validation->offsetSet($offset, $value);
    }

    public function offsetUnset($offset) {
        return $this->_validation->offsetUnset($offset);
    }

}

?>
