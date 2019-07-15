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
 * @version 2.0.1
 * @since   1.0.0
 */
class Gateway extends Core_Gateway {
	/**
	 * Slug of this gateway
	 *
	 * @var string
	 */
	const SLUG = 'ing-kassa-compleet';

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
		$this->set_slug( self::SLUG );

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
	 * Is payment method required to start transaction?
	 *
	 * @see Core_Gateway::payment_method_is_required()
	 */
	public function payment_method_is_required() {
		return true;
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

		if ( empty( $payment_method ) && ! empty( $issuer ) ) {
			$payment_method = Core_PaymentMethods::IDEAL;
		}

		if ( Core_PaymentMethods::IDEAL === $payment_method ) {
			$request->issuer = $issuer;
		}

		$request->method = PaymentMethods::transform( $payment_method );

		$order = $this->client->create_order( $request );

		if ( $order ) {
			$payment->set_transaction_id( $order->id );

			$action_url = $payment->get_pay_redirect_url();

			if ( Core_PaymentMethods::BANK_TRANSFER === $payment_method ) {
				/*
				 * Set payment redirect message with received transaction reference.
				 *
				 * @link https://s3-eu-west-1.amazonaws.com/wl1-apidocs/api.kassacompleet.nl/index.html#payment-methods-without-the-redirect-flow-performing_redirect-requirement
				 */
				$message = sprintf(
					/* translators: 1: payment provider name, 2: PSP account name, 3: PSP account number, 4: PSP account BIC, 5: formatted amount, 6: transaction reference */
					__(
						'You have chosen the payment method "Bank transfer". To complete your payment, please transfer the amount to the payment service provider (%1$s).

<strong>Account holder:</strong> %2$s
<strong>Account IBAN:</strong> %3$s
<strong>Account BIC:</strong> %4$s
<strong>Amount:</strong> %5$s
<strong>Transaction reference:</strong> %6$s

<em>Please note: only payments with the mentioned transaction reference can be processed.</em>',
						'pronamic_ideal'
					),
					__( 'ING', 'pronamic_ideal' ),
					'ING PSP',
					'NL13INGB0005300060',
					'INGBNL2A',
					$payment->get_total_amount()->format_i18n(),
					$order->transactions[0]->payment_method_details->reference
				);

				$payment->set_meta( 'payment_redirect_message', $message );
			}

			if ( isset( $order->transactions[0]->payment_url ) ) {
				$action_url = $order->transactions[0]->payment_url;
			}

			$payment->set_action_url( $action_url );
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
