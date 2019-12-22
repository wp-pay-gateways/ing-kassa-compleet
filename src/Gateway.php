<?php
/**
 * Gateway.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet
 */

namespace Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet;

use Pronamic\WordPress\Pay\Core\Gateway as Core_Gateway;
use Pronamic\WordPress\Pay\Core\PaymentMethods as Core_PaymentMethods;
use Pronamic\WordPress\Pay\Payments\Payment;

/**
 * Title: ING Kassa Compleet
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Reüel van der Steege
 * @version 2.0.3
 * @since   1.0.0
 */
class Gateway extends Core_Gateway {
	/**
	 * Client.
	 *
	 * @var Client
	 */
	protected $client;

	/**
	 * Constructs and initializes an ING Kassa Compleet gateway
	 *
	 * @param Config $config Config.
	 */
	public function __construct( Config $config ) {
		parent::__construct( $config );

		$this->set_method( self::METHOD_HTTP_REDIRECT );

		// Supported features.
		$this->supports = array(
			'payment_status_request',
		);

		// Client.
		$this->client = new Client( $config->api_key );
	}

	/**
	 * Get issuers
	 *
	 * @see Core_Gateway::get_issuers()
	 */
	public function get_issuers() {
		$groups = array();

		$result = $this->client->get_issuers();

		if ( is_array( $result ) ) {
			$groups[] = array(
				'options' => $result,
			);
		}

		$error = $this->client->get_error();

		if ( is_wp_error( $error ) ) {
			$this->error = $error;
		}

		return $groups;
	}

	/**
	 * Get supported payment methods
	 *
	 * @see Core_Gateway::get_supported_payment_methods()
	 */
	public function get_supported_payment_methods() {
		return array(
			Core_PaymentMethods::BANCONTACT,
			Core_PaymentMethods::BANK_TRANSFER,
			Core_PaymentMethods::CREDIT_CARD,
			Core_PaymentMethods::IDEAL,
			Core_PaymentMethods::PAYCONIQ,
			Core_PaymentMethods::PAYPAL,
			Core_PaymentMethods::SOFORT,
		);
	}

	/**
	 * Start
	 *
	 * @param Payment $payment Payment.
	 *
	 * @see Core_Gateway::start()
	 */
	public function start( Payment $payment ) {
		$request = new OrderRequest();

		$request->currency          = $payment->get_total_amount()->get_currency()->get_alphabetic_code();
		$request->amount            = $payment->get_total_amount()->get_cents();
		$request->merchant_order_id = $payment->get_order_id();
		$request->description       = $payment->get_description();
		$request->return_url        = $payment->get_return_url();

		// To make the 'Test' meta box work, set payment method to iDEAL if an issuer_id has been set.
		$issuer = $payment->get_issuer();

		$payment_method = $payment->get_method();

		if ( Core_PaymentMethods::IDEAL === $payment_method ) {
			$request->issuer = $issuer;
		}

		$request->method = PaymentMethods::transform( $payment_method );

		$order = $this->client->create_order( $request );

		if ( $order ) {
			$payment->set_transaction_id( $order->id );

			// Set action URL to order pay URL.
			if ( isset( $order->order_url ) ) {
				$payment->set_action_url( $order->order_url );
			}

			// Set action URL to transction payment URL (only if payment method is set).
			if ( isset( $order->transactions[0]->payment_url ) ) {
				$payment->set_action_url( $order->transactions[0]->payment_url );
			}

			// Bank transfer instructions.
			if ( Core_PaymentMethods::BANK_TRANSFER === $payment_method && isset( $order->transactions[0]->id ) ) {
				$payment->set_action_url(
					\sprintf(
						'https://api.kassacompleet.nl/pay/%s/completed/',
						esc_html( $order->transactions[0]->id )
					)
				);
			}
		}

		$error = $this->client->get_error();

		if ( is_wp_error( $error ) ) {
			$this->error = $error;
		}
	}

	/**
	 * Update status of the specified payment
	 *
	 * @param Payment $payment Payment.
	 */
	public function update_status( Payment $payment ) {
		$transaction_id = $payment->get_transaction_id();

		if ( empty( $transaction_id ) ) {
			return;
		}

		$order = $this->client->get_order( $transaction_id );

		if ( ! is_object( $order ) ) {
			return;
		}

		$payment->set_status( Statuses::transform( $order->status ) );

		if ( isset( $order->transactions[0]->payment_method_details ) ) {
			$details = $order->transactions[0]->payment_method_details;

			if ( isset( $details->consumer_name ) ) {
				$payment->set_consumer_name( $details->consumer_name );
			}

			if ( isset( $details->consumer_iban ) ) {
				$payment->set_consumer_iban( $details->consumer_iban );
			}
		}

		$error = $this->client->get_error();

		if ( is_wp_error( $error ) ) {
			$this->error = $error;
		}
	}
}
