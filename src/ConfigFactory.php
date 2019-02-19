<?php
/**
 * Config Factory.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet
 */

namespace Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet;

use Pronamic\WordPress\Pay\Core\GatewayConfigFactory;

/**
 * Title: ING Kassa Compleet config factory
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Reüel van der Steege
 * @version 2.0.0
 * @since   1.0.0
 */
class ConfigFactory extends GatewayConfigFactory {
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
}
