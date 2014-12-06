<?php defined('SYSPATH') or die('No direct script access.');

/**
 * General tests for the PayPal module.
 *
 * You need to set up a sandbox account in order to test anything.
 *
 * @package  PayPal
 * @category Tests
 * @author   Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @license  http://kohanaframework.org/license
 */
class PayPalTest extends Unittest_TestCase {

	public function setUp()
	{
		parent::setUp();

		/**
		 * Ensures tests are running on the sandbox mode.
		 */
		PayPal::$environment = PayPal::SANDBOX;

		Kohana::$config = $this->getMock('Config');

		Kohana::$config
			->expects($this->at(0))
			->method('load')
			->with($this->equalTo('paypal.'.PayPal::$environment.'.ipn_enabled'))
			->will($this->returnValue(TRUE));

		/**
		 * Reload the file to enable the IPN route.
		 */
		require MODPATH.'paypal/init'.EXT;

		Kohana::$config = $this->getMock('Config');

		Kohana::$config
			->expects($this->any())
			->method('load')
			->will($this->returnValue(array(
				'username'    => uniqid(),
				'password'    => uniqid(),
				'signature'   => uniqid(),
				'api_version' => '99.0',
				'ipn_enabled' => TRUE
			)));
	}

    /**
     * Simple utility to assert a Validation object and output errors.
     */
    public function assertValidation(Validation $validation)
	{
        return $this->assertTrue($validation->check(), print_r($validation->errors(), TRUE));
    }

    public function testRequest()
	{
        $request = PayPal::factory('SetExpressCheckout');

        $this->assertInstanceOf('Request', $request);

        $this->assertNotNull(Kohana::$config->load('paypal.sandbox.signature'), 'Set your sandbox credentials to run this test.');

        $this->assertEquals('https://api-3t.sandbox.paypal.com/nvp', $request->uri());

        $this->assertArrayHasKey('METHOD', $request->query());
        $this->assertArrayHasKey('USER', $request->query());
        $this->assertArrayHasKey('PWD', $request->query());
        $this->assertArrayHasKey('SIGNATURE', $request->query());
        $this->assertArrayHasKey('VERSION', $request->query());
    }

    /**
     * Does not execute any external requesting.
     */
    public function testLiveRequest()
	{
        PayPal::$environment = PayPal::LIVE;

        $request = PayPal::factory('SetExpressCheckout');

        $this->assertNotNull(Kohana::$config->load('paypal.live.signature'), 'Set your live credentials to run this test.');

        $this->assertEquals('https://api-3t.paypal.com/nvp', $request->uri());
    }

    public function testParseResponse()
	{
        if ( ! $this->hasInternet())
		{
            $this->markTestSkipped();
        }

        $response = PayPal::factory('SetExpressCheckout')
                ->query('AMT', 12.27)
                ->execute();

        $data = PayPal::parse_response($response);

        $this->markTestIncomplete();
    }

    /**
     * Expendables data to test PayPal::expand function.
     *
     * @return array
     */
    public function providerExpandables()
	{
        return array(
            array(
                // one level array
                array('FOO'),
                array('FOO')
            ),
            array(
                // one level dictionary
                array('FOO' => 'BAR'),
                array('FOO' => 'BAR')
            ),
            array(
                // dot
                array('FOO.BAR' => 'BAR'),
                array('FOO' => array('BAR' => 'BAR'))
            ),
            array(
                // mixed dot and underscore
                array('FOO.BAR_FOO' => 'FOO'),
                array('FOO' => array('BAR' => array('FOO' => 'FOO')))
            ),
            array(
                // multiple keys
                array(
                    'FOO_0_BAR' => 'Teeest :)',
                    'FOO_1_BAR' => 'Example!'
                ),
                array(
                    'FOO' => array(
                        '0' => array(// 0
                            'BAR' => 'Teeest :)'
                        ),
                        '1' => array(// 1
                            'BAR' => 'Example!'
                        )
                    )
                )
            ),
        );
    }

    /**
     * @dataProvider providerExpandables
     */
    public function testExpand(array $flattened, array $expanded)
	{
        $this->assertEquals($expanded, PayPal::expand($flattened));
    }

    public function testSetExpressCheckout()
	{
        $request = PayPal::factory('SetExpressCheckout')
                ->query('AMT', 45)
                ->query('RETURNURL', 'http://example.com')
                ->query('CANCELURL', 'http://example.com');

        $this->assertValidation(PayPal_SetExpressCheckout::get_request_validation($request));

        if ( ! $this->hasInternet())
		{
            $this->markTestSkipped();
        }

        $response = $request->execute();

        $this->assertValidation(PayPal_SetExpressCheckout::get_response_validation($response));

        $response = PayPal::parse_response($response);

        // redirection
        $this->assertEquals('https://www.sandbox.paypal.com/cgi-bin/webscr', PayPal_SetExpressCheckout::redirect_url());

        $this->assertArrayHasKey('TOKEN', $response);

        return array($response['TOKEN'], 'wert3t559t89i');
    }

    /**
     * @depends testSetExpressCheckout
     */
    public function testDoExpressCheckoutPayment($token, $payer_id)
	{
        $response = PayPal::factory('DoExpressCheckoutPayment')
                ->query('AMT', 45)
                ->query('PAYERID', $payer_id)
                ->query('TOKEN', $token)
                ->execute();

        $validation = PayPal_DoExpressCheckoutPayment::get_response_validation($response);

        $this->assertValidation($validation);

        $response = PayPal::parse_response($response);

        return $response['TRANSACTIONID'];
    }

    /**
     * @depends testDoExpressCheckoutPayment
     */
    public function testGetExpressCheckoutDetails($token)
	{
        $response = PayPal::factory('GetExpressCheckoutDetails')
                ->query('TOKEN', $token)
                ->query('PAYERID', $this->payer_id)
                ->execute();

        $this->assertEquals($response->query('AMT'), 45);
    }

    public function testDoDirectPayment()
	{
        $request = PayPal::factory('DoDirectPayment')
				->query('FIRSTNAME', uniqid())
				->query('LASTNAME', uniqid())
				->query('LASTNAME', uniqid())
				->query('LASTNAME', uniqid())
                ->query('CREDITCARDTYPE', 'Visa')
                ->query('ACCT', '4012888888881881')
                ->query('CVV2', '272')
                ->query('EXPDATE', '052020')
                ->query('EMAIL', 'info@example.com')
				->query('COUNTRYCODE', 'US')
				->query('CITY', 'Phoenix')
				->query('STATE', 'AB')
                ->query('ZIP', 'H0H 0H0')
                ->query('STREET', '55, Sesam street')
                ->query('AMT', 44.1);

		$this->assertValidation(PayPal_DoDirectPayment::get_request_validation($request));

        if (!$this->hasInternet())
		{
            $this->markTestSkipped();
        }

		$response = $request->execute();

        $this->assertValidation(PayPal_DoDirectPayment::get_response_validation($response));

        $response = PayPal::parse_response($response);

        // @todo add the Authorization method
        $response = PayPal::factory('DoDirectPayment')
                ->query('CREDITCARDTYPE', 'Visa')
                ->query('ACCT', '4222222222222')
                ->query('CVV2', '272')
                ->query('EXPDATE', '052020')
                ->query('EMAIL', 'info@example.com')
                ->query('ZIP', 'H0H 0H0')
                ->query('STREET', '55, Sesam street')
                ->query('AMT', 44.1)
                ->execute();

        $this->assertValidation(PayPal_DoDirectPayment::get_response_validation($response));

        $response = PayPal::parse_response($response);

        return $response['AUTHORIZATIONID'];
    }

	public function providerAuthorizationMethods()
	{
		return array(
			array('DoAuthorization'),
			array('DoVoid'),
			array('DoReauthorization'),
		);
	}

	/**
	 * @depends testDoDirectPayment
	 * @provider providerAuthorizationMethods
	 */
	public function testAuthorizationMethod(array $details)
	{
		list($method, $query) = $details;

        $request = PayPal::factory('DoAuthorization')
                ->query('AUTHORIZATIONID', $authorization_id);

		foreach ($query as $key => $value)
		{
			$request->query($key, $value);
		}

		$response = $request->execute();
	}

	public function providerTransactionMethods($transaction_id)
	{
		return array(
			array('GetTransactionDetails', array('TRANSACTIONID' => $transaction_id)),
			array('RefundTransaction', array('TRANSACTIONID' => $transaction_id)),
			array('TransactionSearch', array('TRANSACTIONID' => $transaction_id))
		);
	}

	/**
	 * Capture an authorization.
	 *
	 * @depends testDoDirectPayment
	 */
	public function testDoCapture($authorization_id)
	{
        $request = PayPal::factory('DoCapture')
                ->query('AUTHORIZATIONID', $authorization_id);

		$this->assertValidation(PayPal_DoCapture::get_request_validation($request));

		$response = $request->execute();

		$this->assertValidation(PayPal_DoCapture::get_response_validation($response));

		$response = PayPal::parse_response();

		return $response['TRANSACTIONID'];
	}

    /**
	 * Automate some tests about transactions.
	 *
     * @depends testDoCapture
	 * @provider providerTransactionMethods
     */
	public function testTransactionMethod(array $details)
	{
		list($method, $query) = $details;

		$request = PayPal::factory($method)
			->query('TRANSACTION_ID', $transaction_id);

		foreach ($query as $key => $value)
		{
			$request->query($key, $value);
		}

	}

    /**
     * @depends testDoDirectPayment
     */
    public function testSetCustomerBillingAgreement($transaction_id)
	{
        $response = PayPal::factory('SetCustomerBillingAgreement')
                ->query('TRANSACTIONID', $transaction_id)
                ->execute();

        $response = PayPal::parse_response($response);

        return $response['TOKEN'];
    }

    /**
     * @depends testSetCustomerBillingAgreement
     */
    public function testCreateBillingAgreement($token, $payer_id, $transaction_id) {

        PayPal::factory('RefundTransaction')
                ->query('TOKEN', $token)
                ->query('PAYERID', $payer_id)
                ->query('TRANSACTIONID', $transaction_id)
                ->execute();
    }

    /**
     * @depends SetExpressCheckout
     */
    public function testAddressVerify($token, $payer_id) {

        PayPal::factory('AddressVerify')
                ->query('EMAIL', $token)
                ->query('ZIP', $payer_id)
                ->execute();
    }

    /**
     * @depends testSetExpressCheckout
     */
    public function testCallback($token, $payer_id) {

        $request = PayPal::factory('Callback')
                ->query('EMAIL', $token)
                ->query('ZIP', $payer_id)
                ->execute();
    }

    public function testSandboxIPN() {

        $response = Request::factory('ipn')
                        ->method(Request::POST)
                        ->post(array(
                            'txn_type' => 'express_checkout',
                            'receiver_id' => '1234',
                            'receiver_email' => 'foo@example.com',
                            'residence_country' => 'USA',
                            'test_ipn' => TRUE
                        ))->execute();

        $this->assertEquals(200, $response->status());
    }

    public function testLiveIPN()
	{
        if (! $this->hasInternet()) {

            $this->markTestSkipped();
        }

        PayPal::$environment = PayPal::LIVE;

        $response = Request::factory('ipn')
                        ->method(Request::POST)
                        ->post(array(
                            'txn_type' => 'express_checkout',
                            'receiver_id' => '1234',
                            'receiver_email' => 'foo@example.com',
                            'residence_country' => 'USA',
                            'test_ipn' => FALSE
                        ))->execute();

        $this->assertEquals(403, $response->status());
    }

    public function testTestIPNOnLiveEnvironment()
	{
        PayPal::$environment = PayPal::LIVE;

        $response = Request::factory('ipn')
                        ->method(Request::POST)
                        ->post('test_ipn', TRUE)
                        ->post('txn_type', 'express_checkout')
                        ->execute();

        $this->assertEquals(403, $response->status());
    }

    public function testLiveIPNOnSandboxEnvironment()
	{
        $response = Request::factory('ipn')
                        ->method(Request::POST)
                        ->post('test_ipn', FALSE)
                        ->post('txn_type', 'express_checkout')
                        ->execute();

        $this->assertEquals(403, $response->status());
    }

}

class Controller_PayPal_IPN extends Controller {

	public function action_express_checkout()
	{

	}
}
