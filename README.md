PayPal module for the Kohana framework
======================================

This module supports classic NVP PayPal api along with an IPN endpoint.

NVP api is being deprecated by the new 
[RESTful api](https://developer.paypal.com/docs/api/). You can use it with
[PayPal-PHP-SDK](https://github.com/paypal/PayPal-PHP-SDK).

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

It returns a prepared `Request` object that you can parametrize and execute:

```php
$response = $setexpresscheckout
    ->query('AMT', 33.23)
    ->query('RETURNURL', Route::url('default', array(
        'controller' => 'payment', 
        'action' => 'complete'), 'https')) // use your own return url
    ->query('CANCELURL', Route::url('default', array(
        'controller' => 'payment', 
        'action' => 'cancel'), 'https')) // use your own cancel url
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

Validation rules are given for some requests. One way to contribute is to provide
more validations for the request and responses objects.

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
