<?php

namespace Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet;

use Pronamic\WordPress\Pay\Gateways\Common\AbstractIntegration;

/**
 * Title: ING Kassa Compleet integration
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author  Reüel van der Steege
 * @version 2.0.0
 * @since   1.0.0
 */
class Integration extends AbstractIntegration {
	public function __construct() {
		$this->id            = 'ing-kassa-compleet';
		$this->name          = 'ING Kassa Compleet';
		$this->provider      = 'ing';
		$this->product_url   = 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/kassa-compleet/';
		$this->dashboard_url = 'https://portal.kassacompleet.nl/';

		// Actions
		$function = array( __NAMESPACE__ . '\Listener', 'listen' );

		if ( ! has_action( 'wp_loaded', $function ) ) {
			add_action( 'wp_loaded', $function );
		}
	}

	public function get_config_factory_class() {
		return __NAMESPACE__ . '\ConfigFactory';
	}

	public function get_settings_class() {
		return __NAMESPACE__ . '\Settings';
	}

	/**
	 * Get required settings for this integration.
	 *
	 * @see https://github.com/wp-premium/gravityforms/blob/1.9.16/includes/fields/class-gf-field-multiselect.php#L21-L42
	 * @since 1.0.2
	 * @return array
	 */
	public function get_settings() {
		$settings = parent::get_settings();

		$settings[] = 'ing_kassa_compleet';

		return $settings;
	}
}
