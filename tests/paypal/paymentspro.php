<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @package PayPal
 * @subpackage PaymentsPro
 * @category Tests
 */
class PayPal_PaymentsPro_Test extends Unittest_TestCase {

    private $token;

    public function setUp() {
        parent::setUp();
        Request::$initial = "";
    }

    public function test_SetExpressCheckout() {

        $response = PayPal::factory('PaymentsPro_GetExpressCheckoutDetails')
                ->data('AMT', 12.50)
                ->execute()
                ->data('TOKEN');

        // Une personne physique doit accéder au site de PayPal
        echo $response->redirect_url . "\n";

        $this->token = readline('Enter TOKEN:');
    }

    /**
     * @depends SetExpressCheckout
     */
    public function test_DoExpressCheckoutPayment() {

        $this->payer_id = PayPal::factory('PaymentsPro_DoExpressCheckoutPayment')
                ->data('AMT', 12.50)
                ->execute()
                ->data('PAYERID');
    }

    /**
     * @depends DoExpressCheckoutPayment
     */
    public function test_GetExpressCheckoutDetails() {

        $response = PayPal::factory('PaymentsPro_GetExpressCheckoutDetails')
                ->data('TOKEN', $this->token)
                ->data('AMT', 12.50)
                ->execute();

        View::factory('paypal/paymentspro/getexpresscheckoutdetails', array('getexpresscheckoutdetails' => $response));
    }

    public function test_CancelPermissions() {
        $this->markTestIncomplete("This test has not been implemented yet.");
    }

    public function test_GetAccessToken() {
        $this->markTestIncomplete("This test has not been implemented yet.");
    }

    public function test_GetAdvancedPersonalData() {
        $this->markTestIncomplete("This test has not been implemented yet.");
    }

    public function test_GetBasicPersonalData() {
        $this->markTestIncomplete("This test has not been implemented yet.");
    }

    public function test_GetPermissions() {
        $this->markTestIncomplete("This test has not been implemented yet.");
    }

    public function test_RequestPermissions() {
        $this->markTestIncomplete("This test has not been implemented yet.");
    }

}

?>
