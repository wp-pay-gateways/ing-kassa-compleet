<?php

class Pronamic_WP_Pay_Gateways_ING_KassaCompleet_Integration extends Pronamic_WP_Pay_Gateways_AbstractIntegration {
	public function __construct() {
		$this->id            = 'ing-kassa-compleet';
		$this->name          = 'ING Kassa Compleet';
		$this->provider      = 'ing';
		$this->dashboard_url = 'https://portal.kassacompleet.nl/';
	}

	public function get_config_factory_class() {
		return 'Pronamic_WP_Pay_Gateways_ING_KassaCompleet_ConfigFactory';
	}

	public function get_config_class() {
		return 'Pronamic_WP_Pay_Gateways_ING_KassaCompleet_Config';
	}

	public function get_settings_class() {
		return 'Pronamic_WP_Pay_Gateways_ING_KassaCompleet_Settings';
	}

	public function get_gateway_class() {
		return 'Pronamic_WP_Pay_Gateways_ING_KassaCompleet_Gateway';
	}
}
