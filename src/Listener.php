<?php
/**
 * Webhook Listener.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet
 */

namespace Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet;

use Pronamic\WordPress\Pay\Plugin;

/**
 * Title: ING Kassa Compleet listener
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Reüel van der Steege
 * @version 2.0.0
 * @since   1.0.1
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

			if ( null === $payment ) {
				return;
			}

			// Add note.
			$note = sprintf(
				/* translators: %s: ING */
				__( 'Webhook requested by %s.', 'pronamic_ideal' ),
				__( 'ING', 'pronamic_ideal' )
			);

			$payment->add_note( $note );

			// Log webhook request.
			do_action( 'pronamic_pay_webhook_log_payment', $payment );

			// Update payment.
			Plugin::update_payment( $payment, false );
		}
	}
}
