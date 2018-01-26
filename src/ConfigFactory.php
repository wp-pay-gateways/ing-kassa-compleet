<?php

namespace Pronamic\WordPress\Pay\Gateways\ING_KassaCompleet;

use Pronamic\WordPress\Pay\Core\GatewayConfigFactory;

/**
 * Title: ING Kassa Compleet config factory
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Reüel van der Steege
 * @version 1.0.0
 * @since 1.0.0
 */
class ConfigFactory extends GatewayConfigFactory {
	public function get_config( $post_id ) {
		$config = new Config();

		$config->api_key = get_post_meta( $post_id, '_pronamic_gateway_ing_kassa_compleet_api_key', true );
		$config->mode    = get_post_meta( $post_id, '_pronamic_gateway_mode', true );

		return $config;
	}
}
