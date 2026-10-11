<?php
/**
 * Offer expiry: the rule, and the hooks that hide expired offers on the front end.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Offers;

defined( 'ABSPATH' ) || exit;

/**
 * An offer carries an `expires_on` date (Y-m-d, in the site's timezone). It is valid through the end of that day and
 * hidden from the next day on. An offer with no date never expires.
 *
 * The hiding is done in the query (pre_get_posts), not by a scheduled job, so an offer disappears the moment its day
 * is over even if WP-Cron is late or switched off. Nothing is deleted or changed: the office admin still sees expired
 * offers in WP Admin and can extend one by changing its date.
 */
final class Expiry {

	/**
	 * Date format stored in the meta and used by <input type="date">.
	 */
	public const FORMAT = 'Y-m-d';

	/**
	 * Hooks the front-end filters.
	 */
	public function register(): void {
		add_action( 'pre_get_posts', array( $this, 'hide_expired' ) );
		add_filter( 'rest_' . PostTypes::POST_TYPE . '_query', array( $this, 'hide_expired_in_rest' ) );
	}

	/**
	 * Is this date in the past? A blank or malformed date never expires, so a typo can't hide an offer by mistake.
	 *
	 * @param string $expires_on Stored date (Y-m-d) or an empty string.
	 * @param string $today      Today's date (Y-m-d) in the site's timezone.
	 * @return bool
	 */
	public static function is_expired( string $expires_on, string $today ): bool {
		return self::is_valid_date( $expires_on ) && $expires_on < $today;
	}

	/**
	 * Is this a real calendar date written as Y-m-d? (2026-02-30 is not.)
	 *
	 * @param string $value Text to check.
	 * @return bool
	 */
	public static function is_valid_date( string $value ): bool {
		$date = \DateTimeImmutable::createFromFormat( '!' . self::FORMAT, $value );

		return false !== $date && $date->format( self::FORMAT ) === $value;
	}

	/**
	 * Today's date in the site's timezone (Settings > General), not the server's.
	 *
	 * @return string
	 */
	public static function today(): string {
		return wp_date( self::FORMAT );
	}

	/**
	 * Adds the "not expired" condition to a meta query, keeping any condition that was already there.
	 *
	 * @param mixed  $existing The query's current meta_query (usually empty).
	 * @param string $today    Today's date (Y-m-d).
	 * @return array<int|string, mixed>
	 */
	public static function meta_query( $existing, string $today ): array {
		$not_expired = array(
			'relation' => 'OR',
			array(
				'key'     => PostTypes::META_EXPIRES,
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => PostTypes::META_EXPIRES,
				'value'   => '',
				'compare' => '=',
			),
			array(
				'key'     => PostTypes::META_EXPIRES,
				'value'   => $today,
				'compare' => '>=',
				'type'    => 'DATE',
			),
		);

		if ( empty( $existing ) || ! is_array( $existing ) ) {
			return $not_expired;
		}

		return array(
			'relation' => 'AND',
			$existing,
			$not_expired,
		);
	}

	/**
	 * Does this query ask only for offers? Mixed queries (post types "any", or offers plus pages) are left alone.
	 *
	 * @param mixed $post_type The query's post_type value.
	 * @return bool
	 */
	public static function is_offer_query( $post_type ): bool {
		if ( is_string( $post_type ) ) {
			return PostTypes::POST_TYPE === $post_type;
		}

		return is_array( $post_type ) && array( PostTypes::POST_TYPE ) === array_values( array_unique( $post_type ) );
	}

	/**
	 * Hides expired offers from every front-end query: the Offers page, any Query Loop, feeds.
	 *
	 * Skipped in WP Admin so the office sees every offer, and in REST requests, which have their own rule below
	 * (an editor in the block editor sees expired offers, a visitor does not).
	 *
	 * @param \WP_Query $query Query about to run.
	 */
	public function hide_expired( $query ): void {
		if ( is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return;
		}

		if ( ! self::is_offer_query( $query->get( 'post_type' ) ) ) {
			return;
		}

		$query->set( 'meta_query', self::meta_query( $query->get( 'meta_query' ), self::today() ) ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- A handful of offers; the filter is what makes expiry automatic.
	}

	/**
	 * The same rule for the REST list of offers (/wp/v2/offers), for visitors and anyone who can't edit offers.
	 *
	 * @param array<string, mixed> $args WP_Query arguments built from the REST request.
	 * @return array<string, mixed>
	 */
	public function hide_expired_in_rest( $args ) {
		if ( current_user_can( 'edit_others_posts' ) ) {
			return $args;
		}

		$existing = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

		$args['meta_query'] = self::meta_query( $existing, self::today() ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- See hide_expired().

		return $args;
	}
}
