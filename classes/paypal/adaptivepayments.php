<?php

/**
 * Base class for AdaptativePayments api.
 *
 * @link  https://cms.paypal.com/ca/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_PermissionsAbout
 *
 * @package PayPal
 * @category AdaptativePayments
 * @author     Guillaume Poirier-Morency
 * @copyright  Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
 */
abstract class PayPal_AdaptivePayments extends PayPal {

    protected function redirect_url(array $response_data) {

        if ($this->_environment === 'live') {
            // Live environment does not use a sub-domain
            $env = '';
        } else {
            // Use the environment sub-domain
            $env = $this->_environment . '.';
        }

        // Add the command to the parameters
        $params = $this->redirect_param($response_data);

        return 'https://www.' . $env . 'paypal.com/webapps/adaptivepayment/flow/' . $this->_redirect_command . '?' . http_build_query($params, '', '&');
    }

}

?>
