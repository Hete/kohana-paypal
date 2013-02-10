# PayPal module for [KohanaPHP](http://github.com/shadowhand/kohana) v3

This module have been redesigned in a morelikely Kohana way. Requests and 
Responses inherint from Kohana classes, so you have pretty much all Kohana can
offer in termes of external requesting.

There is a very important thing and it is called number formatting. PayPal only 
accept float numbers with dots. PayPal::number_format should then save your life.

If you want to build a class for a request, you have to make a new class 
extending PayPal. You have also to implement a required() method which returns 
an key-tree array against which the request's parameters will be validated.

I need help to build request files to eventually support the whole PayPal NVP 
API (= lots of work).

Checkout support for this project (the last header of this document)!

It supports new PayPal APIs.

If you think my work is worth some penny, but me a beer! <add_a_link_using_this_paypal_module>

## Supported APIs

* Adaptive Payments
* Payments Pro (in development)
* Permissions (in development)
* Invoicing (in development)
* IPN (in development)
* Adaptive Accounts (coming soon!)

 
You may implement custom api by defining the appropriate classes in your project
or in the module itself and pull request me your work!

## Features

* Request_PayPal inherit from Kohana_Request, so you have all the Kohana's 
external request features build-in;
* Built-in cache capabilities;
* One setup by environment (sandbox, live, sandbox-beta, etc...);
* Static API key preconfigured for sandbox environment;
* Fully configurable cURL client from the settings and by environment;
* Validation at request and response levels;
* Response_PayPal is itself a Validation object instead of a Response object 
(considered useless);
* Security token in responses for security. A request can only be executed by 
the client who created it;
* A static api id for sandbox access is providen by default.
* Deal with failure through PayPal_Exception;
* Pre-computed redirect url if applicable (available in $response->redirect_url);
* Automatic logging;
* Support for NVP;
* Extensive builder syntax for clarity.

## Incoming features

* Filters for validations;
* Support for SOAP;
* Automatic IPN management, the module automatically register to IPN calls.

## How to use this api

<pre>
$params = array(
  'token' => 'dsnahuiy8182318edhd'
  'verifier' => 'asdi2ue89ewioehd'
);
$request = PayPal::factory('PaymentsPro_SetExpressCheckout', $params);

// Now on you may set parameters through param()
$request->param('key', 'value');

...

// Once you're done, you can execute the request!
try {
    $result = $request->execute();
    $token = $result['token'];
    $token_secret = $result['tokenSecret'];
    $redirect_uri = $result->redirect_url;
} catch(PayPal_Exception $ppe) {
    // Do stuff in case of failure...
}

</pre>

## Advanced documentation

### Request

Request are inheriting from Kohana_Request and provide all its features in a
built-in way.

The factory method is PayPal and not Request_PayPal because Request_PayPal would
have required the first parameter to be an uri. Moreover, PayPal is shorter and
cleaner.

Also, requests name must not start by PayPal_, again for readability purpose.

<pre>

$request = PayPal::factory("PaymentsPro_SetExpressCheckout"); // Despites the 
class is named PayPal_PaymentsPro_SetExpressCheckout

</pre>

Best way to insert data in a PayPal request is through the param method. Using
post and query is not recommended: in fact, param will do it for you.

<pre>

$params = array(
    "some_key" => "some_value"
);

$request = PayPal::factory("<request_class_name>", $params);

// Equals to the precedent line
$request->param($params);

</pre>

Overwritting param was a design feature for readability. It is usualy used for
Route parameters, but as no route applies in external requests and param is a
very appropriate name for the purpose, I have decided to use it as a convenient
getter/setter for standard request parameters.

### Response

The response is a Validation object so you do not (and should not) have to pass
it in another Validation object to validate it.

Responses are immutable.

<pre>

$response = $some_request->execute();

$response->data(); // Validation access
$response[]; // Array access which alias data()

$response->rule("some_field", "some_rule"); // You may add

$response->check(); // Warning: throws a PayPal_Validation_Exception (which 
inherit from PayPal_Exception) in case of failure.

</pre>

### Protocols

This module suports many different protocols. The most common protocol used to communicate with PayPal is NVP (name-value pair). You do not really have to deal with them as the requests you will be using already inherit from its protocol. Requests will be first all implemented in NVP, then I will have to integrate some changes to be able to switch to SOAP without trouble.

The main issue with SOAP is that it is not uni-dimensional, leading to a real complexity when it comes to validation. Kohana does not have multi-dimensional validation. This is why NVP is much more recommended.

### Why this module is not generic

Each different api requires a class, and each of their requests requires a class too. It is designed this way to provide overloading possibilities at every levels. Let say a constant name is not consistent and changes over the api? It can be overloaded in the specific api where it is different. Also each api requires specific validation rules, so having a class for each allow you to have custom validation any request.

### Why Response_PayPal does not inherit from Response

Response provides nothing but a body() method, which returns the body of PayPal's response. Validating or implementing convenient array access into this object is difficult (you have to create a Validation attribute). By making itself a Validation object, it opens all the Kohana validation possibilities, which is rather useful. Anyway, you would validate your input in another Validation object and probably drop the Response.

### Some cool approach

Let's say you have a group of inputs for a paypal checkout form, you can simply 
pass it in the request if keys are compatible.

<pre>

$request->param($this->request->param("paypal"));

// Response is a ArrayAccess and a Validation object, nice stuff can be done 
like validating status

$response->rule("status", "equals", array("Success"));

try {
    $response->check();
} catch(PayPal_Exception $ppe) {
    // Handle exception...
}

// Or in a single builder line

try {
    PayPal::factory("API_NameOfRequest", array(<some_parameters>))
        ->execute()
        ->rule("<some_field>", "<some_rule>") // Add extra rules
        ->check(); // Check that throws a PayPal_Exception
} catch(PayPal_Exception $ppe) {
    // Handle exception...
}

</pre>


## Support for this project

PayPal has around 5 apis, which each have from 5 to 10 methods. If you want to 
code some APIs, it's pretty simple, you only have to build the rules for request and response !

Writing unittests would really make my life easier (and so your and everyone who 
will use this module).
