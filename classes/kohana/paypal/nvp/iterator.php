<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Iterator for PayPal NVP data.
 * 
 * It is a simple iterator that given a prefix, will only return elements that
 * matches it. For instance, if you have something like:
 * 
 * $data = PayPal_NVP_Iterator::factory(array( 
 *     "PAYMENTSDETAILS_0_AMT" => 23,
 *     "PAYMENTSDETAILS_1_AMT" => 30,
 *     "PAYMENTSDETAILS_2_AMT" => 20 
 * ), "PAYMENTSDETAILS");
 * 
 * foreach($data as $key => $value) {
 *  // $key is 0, 1 or 2
 *  // $value is an associative array
 *  $amt = $value["AMT"]
 * }
 * 
 * @package PayPal
 * @category Helpers
 * @author Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 */
class Kohana_PayPal_NVP_Iterator implements Iterator {

    public static function factory(array $data, $prefix) {
        return new PayPal_NVP_Iterator($data, $prefix);
    }

    /**
     *
     * @var ArrayIterator
     */
    private $data,
            $prefix;

    public function __construct(array $data, $prefix) {
        $this->data = new ArrayIterator($data);
        $this->prefix = $prefix;
    }

    public function current() {
        
        //
        
        return $this->data->current();
    }

    public function key() {
        return $this->data->key();
    }

    public function next() {
        return $this->data->next();
    }

    public function rewind() {
        $this->data->rewind();
    }

    public function valid() {
        $this->data->valid() && PayPal_Valid::starts_with($this->data->current(), $this->prefix);
    }

}

?>
