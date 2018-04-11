<?php

namespace Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet;

use Pronamic\WordPress\Pay\Core\Gateway as Core_Gateway;
use Pronamic\WordPress\Pay\Core\PaymentMethods;
use Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet\PaymentMethods as Methods;
use Pronamic\WordPress\Pay\Payments\Payment;
use Pronamic\WordPress\Pay\Util;

/**
 * Title: ING Kassa Compleet
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author  Reüel van der Steege
 * @version 1.0.7
 * @since   1.0.0
 */
class Gateway extends Core_Gateway {
	/**
	 * Constructs and initializes an ING Kassa Compleet gateway
	 *
	 * @param Config $config
	 */
	public function __construct( Config $config ) {
		parent::__construct( $config );

		$this->supports = array(
			'payment_status_request',
		);

		$this->set_method( Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 0.01 );

		// Client
		$this->client = new Client( $config->api_key );
	}

	/**
	 * Get issuers
	 *
	 * @see Pronamic_WP_Pay_Gateway::get_issuers()
	 */
	public function get_issuers() {
		$groups = array();

		$result = $this->client->get_issuers();

		if ( $result ) {
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

	public function get_issuer_field() {
		$payment_method = $this->get_payment_method();

		if ( null === $payment_method || PaymentMethods::IDEAL === $payment_method ) {
			return array(
				'id'       => 'pronamic_ideal_issuer_id',
				'name'     => 'pronamic_ideal_issuer_id',
				'label'    => __( 'Choose your bank', 'pronamic_ideal' ),
				'required' => true,
				'type'     => 'select',
				'choices'  => $this->get_transient_issuers(),
			);
		}
	}

	/**
	 * Get supported payment methods
	 *
	 * @see Pronamic_WP_Pay_Gateway::get_supported_payment_methods()
	 */
	public function get_supported_payment_methods() {
		return array(
			PaymentMethods::BANCONTACT,
			PaymentMethods::BANK_TRANSFER,
			PaymentMethods::CREDIT_CARD,
			PaymentMethods::IDEAL,
			PaymentMethods::PAYCONIQ,
			PaymentMethods::PAYPAL,
			PaymentMethods::SOFORT,
		);
	}

	/**
	 * Is payment method required to start transaction?
	 *
	 * @see Pronamic_WP_Pay_Gateway::payment_method_is_required()
	 */
	public function payment_method_is_required() {
		return true;
	}

	/**
	 * Start
	 *
	 * @see Pronamic_WP_Pay_Gateway::start()
	 */
	public function start( Payment $payment ) {
		$request = new OrderRequest();

		$request->currency          = $payment->get_currency();
		$request->amount            = $payment->get_amount();
		$request->merchant_order_id = $payment->get_order_id();
		$request->description       = $payment->get_description();
		$request->return_url        = $payment->get_return_url();

		// To make the 'Test' meta box work, set payment method to iDEAL if an issuer_id has been set.
		$issuer = $payment->get_issuer();

		$payment_method = $payment->get_method();

		if ( empty( $payment_method ) && ! empty( $issuer ) ) {
			$payment_method = PaymentMethods::IDEAL;
		}

		if ( PaymentMethods::IDEAL === $payment_method ) {
			$request->issuer = $issuer;
		}

		$request->method = Methods::transform( $payment_method );

		$order = $this->client->create_order( $request );

		if ( $order ) {
			$payment->set_transaction_id( $order->id );

			$action_url = add_query_arg( array(
				'payment_redirect' => $payment->get_id(),
				'key'              => $payment->key,
			), home_url( '/' ) );

			if ( PaymentMethods::BANK_TRANSFER === $payment_method ) {
				// Set payment redirect message with received transaction reference.
				// @see https://s3-eu-west-1.amazonaws.com/wl1-apidocs/api.kassacompleet.nl/index.html#payment-methods-without-the-redirect-flow-performing_redirect-requirement
				$message = sprintf(
					/* translators: 1: payment provider name, 2: PSP account name, 3: PSP account number, 4: PSP account BIC, 5: formatted amount, 6: transaction reference */
					__( 'You have chosen the payment method "Bank transfer". To complete your payment, please transfer the amount to the payment service provider (%1$s).

<strong>Account holder:</strong> %2$s
<strong>Account IBAN:</strong> %3$s
<strong>Account BIC:</strong> %4$s
<strong>Amount:</strong> %5$s
<strong>Transaction reference:</strong> %6$s

<em>Please note: only payments with the mentioned transaction reference can be processed.</em>', 'pronamic_ideal' ),
					__( 'ING', 'pronamic_ideal' ),
					'ING PSP',
					'NL13INGB0005300060',
					'INGBNL2A',
					Util::format_price( $payment->get_amount(), $payment->get_currency() ),
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
	 * @param Payment $payment
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
