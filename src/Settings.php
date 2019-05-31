<?php
/**
 * Settings.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet
 */

namespace Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet;

use Pronamic\WordPress\Pay\Core\GatewaySettings;
use Pronamic\WordPress\Pay\WebhookManager;

/**
 * Title: ING Kassa Compleet gateway settings
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.0.0
 */
class Settings extends GatewaySettings {
	/**
	 * Settings constructor.
	 */
	public function __construct() {
		add_filter( 'pronamic_pay_gateway_sections', array( $this, 'sections' ) );
		add_filter( 'pronamic_pay_gateway_fields', array( $this, 'fields' ) );
	}

	/**
	 * Settings sections.
	 *
	 * @param array $sections Sections.
	 *
	 * @return array
	 */
	public function sections( array $sections ) {
		// ING Kassa Compleet.
		$sections['ing_kassa_compleet'] = array(
			'title'       => __( 'ING Kassa Compleet', 'pronamic_ideal' ),
			'methods'     => array( 'ing_kassa_compleet' ),
			'description' => sprintf(
				/* translators: 1: ING */
				__( 'Account details are provided by %1$s after registration. These settings need to match with the %1$s dashboard.', 'pronamic_ideal' ),
				__( 'ING', 'pronamic_ideal' )
			),
		);

		// Transaction feedback.
		$sections['ing_kassa_compleet_feedback'] = array(
			'title'       => __( 'Transaction feedback', 'pronamic_ideal' ),
			'methods'     => array( 'ing_kassa_compleet' ),
			'description' => sprintf(
				/* translators: %s: ING Kassa Compleet */
				__( 'Set the Webhook URL in the %s dashboard to receive automatic transaction status updates.', 'pronamic_ideal' ),
				__( 'ING Kassa Compleet', 'pronamic_ideal' )
			),
			'features'    => Gateway::get_supported_features(),
		);

		// Return sections.
		return $sections;
	}

	/**
	 * Settings fields.
	 *
	 * @param array $fields Fields.
	 *
	 * @return array
	 */
	public function fields( array $fields ) {
		// API Key.
		$fields[] = array(
			'filter'   => FILTER_SANITIZE_STRING,
			'section'  => 'ing_kassa_compleet',
			'meta_key' => '_pronamic_gateway_ing_kassa_compleet_api_key',
			'title'    => _x( 'API Key', 'ing_kassa_compleet', 'pronamic_ideal' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
			'methods'  => array( 'ing_kassa_compleet' ),
			'tooltip'  => sprintf(
				'%s %s.',
				__( 'API key', 'pronamic_ideal' ),
				sprintf(
					/* translators: %s: ING Kassa Compleet */
					__( 'as mentioned in the %s dashboard', 'pronamic_ideal' ),
					__( 'ING Kassa Compleet', 'pronamic_ideal' )
				)
			),
		);

		// Transaction feedback.
		$fields[] = array(
			'section'  => 'ing_kassa_compleet',
			'methods'  => array( 'ing_kassa_compleet' ),
			'title'    => __( 'Transaction feedback', 'pronamic_ideal' ),
			'type'     => 'description',
			'html'     => __( 'Receiving payment status updates needs additional configuration.', 'pronamic_ideal' ),
			'features' => Gateway::get_supported_features(),
		);

		// Webhook URL.
		$fields[] = array(
			'section'  => 'ing_kassa_compleet_feedback',
			'title'    => __( 'Webhook URL', 'pronamic_ideal' ),
			'type'     => 'text',
			'classes'  => array( 'large-text', 'code' ),
			'value'    => add_query_arg( 'ing_kassa_compleet_webhook', '', home_url( '/' ) ),
			'readonly' => true,
			'tooltip'  => sprintf(
				/* translators: %s: ING Kassa Compleet */
				__( 'Copy the Webhook URL to the %s dashboard to receive automatic transaction status updates.', 'pronamic_ideal' ),
				__( 'ING Kassa Compleet', 'pronamic_ideal' )
			),
		);

		// Webhook status.
		$fields[] = array(
			'section'  => 'ing_kassa_compleet_feedback',
			'methods'  => array( 'ing_kassa_compleet' ),
			'title'    => __( 'Status', 'pronamic_ideal' ),
			'type'     => 'description',
			'callback' => array( $this, 'feedback_status' ),
		);

		// Return fields.
		return $fields;
	}

	/**
	 * Transaction feedback status.
	 *
	 * @param array $field Settings field.
	 */
	public function feedback_status( $field ) {
		$features = Gateway::get_supported_features();

		WebhookManager::settings_status( $field, $features );
	}
}
