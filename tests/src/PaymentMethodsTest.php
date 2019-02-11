<?php

namespace Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet;

use Pronamic\WordPress\Pay\Core\PaymentMethods as CorePaymentMethods;

/**
 * Title: ING Kassa Compleet payment methods helper test
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.5
 */
class PaymentMethodsTest extends \PHPUnit_Framework_TestCase {
	/**
	 * Test transform.
	 *
	 * @dataProvider test_provider
	 *
	 * @param $payment_method
	 * @param $expected
	 */
	public function test_transform( $payment_method, $expected ) {
		$ing_method = PaymentMethods::transform( $payment_method );

		$this->assertEquals( $expected, $ing_method );
	}

	/**
	 * Test provider.
	 *
	 * @return array
	 */
	public function test_provider() {
		return array(
			array( CorePaymentMethods::BANCONTACT, PaymentMethods::BANCONTACT ),
			array( CorePaymentMethods::BANK_TRANSFER, PaymentMethods::BANK_TRANSFER ),
			array( CorePaymentMethods::CREDIT_CARD, PaymentMethods::CREDIT_CARD ),
			array( CorePaymentMethods::IDEAL, PaymentMethods::IDEAL ),
			array( CorePaymentMethods::PAYCONIQ, PaymentMethods::PAYCONIQ ),
			array( CorePaymentMethods::PAYPAL, PaymentMethods::PAYPAL ),
			array( CorePaymentMethods::SOFORT, PaymentMethods::SOFORT ),
			array( 'not existing payment method', null ),
		);
	}
}