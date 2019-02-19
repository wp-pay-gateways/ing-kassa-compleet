<?php
/**
 * Order Request.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet
 */

namespace Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet;

/**
 * Title: ING Kassa Compleet order request
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Reüel van der Steege
 * @version 2.0.0
 * @since   1.0.0
 */
class OrderRequest {
	/**
	 * Amount in cents
	 *
	 * @var float
	 */
	public $amount;

	/**
	 * ISO 4217 currency code
	 *
	 * @link https://en.wikipedia.org/wiki/ISO_4217
	 *
	 * @var string
	 */
	public $currency;

	/**
	 * Issuer.
	 *
	 * @var string
	 */
	public $issuer;

	/**
	 * Method.
	 *
	 * @var string
	 */
	public $method;

	/**
	 * Merchant order ID.
	 *
	 * @var string
	 */
	public $merchant_order_id;

	/**
	 * Description.
	 *
	 * @var string
	 */
	public $description;

	/**
	 * Return URL.
	 *
	 * @var string
	 */
	public $return_url;

	/**
	 * Get array.
	 *
	 * @return array
	 */
	public function get_array() {
		$array = array(
			'amount'       => $this->amount,
			'currency'     => $this->currency,
			'description'  => $this->description,
			'return_url'   => $this->return_url,
			'transactions' => array(),
		);

		// Array filter will remove values NULL, FALSE and empty strings ('').
		$array = array_filter( $array );

		// Add payment method.
		$payment_method = array(
			'payment_method' => $this->method,
		);

		// Add payment method details.
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
