<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * PayPal response.
 */
class Kohana_Response_PayPal implements ArrayAccess {

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

        parse_str($response->body(), $data);

        foreach ($data as $key => $value) {
            unset($data[$key]);
            $data[$this->sanitize($key)] = $value;
        }

        $this->_validation = Validation::factory($data)
                ->rule("responseEnvelope_ack", "not_empty")
                ->rule("responseEnvelope_ack", "PayPal_Valid::contained", array(":value", Request_PayPal::$SUCCESS_ACKNOWLEDGEMENTS));
    }

    /**
     * Sanitize keys.
     * @param string $input
     * @return string
     */
    public function sanitize($value) {
        return str_replace(".", "_", $value);
    }

    public function data() {
        return $this->_validation->data();
    }

    // Bindings for validation object

    public function check() {
        return $this->_validation->check();
    }

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
