<?php

/**
 * Title: ING Kassa Compleet statuses constants
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Reüel van der Steege
 * @version 1.0.5
 * @since 1.0.0
 */
class Pronamic_WP_Pay_ING_KassaCompleet_Statuses {
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

	/////////////////////////////////////////////////

	/**
	 * Transform an ING Kassa Compleet status to a global status
	 *
	 * @param string $status
	 */
	public static function transform( $status ) {
		switch ( $status ) {
			case self::ERROR :
				return Pronamic_WP_Pay_Statuses::FAILURE;

			case self::PENDING :
			case self::PROCESSING :
				return Pronamic_WP_Pay_Statuses::OPEN;

			case self::CANCELLED :
				return Pronamic_WP_Pay_Statuses::CANCELLED;

			case self::COMPLETED :
			case self::SUCCESS :
				return Pronamic_WP_Pay_Statuses::SUCCESS;

			default:
				return null;
		}
	}
}
