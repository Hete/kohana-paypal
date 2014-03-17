# PayPal module for Kohana 3.3

This module supports classic NVP PayPal api along with an IPN endpoint.

NVP api is being deprecated by the new RESTful api. New projects should use new api.

## Featuers

* classic NVP api
* validations for Request and Response objects
* IPN endpoint
* various views

## Basic usage

    $setexpresscheckout = PayPal::factory('SetExpressCheckout');

You can pass options like with Request factory

    $setexpresscheckotu = PayPal::factory('SetExpressCheckout', array(
        'cache' => Cache::instance()
    ));

You can get the inner Request object

    $response = $setexpresscheckout
        ->query('AMT', 33.23)
        ->execute();

Executing the Request will returna Response object.

Redirection are handled for requests that requires user interaction.
    
    $url = PayPal_SetExpressCheckout::redirect_url($response);
    $param = PayPal_SetExpressCheckout::redirect_query($response);

    HTTP::redirect($url . URL::query($param));

Or simply
    
    PayPal_SetExpressCheckout::redirect($response);

PayPal class provides a method for parsing the Response into an associative array.

    $response = PayPal::parse_response($response);

If you need the raw PayPal array:

    $arr = PayPal::parse_response($response, FALSE);

Then you can expand it

    $expanded = PayPal::expand($arr);

## Validation

Some validation rules are given

For request

    $validation = PayPal_SetExpressCheckout::get_request_validation($request);

For response

    $validation = PayPal_SetExpressCheckout::get_response_validation($response);

## IPN

To enable IPN, set the ipn\_enabled option in your configuration.

It will create an endpoint for PayPal's request.

    example.com/ipn

Requests are automatically verified against PayPal. You only need to overload Kohana\_Controller\_IPN and set supported action. If not specified, a 404 error will be triggered. express\_checkout is given as an example.

## Views

A set of useful views are given to deal with common requests. They are designed to present parsed Response object using PayPal::parse\_response.

* DoDirectPayment
* GetExpressCheckoutDetails
* GetTransactionDetails
