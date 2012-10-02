<?php

/**
 * Base class to represents PayPal objects such as ResponseEnvelope and RequestEnvelope.
 */
abstract class PayPal_Object implements PayPal_Encodable {

    /**
     * Returns the validation rules for this object.
     */
    public abstract function rules();
}

?>
