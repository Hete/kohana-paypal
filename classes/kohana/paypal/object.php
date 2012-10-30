<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Base class to represents PayPal objects such as ResponseEnvelope and RequestEnvelope..
 *
 * @link  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/library_documentation
 * 
 * @package PayPal
 * @author     Guillaume Poirier-Morency
 * @copyright  Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
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
