<?php

/**
 * 
 * @link https://www.x.com/developers/paypal/documentation-tools/api/cancelpreapproval-api-operation
 * 
 * @package PayPal
 * @category AdaptativePayments
 * @author Quentin Avedissian <quentin.avedissian@gmail.com>
 * @copyright Hète.ca Inc.
 */
class Kohana_PayPal_AdaptivePayments_CancelPreapproval extends PayPal_AdaptivePayments {

    protected function rules() {
        return array(
            'preapprovalKey' => array(
                array('not_empty')
            )
        );
    }

}

?>
