<?php

namespace Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet;

use Pronamic\WordPress\Pay\Core\PaymentMethods as Core_PaymentMethods;

/**
 * Title: ING Kassa Compleet payment methods
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class PaymentMethods {
	/**
	 * Constant for the Bancontact method.
	 *
	 * @link https://plugins.trac.wordpress.org/browser/ing-psp/tags/1.0/ing-php/src/Order/Transaction/PaymentMethod.php#L11
	 * @var string
	 */
	const BANCONTACT = 'bancontact';

	/**
	 * Constant for the Banktransfer method.
	 *
	 * @link https://plugins.trac.wordpress.org/browser/ing-kassa-compleet/tags/1.0.6/ingkassacompleet.php#L339
	 * @var string
	 */
	const BANK_TRANSFER = 'bank-transfer';

	/**
	 * Constant for the Cash on Delivery method.
	 *
	 * @link https://plugins.trac.wordpress.org/browser/ing-kassa-compleet/tags/1.0.6/ingkassacompleet.php#L463
	 * @var string
	 */
	const CASH_ON_DELIVERY = 'cash-on-delivery';

	/**
	 * Constant for the CreditCard method.
	 *
	 * @link https://plugins.trac.wordpress.org/browser/ing-kassa-compleet/tags/1.0.6/ingkassacompleet.php#L219
	 * @var string
	 */
	const CREDIT_CARD = 'credit-card';

	/**
	 * Constant for the iDEAL payment method.
	 *
	 * @link https://plugins.trac.wordpress.org/browser/ing-kassa-compleet/tags/1.0.6/ingkassacompleet.php#L109
	 * @var string
	 */
	const IDEAL = 'ideal';

	/**
	 * Constant for the Payconiq method.
	 *
	 * @var string
	 */
	const PAYCONIQ = 'payconiq';

	/**
	 * Constant for the PayPal method.
	 *
	 * @link https://plugins.trac.wordpress.org/browser/ing-psp/tags/1.2/ing-php/src/Order/Transaction/PaymentMethod.php#L21
	 * @var string
	 */
	const PAYPAL = 'paypal';

	/**
	 * Constant for the SOFORT method.
	 *
	 * @link https://plugins.trac.wordpress.org/browser/ing-psp/tags/1.0/ing-php/src/Order/Transaction/PaymentMethod.php#L11
	 * @var string
	 */
	const SOFORT = 'sofort';

	/**
	 * Transform WordPress payment method to ING Kassa Compleet method.
	 *
	 * @since 1.0.5
	 *
	 * @param string $method
	 *
	 * @return string
	 */
	public static function transform( $payment_method ) {
		switch ( $payment_method ) {
			case Core_PaymentMethods::BANCONTACT:
				return self::BANCONTACT;

			case Core_PaymentMethods::BANK_TRANSFER:
				return self::BANK_TRANSFER;

			case Core_PaymentMethods::CREDIT_CARD:
				return self::CREDIT_CARD;

			case Core_PaymentMethods::IDEAL:
				return self::IDEAL;

			case Core_PaymentMethods::PAYCONIQ:
				return self::PAYCONIQ;

			case Core_PaymentMethods::PAYPAL:
				return self::PAYPAL;

			case Core_PaymentMethods::SOFORT:
				return self::SOFORT;

			default:
				return null;
		}
	}
}
