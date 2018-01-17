<?php

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
class Pronamic_WP_Pay_Gateways_ING_KassaCompleet_Config extends Pronamic_WP_Pay_GatewayConfig {
	public $api_key;

	public function get_gateway_class() {
		return 'Pronamic_WP_Pay_Gateways_ING_KassaCompleet_Gateway';
	}
}
