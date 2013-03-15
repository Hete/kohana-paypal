<?php

defined('SYSPATH') or die('No direct script access.');

return array(
    // PayPal environment: live, sandbox, beta-sandbox
    'sandbox' => array(
        // PayPal API and username
        'username' => NULL,
        'password' => NULL,
        // PayPal API signature
        'signature' => NULL,
        // Static api id for sandbox apps.
        'api_id' => 'APP-80W284485P519543T',
        // cURL settings
        'curl' => array(
            // Additionnal options
            'options' => array(
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_SSL_VERIFYHOST => FALSE,
                CURLOPT_USERAGENT => "Kohana",
            )
        ),
        // Language for responses
        'lang' => 'en_US',
        'ipn' => array(
            'enabled' => TRUE, // If enabled, will automatically register ipn notification
            'receiver' => array(
                'id' => "28fj8098wjnu",
                'email' => "foo@foo.com",
                'country' => "US" 
            ),
            'protocol' => 'http'
        ),
    ),
    'live' => array(
        // PayPal API and username
        'username' => NULL,
        'password' => NULL,
        // PayPal API signature
        'signature' => NULL,
        'api_id' => NULL,
        'curl' => array(
            'options' => array(
                CURLOPT_SSL_VERIFYPEER => TRUE,
                CURLOPT_SSL_VERIFYHOST => TRUE,
                CURLOPT_USERAGENT => "Kohana",
            )
        ),
        // Language for responses
        'lang' => 'en_US',
        'ipn' => array(
            'enabled' => TRUE,
            'receiver' => array(
                'id' => NULL,
                'email' => NULL,
                'country' => NULL 
            ),
            'protocol' => 'https'
        ),
    ),
    'sandbox-beta' => array(
        // PayPal API and username
        'username' => NULL,
        'password' => NULL,
        // PayPal API signature
        'signature' => NULL,
        // Static api id for sandbox apps.
        'api_id' => 'APP-80W284485P519543T',
        'curl' => array(
            'options' => array(
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_SSL_VERIFYHOST => FALSE,
                CURLOPT_USERAGENT => "Kohana",
            )
        ),
        // Language for responses
        'lang' => 'en_US',
        'ipn' => array(
            'enabled' => TRUE,
            'receiver' => array(
                'id' => NULL,
                'email' => NULL,
                'country' => NULL 
            ),
            'protocol' => 'http'
        ),
    ),
);
