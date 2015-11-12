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
	 * Transform an ING Kassa Compleet payment method to the WordPress pay payment method.
	 *
	 * @param string $payment_method
	 * @return string
	 */
	public static function transform( $payment_method ) {
		switch ( $payment_method ) {
			case Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethods::IDEAL :
				return Pronamic_WP_Pay_PaymentMethods::IDEAL;
			case Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethods::CREDIT_CARD :
				return Pronamic_WP_Pay_PaymentMethods::CREDIT_CARD;
			case Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethods::BANK_TRANSFER :
				return Pronamic_WP_Pay_PaymentMethods::BANK_TRANSFER;
			case Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethods::CASH_ON_DELIVERY :
				return null;
			default :
				return null;
		}
	}
}
