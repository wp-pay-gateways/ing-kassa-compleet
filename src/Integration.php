<?php
/**
 * Integration.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet
 */

namespace Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet;

use Pronamic\WordPress\Pay\Gateways\Common\AbstractIntegration;

/**
 * Title: ING Kassa Compleet integration
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Reüel van der Steege
 * @version 2.0.3
 * @since   1.0.0
 */
class Integration extends AbstractIntegration {
	/**
	 * Integration constructor.
	 */
	public function __construct() {
		$this->id            = 'ing-kassa-compleet';
		$this->name          = 'ING - Kassa Compleet';
		$this->provider      = 'ing';
		$this->product_url   = 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/kassa-compleet/';
		$this->dashboard_url = 'https://portal.kassacompleet.nl/';
		$this->supports      = array(
			'payment_status_request',
			'webhook',
			'webhook_log',
		);

		$this->set_manual_url( __( 'https://www.pronamic.eu/support/how-to-connect-ing-kassa-compleet-with-wordpress-via-pronamic-pay/', 'pronamic_ideal' ) );

		// Actions.
		$function = array( __NAMESPACE__ . '\Listener', 'listen' );

		if ( ! has_action( 'wp_loaded', $function ) ) {
			add_action( 'wp_loaded', $function );
		}
	}

	/**
	 * Get settings fields.
	 *
	 * @return array
	 */
	public function get_settings_fields() {
		$fields = array();

		// API Key.
		$fields[] = array(
			'section'  => 'general',
			'filter'   => FILTER_SANITIZE_STRING,
			'meta_key' => '_pronamic_gateway_ing_kassa_compleet_api_key',
			'title'    => _x( 'API Key', 'ing_kassa_compleet', 'pronamic_ideal' ),
			'type'     => 'text',
			'classes'  => array( 'regular-text', 'code' ),
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

		// Webhook URL.
		$fields[] = array(
			'section'  => 'feedback',
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

		return $fields;
	}
	/**
	 * Get config with specified post ID.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return Config|null
	 */
	public function get_config( $post_id ) {
		$config = new Config();

		$config->api_key = $this->get_meta( $post_id, 'ing_kassa_compleet_api_key' );
		$config->mode    = $this->get_meta( $post_id, 'mode' );

		return $config;
	}

	/**
	 * Get gateway.
	 *
	 * @param int $post_id Post ID.
	 * @return Gateway
	 */
	public function get_gateway( $post_id ) {
		return new Gateway( $this->get_config( $post_id ) );
	}
}
