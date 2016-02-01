<?php

/**
 * Title: ING Kassa Compleet config factory
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Reüel van der Steege
 * @version 1.0.0
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Gateways_ING_KassaCompleet_ConfigFactory extends Pronamic_WP_Pay_GatewayConfigFactory {
	public function get_config( $post_id ) {
		$config = new Pronamic_WP_Pay_Gateways_ING_KassaCompleet_Config();

		$config->api_key = get_post_meta( $post_id, '_pronamic_gateway_ing_kassa_compleet_api_key', true );
		$config->mode    = get_post_meta( $post_id, '_pronamic_gateway_mode', true );

		return $config;
	}
}
