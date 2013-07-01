<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Configuration for PayPal.
 * 
 * @package   PayPal
 * @author    Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright (c) 2013, Hète.ca Inc.
 * @license   http://kohanaframework.org/license
 */
return array(
    'live' => array(
        /**
         * Credentials
         * 
         * Username, password and signature are found in your account settings.
         * API id has to be requested and is specific to your application.
         */
        'username' => NULL,
        'password' => NULL,
        'signature' => NULL,
        'api_id' => NULL,
        /**
         * Options for cURL. Security is enforced in live mode.       
         */
        'curl_options' => array(
            CURLOPT_USERAGENT => 'Kohana',
            CURLOPT_SSL_VERIFYPEER => TRUE,
            CURLOPT_SSL_VERIFYHOST => TRUE
        ),
        'lang' => 'en_US', // Language for responses
        /**
         * IPN opens an endpoint so that PayPal can notify you about updates on
         * your transactions. It is useful for updating transaction status. You
         * have to implement actions specifically to your application.
         */
        'ipn_enabled' => FALSE
    ),
    'sandbox' => array(
        'username' => NULL,
        'password' => NULL,
        'signature' => NULL,
        'api_id' => 'APP-80W284485P519543T', // Static api id for sandbox apps.
        'curl_options' => array(
            CURLOPT_USERAGENT => 'Kohana',
        ),
        'lang' => 'en_US',
        'ipn_enabled' => FALSE
    ),
    'sandbox-beta' => array(
        'username' => NULL,
        'password' => NULL,
        'signature' => NULL,
        'api_id' => 'APP-80W284485P519543T',
        'curl_options' => array(
            CURLOPT_USERAGENT => 'Kohana',
        ),
        'lang' => 'en_US',
        'ipn_enabled' => FALSE
    ),
);
