<?php

/**
 * Interface to catch all kind of PayPal_Exception
 */
interface Kohana_PayPal_Exception {

    /**
     * @return PayPal
     */
    public function request();
}

?>
