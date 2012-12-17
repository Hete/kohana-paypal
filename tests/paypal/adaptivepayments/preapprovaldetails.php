<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @link https://cms.paypal.com/ca/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_APPreapprovalDetails
 * 
 * @package PayPal
 * @category Tests
 * @author Quentin Avedissian <quentin.avedissian@gmail.com>
 * @copyright 
 */
class PayPal_AdaptivePayments_PreapprovalDetails_Test extends Unittest_TestCase {

    public function setUp() {
        parent::setUp();
        Request::$initial = '';
    }

    public function test_complete_request() {

        $data = array(
            "startingDate" => Date::formatted_time('now', PayPal::DATE_FORMAT),
            "endingDate" => Date::formatted_time('now', PayPal::DATE_FORMAT),
            "cancelUrl" => "http://www.x.com",
            "returnUrl" => "http://www.x.com",
            "currencyCode" => "CAD",
        );

        $request = PayPal::factory("AdaptivePayments_Preapproval", $data);

        $this->assertInstanceOf("PayPal_AdaptivePayments_Preapproval", $request);

        $response = $request->execute();

        $this->assertInstanceOf("Response_Paypal", $response);

        $this->assertArrayHasKey('preapprovalKey', $response->data());
    }

}

?>
