<?php

/**
 * Title: ING Kassa Compleet statuses constants
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Reüel van der Steege
 * @version 1.0.0
 * @since 1.0.0
 */
class Pronamic_WP_Pay_ING_KassaCompleet_Statuses {
	/**
	 * Success
	 *
	 * @var string
	 */
	const COMPLETED = 'completed';

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
