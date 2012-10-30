# PayPal module for [KohanaPHP](http://github.com/shadowhand/kohana) v3

This module have been redesigned in a morelikely Kohana way. I have implemented a factory method to build specific PayPal request object.

If you want to build a class for a request, you have to make a new class extending PayPal. You have also to implement a required() method which returns an key-tree array against which the request's parameters will be validated.

I need help to build request files to eventually support the whole PayPal NVP API.

## Supported APIs
* AdaptivePayments
    * Preapproval
* ExpressCheckout (uses a particular API)
    * SetExpressCheckout
* Permissions
    * RequestPermissions
    * GetAccessToken
    * GetPermissions
    * CancelPermissions
    * GetBasicPersonalData
    * GetAdvancedPersonalData

## Features

* Ultraflexible ! 
* Fully configurable cURL client.
* Validation at request and response.
* Security token in responses for security.
* Access to sandbox with a static api id.
* Configuration for each environnements.
* Custom exceptions to deal with failure properly.
* Redirect URL are pre-computed from the class and available in the response.
* PayPal objects for stuff like RequestEnvelope and ResponseEnvelope.
* Encoding api to easily work with multidimensional arrays.
* More to come.. !

## Latest changes in development trunk

* Single method for rules, it's useless to validate PayPal response, only the acknowledgment.
* Starting builder syntax for param(), headers(), config() and check().
* Security token in a more convenient way (specify an expected token at request execution and it will be validated in the request)
* Benchmarking.
* Encoding and decoding methods with PayPal object are on their way !

### Example of request class :
<pre>
/**
 * PayPal ExpressCheckout integration.
 *
 * @see  https://cms.paypal.com/ca/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_PermissionsGetAccessTokenAPI
 *
 * @package    Kohana
 * @author     Kohana Team
 * @copyright  (c) 2009 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class PayPal_Permissions_GetAccessToken extends PayPal_Permissions {

    /**
     * Need a token and the verifier in the request.
     * @return type
     */
    protected function rules() {
        return array(
            'token' => array(
                array('not_empty')
            ),
            'verifier' => array(
                array('not_empty')
            )
        );
    }   

}
</pre>

How to use this class :
<pre>
$params = array(
  'token' => 'dsnahuiy8182318edhd'
  'verifier' => 'asdi2ue89ewioehd'
);
$request = PayPal::factory('SetExpressCheckout', $params);
$result = $request->execute($this->request->post("security_token"));
$token = $result['token'];
$token_secret = $result['tokenSecret'];
$redirect_uri = $result['redirect_url'];
</pre>

As suggested by Kohana, keep a security token in a hidden field of your form and test it conviniently within the validation of your PayPal request.

## Support for this project
PayPal has around 5 apis, which each have from 5 to 10 methods. If you want to code some APIs, it's pretty simple, you only have to build the rules for request and response !

Yeah, unit testing also. Kohana has a module for that, so it shouldn't be so hard.
