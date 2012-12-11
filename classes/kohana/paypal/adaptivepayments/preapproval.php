<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @link https://cms.paypal.com/ca/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_APPreapproval
 * @author Guillaume Poirier-Morency
 * @copyright Hète.ca
 */
class Kohana_PayPal_AdaptivePayments_Preapproval extends PayPal_AdaptivePayments {

    protected $_redirect_command = 'ap-preapproval';

    protected function redirect_params(array $results) {
        return array("preapprovalkey" => $results['preapprovalKey']);
    }

    protected function rules() {
        return array(
            // Required
            'startingDate' => array(
                array('date', array(":value", PayPal::DATE_FORMAT)),
                array('not_empty')
            ),
            'endingDate' => array(
                array('date', array(":value", PayPal::DATE_FORMAT)),
                array('not_empty')
            ),
            'cancelUrl' => array(
                array('url'),
                array('not_empty'),
            ),
            'returnUrl' => array(
                array('url'),
                array('not_empty'),
            ),
            'currencyCode' => array(
                array('PayPal_Valid::currency_code'),
                array('not_empty'),
            ),
            // Optional
            // clientDetails
            'clientDetails_applicationId' => array(),
            'clientDetails_customerId' => array(
                array("max_length", array(":value", 127)),
            ),
            'clientDetails_customerType' => array(),
            'clientDetails_deviceId' => array(),
            'clientDetails_geoLocation' => array(
            // TODO
            ),
            'clientDetails_ipAddress' => array(
                array('ip'),
            ),
            'clientDetails_model' => array(
                array("max_length", array(":value", 127)),
            ),
            'clientDetails_partnerName' => array(),
            'dateOfMonth' => array(),
            'dayOfWeek' => array(
                array('PayPal_Valid::day_of_week')
            ),
            'displayMaxTotalAmount' => array(
                array('PayPal_Valid::boolean'),
            ),
            'feesPayer' => array(
                array('PayPal_Valid::fee_payer')
            ),
            'ipnNotificationUrl' => array(
                array("max_length", array(":value", 1024)),
                array("url"),
            ),
            'maxAmountPerPayment' => array(
                array('numeric')
            ),
            'maxNumberOfPayments' => array(
                array('numeric')
            ),
            'maxNumberOfPaymentsPerPeriod' => array(
                array('numeric')
            ),
            'maxTotalAmountOfAllPayments' => array(
                array('numeric')
            ),
            'memo' => array(
                array('max_length', array(":value", 1000))
            ),
            'paymentPeriod' => array(
                array('PayPal_Valid::payment_period')
            ),
            'pinType' => array(
                array("PayPal_Valid::pin_type")
            ),
            'senderEmail' => array(
                array('email')
            ),
            
                //
        );
    }

    protected function response_rules() {
        return array(
            'preapprovalKey' => array(
                array('not_empty')
            )
        );
    }

}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
