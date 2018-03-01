<?php

namespace Pronamic\WordPress\Pay\Gateways\ING_KassaCompleet;

use Pronamic\WordPress\Pay\Core\Util as Core_Util;

/**
 * Title: ING Kassa Compleet order request
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Reüel van der Steege
 * @version 1.0.0
 * @since 1.0.0
 */
class OrderRequest {
	/**
	 * Amount in cents
	 */
	public $amount;

	/**
	 * ISO 4217 currency code
	 *
	 * @doc http://en.wikipedia.org/wiki/ISO_4217
	 */
	public $currency;

	public $issuer;

	public $method;

	public $merchant_order_id;

	public $description;

	public $return_url;

	public function __construct() {

	}

	public function get_array() {
		$array = array(
			'amount'       => Core_Util::amount_to_cents( $this->amount ),
			'currency'     => $this->currency,
			'description'  => $this->description,
			'return_url'   => $this->return_url,
			'transactions' => array(),
		);

		// Array filter will remove values NULL, FALSE and empty strings ('')
		$array = array_filter( $array );

		// Add payment method
		$payment_method = array(
			'payment_method' => $this->method,
		);

		// Add payment method details
		switch ( $this->method ) {
			case PaymentMethods::IDEAL:
				$payment_method['payment_method_details'] = array(
					'issuer_id' => $this->issuer,
				);

				break;
		}

		$array['transactions'] = array( $payment_method );

		return $array;
	}
}
