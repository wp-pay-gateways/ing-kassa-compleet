<?php

namespace Pronamic\WordPress\Pay\Gateways\ING\KassaCompleet;

use Pronamic\WordPress\Pay\Core\Statuses as Core_Statuses;

/**
 * Title: ING Kassa Compleet statuses constants
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Reüel van der Steege
 * @version 2.0.0
 * @since   1.0.0
 */
class Statuses {
	/**
	 * Completed
	 *
	 * @var string
	 */
	const COMPLETED = 'completed';

	/**
	 * Error
	 *
	 * @since 1.0.5
	 * @var string
	 */
	const ERROR = 'error';

	/**
	 * Pending
	 *
	 * @var string
	 */
	const PENDING = 'pending';

	/**
	 * Processing
	 *
	 * @var string
	 */
	const PROCESSING = 'processing';

	// @todo verify cancelled status
	/**
	 * Cancelled
	 *
	 * @var string
	 */
	const CANCELLED = 'cancelled';

	/**
	 * Success
	 *
	 * @var string
	 */
	const SUCCESS = 'Success';

	/**
	 * Transform an ING Kassa Compleet status to a global status
	 *
	 * @param string $status
	 *
	 * @return string|null
	 */
	public static function transform( $status ) {
		switch ( $status ) {
			case self::ERROR:
				return Core_Statuses::FAILURE;

			case self::PENDING:
			case self::PROCESSING:
				return Core_Statuses::OPEN;

			case self::CANCELLED:
				return Core_Statuses::CANCELLED;

			case self::COMPLETED:
			case self::SUCCESS:
				return Core_Statuses::SUCCESS;

			default:
				return null;
		}
	}
}
