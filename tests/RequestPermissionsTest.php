<?php

class PermissionsTest extends Unittest_TestCase {

    public function test_requestpermissions() {

        $params = array('');
        
        $request = PayPal::factory('Permissions_RequestPermissions', $params);

        $this->assertInstanceOf("PayPal_Permissions_RequestPermissions", $request);
        
        $response = $request->execute();
    }

}

?>
