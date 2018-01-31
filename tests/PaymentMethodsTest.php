<?php

use Pronamic\WordPress\Pay\Core\PaymentMethods;
use Pronamic\WordPress\Pay\Gateways\ING_KassaCompleet\PaymentMethods as Methods;

/**
 * Title: ING Kassa Compleet payment methods helper test
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0.6
 * @since 1.0.5
 */
class Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethodsTest extends PHPUnit_Framework_TestCase {
	/**
	 * Test transform.
	 *
	 * @dataProvider test_provider
	 *
	 * @param $payment_method
	 * @param $expected
	 */
	public function test_transform( $payment_method, $expected ) {
		$payment_method = Methods::transform( $payment_method );

		$this->assertEquals( $expected, $payment_method );
	}

	/**
	 * Test provider.
	 *
	 * @return array
	 */
	public function test_provider() {
		return array(
			array( Methods::BANCONTACT, PaymentMethods::BANCONTACT ),
			array( Methods::BANK_TRANSFER, PaymentMethods::BANK_TRANSFER ),
			array( Methods::CREDIT_CARD, PaymentMethods::CREDIT_CARD ),
			array( Methods::IDEAL, PaymentMethods::IDEAL ),
			array( Methods::PAYCONIQ, PaymentMethods::PAYCONIQ ),
			array( Methods::PAYPAL, PaymentMethods::PAYPAL ),
			array( Methods::SOFORT, PaymentMethods::SOFORT ),
			array( 'not existing payment method', null ),
		);
	}
}
