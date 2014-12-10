PayPal module for the Kohana framework
======================================

[![Build Status](https://travis-ci.org/Hete/kohana-paypal.svg?branch=3.3%2Fdevelop)](https://travis-ci.org/Hete/kohana-paypal)

This module supports classic NVP PayPal api along with an IPN endpoint.

NVP api is being deprecated by the new RESTful api.

Basic usage
-----------
You need to obtain credentials from PayPal website in order to use this module.
Once you have them, set them in the configuration file.

If you would like to use a certificate, do not set the signature and provide
your certificate path to cURL. It is documented in the module configuration
file.

You only need to factory an external `Request` object
```php
$setexpresscheckout = PayPal::factory('SetExpressCheckout');
```

It returns a prepared `Request` object that you can execute
```php
$response = $setexpresscheckout
    ->query('AMT', 33.23)
    ->execute();
```

Do not pass an array to `Request::query`, it will erase the initial configuration
which contains the credentials and method.

Redirection are handled for requests that requires user interaction.
```php
$url = PayPal_SetExpressCheckout::redirect_url($response);
$query = PayPal_SetExpressCheckout::redirect_query($response);

HTTP::redirect($url . URL::query($query));
```

Or simply
```php
PayPal_SetExpressCheckout::redirect($response);
```

PayPal class provides a method for parsing the Response into an associative
array so that you can work with data efficiently.
```php
$response = PayPal::parse_response($response);
```

If you need the raw PayPal array:
```php
$arr = PayPal::parse_response($response, FALSE);
```

Then you can expand it
```php
$expanded = PayPal::expand($arr);
```

Validation
----------
Validation rules are given for some requests.
```php
$request_validation = PayPal_SetExpressCheckout::get_request_validation($request);

$response_validation = PayPal_SetExpressCheckout::get_response_validation($response);
```

IPN
---
To enable IPN, set the `ipn_enabled` option in your configuration. It will create
an endpoint for PayPal's request.

    example.com/ipn

Requests are automatically verified against PayPal in the live environment. You only
need to overload `Kohana_Controller_IPN` and set supported action. If not specified,
a 404 error will be triggered.

In your application, you only need to override the IPN controller and implement
the appropriate action. Action name will match the `txn_type` key in PayPal's
query.
```php
class Controller_PayPal_IPN extends Kohana_Controller_PayPal_IPN {

    public function action_express_checkout()
    {
        // Put your code here...
    }
}
```
