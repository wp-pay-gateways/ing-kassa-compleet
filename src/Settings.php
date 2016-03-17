<?php

/**
 * Title: ING Kassa Compleet gateway settings
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0.0
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Gateways_ING_KassaCompleet_Settings extends Pronamic_WP_Pay_GatewaySettings {
	public function __construct() {
		add_filter( 'pronamic_pay_gateway_sections', array( $this, 'sections' ) );
		add_filter( 'pronamic_pay_gateway_fields', array( $this, 'fields' ) );
	}

	public function sections( array $sections ) {
		// iDEAL
		$sections['ing_kassa_compleet'] = array(
			'title'   => __( 'ING Kassa Compleet', 'pronamic_ideal' ),
			'methods' => array( 'ing_kassa_compleet' ),
			'description' => sprintf(
				__( 'Account details are provided by %s after registration. These settings need to match with the %1$s dashboard.', 'pronamic_ideal' ),
				__( 'ING', 'pronamic_ideal' )
			),
		);

		// Transaction feedback
		$sections['ing_kassa_compleet_feedback'] = array(
			'title'   => __( 'Transaction feedback', 'pronamic_ideal' ),
			'methods' => array( 'ing_kassa_compleet' ),
			'description' => sprintf(
				__( 'Set the Webhook URL in the %s dashboard to receive automatic transaction status updates.', 'pronamic_ideal' ),
				__( 'ING Kassa Compleet', 'pronamic_ideal' )
			),
		);

		// Return
		return $sections;
	}

	public function fields( array $fields ) {
		// API Key
		$fields[] = array(
			'filter'      => FILTER_SANITIZE_STRING,
			'section'     => 'ing_kassa_compleet',
			'meta_key'    => '_pronamic_gateway_ing_kassa_compleet_api_key',
			'title'       => _x( 'API Key', 'ing_kassa_compleet', 'pronamic_ideal' ),
			'type'        => 'text',
			'classes'     => array( 'regular-text', 'code' ),
			'methods'     => array( 'ing_kassa_compleet' ),
			'tooltip'     => sprintf(
				'%s %s.',
				__( 'API key', 'pronamic_ideal' ),
				sprintf(
					__( 'as mentioned in the %s dashboard', 'pronamic_ideal' ),
					__( 'ING Kassa Compleet', 'pronamic_ideal' )
				)
			),
		);

		// Transaction feedback
		$fields[] = array(
			'section'     => 'ing_kassa_compleet',
			'methods'     => array( 'ing_kassa_compleet' ),
			'title'       => __( 'Transaction feedback', 'pronamic_ideal' ),
			'type'        => 'description',
			'html'        => sprintf(
				'<span class="dashicons dashicons-warning"></span> %s',
				__( 'Receiving payment status updates needs additional configuration, if not yet completed.', 'pronamic_ideal' )
			),
		);

		// Webhook URL
		$fields[] = array(
			'section'     => 'ing_kassa_compleet_feedback',
			'title'       => __( 'Webhook URL', 'pronamic_ideal' ),
			'type'        => 'text',
			'classes'     => array( 'large-text', 'code' ),
			'value'       => add_query_arg( 'ing_kassa_compleet_webhook', '', home_url( '/' ) ),
			'readonly'    => true,
			'tooltip'     => sprintf(
				__( 'Copy the Webhook URL to the %s dashboard to receive automatic transaction status updates.', 'pronamic_ideal' ),
				__( 'ING Kassa Compleet', 'pronamic_ideal' )
			),
		);

		// Return
		return $fields;
	}
}
