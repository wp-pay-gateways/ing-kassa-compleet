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
	 * @param $method
	 * @param $expected
	 */
	public function test_transform( $payment_method, $expected ) {
		$ing_method = Methods::transform( $payment_method );

		$this->assertEquals( $expected, $ing_method );
	}

	/**
	 * Test provider.
	 *
	 * @return array
	 */
	public function test_provider() {
		return array(
			array( PaymentMethods::BANCONTACT, Methods::BANCONTACT ),
			array( PaymentMethods::BANK_TRANSFER, Methods::BANK_TRANSFER ),
			array( PaymentMethods::CREDIT_CARD, Methods::CREDIT_CARD ),
			array( PaymentMethods::IDEAL, Methods::IDEAL ),
			array( PaymentMethods::PAYCONIQ, Methods::PAYCONIQ ),
			array( PaymentMethods::PAYPAL, Methods::PAYPAL ),
			array( PaymentMethods::SOFORT, Methods::SOFORT ),
			array( 'not existing payment method', null ),
		);
	}
}
