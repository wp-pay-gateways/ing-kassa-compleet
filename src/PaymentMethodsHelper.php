<?php

/**
 * Title: ING Kassa Compleet payment methods helper
 * Description:
 * Copyright: Copyright (c) 2005 - 2015
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0.0
 */
class Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethodsHelper {
	/**
	 * Payment methods
	 *
	 * @var array
	 */
	private static $payment_methods = array(
		Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethods::BANK_TRANSFER    => Pronamic_WP_Pay_PaymentMethods::BANK_TRANSFER,
		Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethods::CASH_ON_DELIVERY => null,
		Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethods::CREDIT_CARD      => Pronamic_WP_Pay_PaymentMethods::CREDIT_CARD,
		Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethods::IDEAL            => Pronamic_WP_Pay_PaymentMethods::IDEAL,
	);

	/////////////////////////////////////////////////

	/**
	 * Transform an ING Kassa Compleet payment method to the WordPress pay payment method.
	 *
	 * @param string $payment_method
	 * @return string
	 */
	public static function transform( $payment_method ) {
		if ( isset( self::$payment_methods[$payment_method] ) ) {
			return self::$payment_methods[$payment_method];
		}

		return null;
	}

	/**
	 * Return all payment methods
	 *
	 * @return array
	 */
	public static function get_methods() {
		return self::$payment_methods;
	}

	/**
	 * Return all supported payment methods
	 *
	 * @return array
	 */
	public static function get_supported_methods() {
		$methods = array();

		foreach( self::$payment_methods as $ing_method => $method ) {
			if ( null !== $method ) {
				$methods[$method] = $ing_method;
			}
		}

		return $methods;
	}
}
