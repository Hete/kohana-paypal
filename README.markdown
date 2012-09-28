# PayPal module for [KohanaPHP](http://github.com/shadowhand/kohana) v3

This module have been redesigned in a morelikely Kohana way. I have implemented a factory method to build specific PayPal request object.

If you want to build a class for a request, you have to make a new class extending PayPal. You have also to implement a required() method which returns an key-tree array against which the request's parameters will be validated.

I need help to build request files to eventually support the whole PayPal NVP API.

## Supported APIs
* Permissions
    * RequestPermissions
    * GetAccessToken
    * GetPermissions
    * CancelPermissions
    * GetBasicPersonalData
    * GetAdvancedPersonalData
* ExpressCheckout (uses a particular API)
    * SetExpressCheckout

AdaptativePayments is on its way !

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
    protected function request_rules() {
        return array(
            'token' => array(
                array('not_empty')
            ),
            'verifier' => array(
                array('not_empty')
            )
        );
    }

    /**
     * A token and a tokenSecret must be providen in the response.
     *
     */
    protected function response_rules() {
        return array(
            'token' => array(
                array('not_empty')
            ),
            'tokenSecret' => array(
                array('not_empty')
            ),
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
$result = $request->execute();
$token = $result['token'];
$token_secret = $result['tokenSecret'];

</pre>

## Support for this project
PayPal has around 5 apis, which each have from 5 to 10 methods. If you want to code some APIs, it's pretty simple, you only have to build the rules for request and response !

Yeah, unit testing also. Kohana has a module for that, so it shouldn't be so hard.
