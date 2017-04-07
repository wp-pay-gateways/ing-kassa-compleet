<?php

/**
 * Title: ING Kassa Compleet listener
 * Description:
 * Copyright: Copyright (c) 2005 - 2017
 * Company: Pronamic
 *
 * @author Reüel van der Steege
 * @version 1.0.3
 * @since 1.0.1
 */
class Pronamic_WP_Pay_Gateways_ING_KassaCompleet_Listener implements Pronamic_Pay_Gateways_ListenerInterface {
	/**
	 * Listen to ING Kassa Compleet webhook requests.
	 */
	public static function listen() {
		if ( filter_has_var( INPUT_GET, 'ing_kassa_compleet_webhook' ) ) {
			$data = json_decode( file_get_contents( 'php://input' ) );

			if ( is_object( $data ) && isset( $data->order_id ) ) {
				$payment = get_pronamic_payment_by_transaction_id( $data->order_id );

				Pronamic_WP_Pay_Plugin::update_payment( $payment, false );
			}
		}
	}
}
