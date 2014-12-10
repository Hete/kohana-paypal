<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Callback
 *
 * @link https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/Callback_API_Operation_NVP/
 *
 * @package    PayPal
 * @subpackage PaymentsPro
 * @author     Hète.ca Team
 * @copyright  (c) 2014, Hète.ca Inc.
 * @license    BSD-3-Clauses
 */
class Kohana_PayPal_Callback extends PayPal {

	public static function get_request_validation(Request $request)
	{
		return parent::get_request_validation($request)
			->rule('CURRENCYCODE', 'not_empty')
			->rule('CURRENCYCODE', 'in_array', array(':value', static::$CURRENTY_CODES));
	}
}
