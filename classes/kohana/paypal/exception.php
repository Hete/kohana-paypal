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
class Kohana_PayPal_Exception extends Kohana_Exception {

    /**
     *
     * @var PayPal
     */
    public $request;

    /**
     *
     * @var PayPal_Response 
     */
    public $response;

    /**
     * 
     * @param PayPal $request
     * @param array $response
     * @param type $message
     * @param array $variables
     * @param type $code
     */
    public function __construct(PayPal_Request $request, PayPal_Response $response, $message, array $variables = NULL, $code = 0) {
        // Message d'erreur
        $this->request = $request;
        $this->response = $response;

        parent::__construct($message, $variables, $code);
    }

}

?>
