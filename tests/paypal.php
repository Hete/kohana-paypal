<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * General tests for the PayPal module.
 */
class PayPal_Test extends Unittest_TestCase {
    
    /**
     * Various assertions about a PayPal request.
     */
    public function test_request() {
        
        $request = PayPal::factory("AdaptivePayments_Pay");

        // API url must be a valid url
        $this->assertTrue(Valid::url($request->api_url()));
        
        // Param is an alias for post
        $this->assertEquals($request->param(), $request->post());
        
        // Curl key exists
        $this->assertNotNull($request->config("curl"));
        
        // Curl options is at least an array
        $this->assertTrue(Arr::is_array($request->config("curl.options")));
        
    }
    
    /**
     * Test a PayPal response. PayPal request must be correct in order to 
     * test responses.
     * @depends test_request
     */
    public function test_response() {

        $request = PayPal::factory("AdaptivePayments_Pay");

        $this->assertTrue(Valid::url($request->api_url()));
        
        
        
        
    }
    
}

?>
