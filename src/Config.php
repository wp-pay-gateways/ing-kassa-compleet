<?php

namespace Pronamic\WordPress\Pay\Gateways\ING_KassaCompleet;

use Pronamic\WordPress\Pay\Core\GatewayConfig;

/**
 * Title: ING Kassa Compleet config
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Reüel van der Steege
 * @version 1.0.0
 * @since 1.0.0
 */
class Config extends GatewayConfig {
	public $api_key;

	public function get_gateway_class() {
		return __NAMESPACE__ . '\Gateway';
	}
}
