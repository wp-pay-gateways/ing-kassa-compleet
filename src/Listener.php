<?php

namespace Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet;

use Pronamic\WordPress\Pay\Plugin;

/**
 * Title: ING Kassa Compleet listener
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Reüel van der Steege
 * @version 1.0.3
 * @since 1.0.1
 */
class Listener {
	/**
	 * Listen to ING Kassa Compleet webhook requests.
	 */
	public static function listen() {
		if ( ! filter_has_var( INPUT_GET, 'ing_kassa_compleet_webhook' ) ) {
			return;
		}

		$data = json_decode( file_get_contents( 'php://input' ) );

		if ( is_object( $data ) && isset( $data->order_id ) ) {
			$payment = get_pronamic_payment_by_transaction_id( $data->order_id );

			Plugin::update_payment( $payment, false );
		}
	}
}
