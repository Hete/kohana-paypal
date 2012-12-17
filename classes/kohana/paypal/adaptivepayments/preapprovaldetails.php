<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @link https://cms.paypal.com/ca/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_APPreapprovalDetails
 * 
 * @package PayPal
 * @category AdaptativePayments
 * @author Quentin Avedissian <quentin.avedissian@gmail.com>
 * @copyright 
 */
class Kohana_PayPal_AdaptivePayments_PreapprovalDetails extends PayPal_AdaptivePayments {
    
    // Preapproval status
    const ACTIVE = 'ACTIVE', CANCELED = 'CANCELED', DEACTIVED = 'DEACTIVED';

    public static $PREAPPROVAL_STATES = array(
        'ACTIVE',
        'DEACTIVED',
        'CANCELED'
    );

    protected function rules() {

        return array(
            //PreapprovalDetailsRequest Fields
            'getBillingAddress' => array(
                array('PayPal_Valid::boolean')
            ),
            'preapprovalKey' => array(
                array('not_empty')
            ),
        );
    }

}

?>
