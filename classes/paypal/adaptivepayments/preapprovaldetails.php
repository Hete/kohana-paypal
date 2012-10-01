<?php

/**
 * 
 * 
 * @link https://cms.paypal.com/ca/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_APPreapprovalDetails
 * 
 * @package PayPal
 * @category AdaptativePayments
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright 
 */
class PayPal_AdaptativePayments_PreapprovalDetails extends PayPal_AdaptativePayments {

    protected function request_rules() {
        
        return array(
            //PreapprovalDetailsRequest Fields
            'getBillingAddress' => array(
                array('boolean')         
            ),
            'preapprovalKey' => array(
                array('not_empty')
            ),
        );
        
        
    }

    protected function response_rules() {
        return array(
            //PreapprovalDetailsResponse Fields
            'addresslist' => array(
                array('not_empty')                
            ),
            'approved' => array(
                array('boolean')
            ),
            'cancelUrl' => array(
                array('not_empty')                
            ),
            'curPayments' => array(
                array('not_empty')
            ),
            'curPaymentsAmount' => array(
                array('not_empty')
            ),
            'curPeriodAttempts' => array(
                array('not_empty')
            ),
            'curPeriodEndingDate' => array(
                array('not_empty')
            ),
            'currencyCode' => array(
              array('not_empty'),
              array('regex', array(':value', '^' .'^'.explode('|', Paypal::$CURRENCIES).'$'))
            ),
            'dateOfMonth' => array(
                array('range' , array (':value', array('0', '30'))),
                array('numeric'),
                array('not_empty')
            ),
            'dayOfWeek' => array(
                array('not_empty'),
                array('regex', ':value', '^'.explode('|', Paypal::$DAYS_OF_WEEK) . '*')
            ),
            'endingDate' => array(
                array('not_empty'),
                array('date', array(":value", PayPal::DATE_FORMAT)),                
            ),
            'ipnNotificationUrl' => array(
                array('not_empty'),
                array('url'),
            ),
            'maxAmountPerPayment' => array(
                array('not_empty'),
                array('numeric'),
            ),
            'maxNumberOfPayments' => array(
                array('not_empty'),
                array('digit'),
            ),
            'maxNumberOfPaymentsPerPeriod' => array(
                array('digit'),
                array('not_empty'),
                ),
            'maxTotalAmountOfAllPayments' => array(
                array('not_empty'),
                array('numeric'),
            ),
            'memo' => array(
                array('not_empty'),
                ),
            'paymentPeriod' => array(
                array('not_empty'),
                array('regex', array(':value', '^' .'^'.explode('|', Paypal::$PAYMENT_PERIODS).'$'))
            ),
            'pinType' => array(
                array('not_empty'),
                array('regex', array(':value', '^' .'^'.explode('|', Paypal::$PERSONAL_IDENTIFICATION_NUMBER).'$'))
            ),
            
        );
    }

}


?>
