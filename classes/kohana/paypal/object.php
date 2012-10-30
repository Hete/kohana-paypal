<?php

/**
 * Base class to represents PayPal objects such as ResponseEnvelope and RequestEnvelope.
 */
abstract class Kohana_PayPal_Object {

    /**
     * Returns the validation rules for this object.
     */
    public abstract function rules();

    /**
     * Returns an valid PayPal encoded version of this object.
     */
    public abstract function encode();
}

?>
