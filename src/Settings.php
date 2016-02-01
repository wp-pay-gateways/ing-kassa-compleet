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
			'description' => sprintf( __( 'You can find the API key in the <a href="%s" target="_blank">ING Kassa Compleet dashboard</a>.', 'pronamic_ideal' ), 'https://portal.kassacompleet.nl/' ),
		);

		// Return
		return $fields;
	}
}
