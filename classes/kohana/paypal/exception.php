<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Interface to catch all kind of PayPal_Exception
 *
 * @link  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/library_documentation
 *
 * @package PayPal
 * @category ExpressCheckout
 * @author     Guillaume Poirier-Morency
 * @copyright  Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
 */
interface Kohana_PayPal_Exception {

    /**
     * @return PayPal
     */
    public function request();
}

?>
