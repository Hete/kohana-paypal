<?php

/**
 * @todo Finish unittests
 */
class AdaptivePaymentsTest extends Unittest_TestCase {

    public function test_cancelpreapproval() {
        $request = PayPal::factory("AdaptivePayments_CancelPreapproval");
        $this->assertInstanceOf(PayPal_AdaptivePayments_CancelPreapproval, $request);
    }
    
    

}

?>
