<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Configuration for PayPal.
 * 
 * @package PayPal
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 */
return array(
    'sandbox' => array(
        // PayPal API and username
        'username' => NULL,
        'password' => NULL,
        // PayPal API signature
        'signature' => NULL,
        // Static api id for sandbox apps.
        'api_id' => 'APP-80W284485P519543T',
        'client' => array(
            'options' => array(
                CURLOPT_USERAGENT => 'Kohana',
            )
        ),
        'lang' => 'en_US', // Language for responses
        'ipn' => array(// Instant payment notification
            'enabled' => FALSE,
            'receiver' => array(
                'id' => NULL,
                'email' => NULL,
                'country' => NULL
            ),
        ),
    ),
    'live' => array(
        'username' => NULL,
        'password' => NULL,
        'signature' => NULL,
        'api_id' => NULL,
        'client' => array(
            'options' => array(
                CURLOPT_SSL_VERIFYPEER => TRUE,
                CURLOPT_USERAGENT => "Kohana",
            )
        ),
        'lang' => 'en_US',
        'ipn' => array(
            'enabled' => FALSE,
            'receiver' => array(
                'id' => NULL,
                'email' => NULL,
                'country' => NULL
            ),
        ),
    ),
    'sandbox-beta' => array(
        'username' => NULL,
        'password' => NULL,
        'signature' => NULL,
        'api_id' => 'APP-80W284485P519543T',
        'client' => array(
            'options' => array(
                CURLOPT_USERAGENT => 'Kohana',
            )
        ),
        'lang' => 'en_US',
        'ipn' => array(
            'enabled' => FALSE,
            'receiver' => array(
                'id' => NULL,
                'email' => NULL,
                'country' => NULL
            ),
        ),
    ),
);
