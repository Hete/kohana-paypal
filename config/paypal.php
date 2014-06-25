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
         * @link https://developer.paypal.com/docs/classic/api/apiCredentials
         */
        'username' => NULL,
        'password' => NULL,
        /**
         * If you do not set a signature, it is assumed that an OpenSSL
         * certificate is used. If it is the case, you must CURLOPT_CAINFO in
         * the client_options.
         */
        'signature' => NULL,
        /**
         * API version for NVP.
         */
        'api_version' => '99.0',
        /**
         * Options for cURL.
         *
         * If you need to set your SSL certificate information, you will need
         * to the following:
         *     CURLOPT_CAINFO => APPPATH . 'paypal-certificate.crt',
         *
         */
        'client_options' => array(),
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
        'api_version' => '99.0',
        'client_options' => array(),
        'ipn_enabled' => FALSE
    ),
    'sandbox-beta' => array(
        'username' => NULL,
        'password' => NULL,
        'signature' => NULL,
        'api_version' => '99.0',
        'client_options' => array(),
        'ipn_enabled' => FALSE
    ),
);
