<?php

/**
 * Utility class to encode multidimensionals array and PayPal_Encodable objects.
 *
 * @link  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/library_documentation
 * @see PayPal_Object
 * @see PayPal_Encodable
 * 
 * @author     Guillaume Poirier-Morency
 * @copyright  Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
 */
class PayPal_Encode {

    /**
     * Encode a multi-dimensional array into a PayPal valid array.
     *  
     * An already encoded PayPal array will not be affected.
     * 
     * @param array $data is the data to encode.
     * @param array $result do not specify this parameter, it is used for recursivity.
     * @param array $base do not specify this parameter, it is used for recursivity.
     */
    public static function paypal_encode(array $data, array &$result = array(), array $base = array()) {



        foreach ($data as $key => $value) {

            $local_base = $base + array($key);

            if (is_array($value)) {
                paypal_encode($value, $result, $local_base);
            }

            if ($value instanceof PayPal_Encodable) {
                // On rajoute les valeurs encodés
                $result = $result + $value->encode();
            } elseif (is_object($value)) {
                throw new Kohana_Exception("Object at key :key must implement PayPal_Encodable to be encoded.", array(":key", $key));
            }

            // Imploding dots to build hiearchy          
            $result[implode(".", $local_base)] = $value;
        }


        return $result;
    }

    /**
     * Not implemented yet.
     * @param array $data
     */
    public static function paypal_decode(array $data) {
        
    }

}

?>
