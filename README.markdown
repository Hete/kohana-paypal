# PayPal module for [KohanaPHP](http://github.com/shadowhand/kohana) v3

This module have been redesigned in a morelikely Kohana way. Requests and 
Responses inherint from Kohana classes, so you have pretty much all Kohana can
offer in termes of external requesting.

If you want to build a class for a request, you have to make a new class extending PayPal. You have also to implement a required() method which returns an key-tree array against which the request's parameters will be validated.

I need help to build request files to eventually support the whole PayPal NVP API (= lots of work).

Checkout support for this project (the last header of this document)!

## Supported APIs

* AdaptivePayments
* ExpressCheckout (still in development)
* Permissions
* Invoice

You may implement custom api by defining the appropriate classes in your project
or in the module itself and pull request me your work!

## Features

* All the features of Kohana Request and Response classes for both Paypal requests and responses!
* One setup by environment (sandbox, live, sandbox-beta, etc...).
* Fully configurable cURL client from the settings and by environment.
* Validation at request and response.
* Security token in responses for security. A request can only be executed by the client who created it.
* A static api id for sandbox access is providen by default.
* Deal with failure through PayPal_Exception.
* Redirect URL are pre-computed from the class and available in the response.
* More to come.. !

## Latest changes in development trunk

* Single method for rules, it's useless to validate PayPal response, only the acknowledgment;
* Starting builder syntax for param(), headers(), config() and check();
* Security token in a more convenient way (specify an expected token at request execution and it will be validated in the request);
* Benchmarking;
* Encoding and decoding methods with PayPal object are on their way !
* Encoding and decoding was quite dumb, so now on, just refer to standard PayPal doc to build requests and interpret responses;
* Unittesting (I need help on this);
* Improved stability for most of requests;

## How to use this api

<pre>
$params = array(
  'token' => 'dsnahuiy8182318edhd'
  'verifier' => 'asdi2ue89ewioehd'
);
$request = PayPal::factory('SetExpressCheckout', $params);

// Now on you may set parameters through param() or post() method (it's the same!)
$request->param('key', 'value');
$request->post('key2', 'value2');
...

// Once you're done, you can execute the request!
try {
    $result = $request->execute($this->request->post("security_token"));
    $token = $result['token'];
    $token_secret = $result['tokenSecret'];
    $redirect_uri = $result->redirect_url;
} catch(PayPal_Exception $ppe) {
    // Do stuff in case of failure...
}

// Some cool approach

/* Let's say you have a group of inputs for a paypal checkout form, you can 
 * simply pass it in the request if keys are compatible.
 */
$request->param($this->request->param("paypal"));

// Response is a ArrayAccess on a Validation attribute. So you can do cool stuff.
$response->rule("bla", "equals", array(1));

if(!$response->check()) {
    // Do stuff...
}

</pre>


## Support for this project

PayPal has around 5 apis, which each have from 5 to 10 methods. If you want to code some APIs, it's pretty simple, you only have to build the rules for request and response !

Yeah, unit testing also. Kohana has a module for that, so it shouldn't be so hard.
