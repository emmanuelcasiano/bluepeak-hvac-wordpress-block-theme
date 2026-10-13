<?php
/**
 * Empties the page cache when content that appears on many pages changes.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Cache;

use BluePeakClimate\Core\Offers;
use BluePeakClimate\Core\Reviews;
use BluePeakClimate\Core\Services;
use BluePeakClimate\Core\Settings\Settings;

defined( 'ABSPATH' ) || exit;

/**
 * A page cache serves a saved copy of each page, so a change to an offer, review, service or to Site Settings would not
 * show until the copy expires. Offers, reviews and services have no page of their own (they are listed on /offers/,
 * /reviews/, the home page and the services pages), and the phone number from Site Settings is printed on every page,
 * so the whole cache is emptied instead of guessing which pages are affected. These changes are rare, so the cost is low.
 *
 * It only talks to WP Super Cache, through its documented wp_cache_clear_cache(), and does nothing when that plugin is
 * not active. Another cache plugin (the live host's own, for example) needs its own purge call added in clear().
 *
 * An offer that expires by date is hidden by Offers\Expiry at the next request that is not served from the cache, so the
 * longest an expired offer can stay visible is the cache lifetime (WP Super Cache keeps a page for 30 minutes by default).
 */
final class Flush {

	/**
	 * Hooks the clear into content and settings changes.
	 */
	public function register(): void {
		add_action( 'transition_post_status', array( $this, 'on_status_change' ), 10, 3 );
		add_action( 'update_option_' . Settings::OPTION, array( $this, 'clear' ) );
		add_action( 'add_option_' . Settings::OPTION, array( $this, 'clear' ) );
	}

	/**
	 * Post types whose changes show on pages other than their own.
	 *
	 * @return string[]
	 */
	public static function post_types(): array {
		return array(
			Services\PostTypes::POST_TYPE,
			Reviews\PostTypes::POST_TYPE,
			Offers\PostTypes::POST_TYPE,
		);
	}

	/**
	 * Clears when one of our post types is saved while published, published, unpublished, trashed or restored.
	 * Drafts and autosaves of a draft change nothing a visitor can see.
	 *
	 * @param string   $new_status New post status.
	 * @param string   $old_status Old post status.
	 * @param \WP_Post $post       The post.
	 */
	public function on_status_change( $new_status, $old_status, $post ): void {
		if ( ! is_object( $post ) || ! in_array( $post->post_type, self::post_types(), true ) ) {
			return;
		}

		if ( 'publish' !== $new_status && 'publish' !== $old_status ) {
			return;
		}

		$this->clear();
	}

	/**
	 * Empties the WP Super Cache cache, when that plugin is active.
	 */
	public function clear(): void {
		if ( function_exists( 'wp_cache_clear_cache' ) ) {
			wp_cache_clear_cache();
		}
	}
}
