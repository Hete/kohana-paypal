<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Requête de préapprobation d'un paiement.
 * 
 * @link https://cms.paypal.com/ca/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_APPreapproval
 * @author Guillaume Poirier-Morency
 * @copyright Hète.ca
 */
class PayPal_AdaptivePayments_Preapproval extends PayPal_AdaptivePayments {

   

    protected function redirect_param(array $results) {
        return array("preapprovalKey" => $results['preapprovalKey']);
    }

    protected function request_rules() {
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
            'currencyCode' => array(
            ),
            'dateOfMonth' => array(),
            'dayOfWeek' => array(
            //array('regex', array(":value", '^'.implode("|", PayPal::$DAYS_OF_WEEK).'$'))
            ),
            'displayMaxTotalAmount' => array(),
            'feesPayer' => array(
            //array('regex', array(":value", implode("|", '^'.PayPal::$FEES_PAYER).'$'))
            ),
            'ipnNotificationUrl' => array(
                array("max_length", array(":value", 1024)),
                array("url"),
            ),
            'maxAmountPerPayment' => array(),
            'maxNumberOfPayments' => array(),
            'maxNumberOfPaymentsPerPeriod' => array(),
            'maxTotalAmountOfAllPayments' => array(
                array('decimal')
            ),
            'memo' => array(
                array('max_length', array(":value", 1000))
            ),
            'paymentPeriod' => array(
            //array('regex', array(":value", implode("|", '^'.PayPal::$PAYMENT_PERIODS).'$'))
            ),
            'pinType' => array(
            //array('regex', array(":value", implode("|", '^'PayPal::$REQUIRED_STATES).'$'))
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
