<?php

/**
 * Title: ING Kassa Compleet payment methods
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0.0
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethods {
	/**
	 * Constant for the Banktransfer method.
	 *
	 * @see https://plugins.trac.wordpress.org/browser/ing-kassa-compleet/tags/1.0.6/ingkassacompleet.php#L339
	 * @var string
	 */
	const BANK_TRANSFER = 'bank-transfer';

	/**
	 * Constant for the Cash on Delivery method.
	 *
	 * @see https://plugins.trac.wordpress.org/browser/ing-kassa-compleet/tags/1.0.6/ingkassacompleet.php#L463
	 * @var string
	 */
	const CASH_ON_DELIVERY = 'cash-on-delivery';

	/**
	 * Constant for the CreditCard method.
	 *
	 * @see https://plugins.trac.wordpress.org/browser/ing-kassa-compleet/tags/1.0.6/ingkassacompleet.php#L219
	 * @var string
	 */
	const CREDIT_CARD = 'credit-card';

	/**
	 * Constant for the iDEAL payment method.
	 *
	 * @see https://plugins.trac.wordpress.org/browser/ing-kassa-compleet/tags/1.0.6/ingkassacompleet.php#L109
	 * @var string
	 */
	const IDEAL = 'ideal';

	/////////////////////////////////////////////////

	/**
	 * Transform WordPress payment method to ING Kassa Compleet method.
	 *
	 * @since 1.1.6
	 * @param string $method
	 * @return string
	 */
	public static function transform( $payment_method ) {
		switch ( $payment_method ) {
			case Pronamic_WP_Pay_PaymentMethods::BANK_TRANSFER :
				return self::BANK_TRANSFER;
			case Pronamic_WP_Pay_PaymentMethods::CREDIT_CARD :
				return self::CREDIT_CARD;
			case Pronamic_WP_Pay_PaymentMethods::IDEAL :
				return self::IDEAL;
			default :
				return null;
		}
	}
}
