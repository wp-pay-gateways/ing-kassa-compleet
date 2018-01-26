<?php
use Pronamic\WordPress\Pay\Core\PaymentMethods;
use Pronamic\WordPress\Pay\Gateways\ING_KassaCompleet\PaymentMethods;

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
	 */
	public function test_transform( $payment_method, $expected ) {
		$payment_method = PaymentMethods::transform( $payment_method );

		$this->assertEquals( $expected, $payment_method );
	}

	/**
	 * Test provider.
	 *
	 * @return array
	 */
	public function test_provider() {
		return array(
			array( PaymentMethods::BANCONTACT, PaymentMethods::BANCONTACT ),
			array( PaymentMethods::BANK_TRANSFER, PaymentMethods::BANK_TRANSFER ),
			array( PaymentMethods::CREDIT_CARD, PaymentMethods::CREDIT_CARD ),
			array( PaymentMethods::IDEAL, PaymentMethods::IDEAL ),
			array( PaymentMethods::PAYCONIQ, PaymentMethods::PAYCONIQ ),
			array( PaymentMethods::PAYPAL, PaymentMethods::PAYPAL ),
			array( PaymentMethods::SOFORT, PaymentMethods::SOFORT ),
			array( 'not existing payment method', null ),
		);
	}
}
