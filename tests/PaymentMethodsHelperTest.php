<?php

/**
 * Title: ING Kassa Compleet payment methods helper test
 * Description:
 * Copyright: Copyright (c) 2005 - 2015
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0.0
 */
class Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethodsHelperTest extends PHPUnit_Framework_TestCase {
	/**
	 * Test transform.
	 *
	 * @dataProvider test_provider
	 */
	public function test_transform( $payment_method, $expected ) {
		$payment_method = Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethodsHelper::transform( $payment_method );

		$this->assertEquals( $expected, $payment_method );
	}

	/**
	 * Test provider.
	 *
	 * @return array
	 */
	public function test_provider() {
		return array(
			array( Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethods::IDEAL, Pronamic_WP_Pay_PaymentMethods::IDEAL ),
			array( Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethods::CREDIT_CARD, Pronamic_WP_Pay_PaymentMethods::CREDIT_CARD ),
			array( Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethods::BANK_TRANSFER, Pronamic_WP_Pay_PaymentMethods::BANK_TRANSFER ),
			array( Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethods::CASH_ON_DELIVERY, null ),
			array( 'not existing payment method', null ),
		);
	}
}
