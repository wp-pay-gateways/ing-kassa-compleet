<?php
/**
 * Test Payment Methods.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet
 */

namespace Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet;

use Pronamic\WordPress\Pay\Core\PaymentMethods as Core_PaymentMethods;

/**
 * Title: ING Kassa Compleet payment methods helper test
 * Description:
 * Copyright: 2005-2021 Pronamic
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
	 * @param string $payment_method Payment method.
	 * @param string $expected       Expected value.
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
			array( Core_PaymentMethods::BANCONTACT, PaymentMethods::BANCONTACT ),
			array( Core_PaymentMethods::BANK_TRANSFER, PaymentMethods::BANK_TRANSFER ),
			array( Core_PaymentMethods::CREDIT_CARD, PaymentMethods::CREDIT_CARD ),
			array( Core_PaymentMethods::IDEAL, PaymentMethods::IDEAL ),
			array( Core_PaymentMethods::PAYCONIQ, PaymentMethods::PAYCONIQ ),
			array( Core_PaymentMethods::PAYPAL, PaymentMethods::PAYPAL ),
			array( Core_PaymentMethods::SOFORT, PaymentMethods::SOFORT ),
			array( 'not existing payment method', null ),
		);
	}
}
