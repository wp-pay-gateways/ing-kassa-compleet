<?php

/**
 * Title: ING Kassa Compleet
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Reüel van der Steege
 * @version 1.0.4
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Gateways_ING_KassaCompleet_Gateway extends Pronamic_WP_Pay_Gateway {
	/**
	 * The ING Kassa Compleet client object
	 *
	 * @var Pronamic_WP_Pay_Gateways_ING_KassaCompleet_Client
	 */
	private $client;

	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an ING Kassa Compleet gateway
	 *
	 * @param Pronamic_WP_Pay_Gateways_ING_KassaCompleet_Config $config
	 */
	public function __construct( Pronamic_WP_Pay_Gateways_ING_KassaCompleet_Config $config ) {
		parent::__construct( $config );

		$this->set_method( Pronamic_WP_Pay_Gateway::METHOD_HTTP_REDIRECT );
		$this->set_has_feedback( true );
		$this->set_amount_minimum( 0.01 );

		// Client
		$this->client = new Pronamic_WP_Pay_Gateways_ING_KassaCompleet_Client( $config->api_key );
	}

	/////////////////////////////////////////////////

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
		} else {
			$this->error = $this->client->get_error();
		}

		return $groups;
	}

	/////////////////////////////////////////////////

	public function get_issuer_field() {
		if ( Pronamic_WP_Pay_PaymentMethods::IDEAL === $this->get_payment_method() ) {
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

	/////////////////////////////////////////////////

	/**
	 * Get payment methods
	 *
	 * @see Pronamic_WP_Pay_Gateway::get_payment_methods()
	 */
	public function get_payment_methods() {
		return Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethodsHelper::get_methods();
	}

	/**
	 * Get supported payment methods
	 *
	 * @see Pronamic_WP_Pay_Gateway::get_supported_payment_methods()
	 */
	public function get_supported_payment_methods() {
		return Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethodsHelper::get_supported_methods();
	}

	/////////////////////////////////////////////////

	/**
	 * Get input HTML
	 *
	 * ING Kassa Compleet does not present a payment screen to the customer if no payment method is set,
	 * so we use iDEAL as default payment method to make the 'Test' meta box work.
	 *
	 * @see Pronamic_WP_Pay_Gateway::get_input_html()
	 */
	public function get_input_html() {
		$payment_method = $this->get_payment_method();

		if ( empty( $payment_method ) ) {
			$this->set_payment_method( Pronamic_WP_Pay_PaymentMethods::IDEAL );
		}

		return parent::get_input_html();
	}

	/////////////////////////////////////////////////

	/**
	 * Start
	 *
	 * @see Pronamic_WP_Pay_Gateway::start()
	 */
	public function start( Pronamic_Pay_Payment $payment ) {
		$request = new Pronamic_WP_Pay_Gateways_ING_KassaCompleet_OrderRequest();

		$request->currency          = $payment->get_currency();
		$request->amount            = $payment->get_amount();
		$request->merchant_order_id = $payment->get_order_id();
		$request->description       = $payment->get_description();
		$request->return_url        = $payment->get_return_url();

		// To make the 'Test' meta box work, set payment method to iDEAL if an issuer_id has been set.
		$issuer = $payment->get_issuer();

		$payment_method = $payment->get_method();

		if ( ! empty( $issuer ) ) {
			$payment_method = Pronamic_WP_Pay_PaymentMethods::IDEAL;
		}

		switch ( $payment_method ) {
			case Pronamic_WP_Pay_PaymentMethods::BANK_TRANSFER :
				$request->method = Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethods::BANK_TRANSFER;

				break;
			case Pronamic_WP_Pay_PaymentMethods::CREDIT_CARD :
				$request->method = Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethods::CREDIT_CARD;

				break;
			case Pronamic_WP_Pay_PaymentMethods::IDEAL :
				$request->method = Pronamic_WP_Pay_Gateways_ING_KassaCompleet_PaymentMethods::IDEAL;
				$request->issuer = $issuer;

				break;
		}

		$order = $this->client->create_order( $request );

		if ( $order ) {
			$payment->set_transaction_id( $order->id );
			$payment->set_action_url( $order->transactions[0]->payment_url );
		} else {
			$this->error = $this->client->get_error();
		}

		/*
		 * Schedule transaction status request
		 *
		 * @since 1.0.1
		 */
		$time = time();

		wp_schedule_single_event( $time + 30, 'pronamic_ideal_check_transaction_status', array( 'payment_id' => $payment->get_id(), 'seconds' => 30 ) );
	}

	/////////////////////////////////////////////////

	/**
	 * Update status of the specified payment
	 *
	 * @param Pronamic_Pay_Payment $payment
	 */
	public function update_status( Pronamic_Pay_Payment $payment ) {
		$order = $this->client->get_order( $payment->get_transaction_id() );

		if ( $order ) {
			$payment->set_status( Pronamic_WP_Pay_ING_KassaCompleet_Statuses::transform( $order->status ) );

			if ( isset( $order->transactions[0]->payment_method_details ) ) {
				$details = $order->transactions[0]->payment_method_details;

				if ( isset( $details->consumer_name ) ) {
					$payment->set_consumer_name( $details->consumer_name );
				}

				if ( isset( $details->consumer_iban ) ) {
					$payment->set_consumer_iban( $details->consumer_iban );
				}
			}
		} else {
			$this->error = $this->client->get_error();
		}
	}
}
