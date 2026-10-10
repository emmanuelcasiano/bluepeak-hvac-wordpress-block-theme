<?php
/**
 * Shared sanitizing helpers.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Support;

defined( 'ABSPATH' ) || exit;

/**
 * Small helpers that features reuse. Pure logic like this is what `/feature test` covers.
 */
final class Sanitize {

	/**
	 * Turns a comma- or newline-separated form value into a clean list of unique strings.
	 *
	 * @param mixed $raw Raw value from $_POST / $_GET (slashed).
	 * @return string[]
	 */
	public static function text_list( $raw ): array {
		if ( ! is_string( $raw ) ) {
			return array();
		}

		$items = preg_split( '/[\r\n,]+/', wp_unslash( $raw ) );
		$items = array_map( 'sanitize_text_field', $items );

		return array_values( array_unique( array_filter( $items, 'strlen' ) ) );
	}
}
