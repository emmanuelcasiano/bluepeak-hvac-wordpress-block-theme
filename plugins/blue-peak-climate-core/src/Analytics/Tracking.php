<?php
/**
 * Loads the GA4 click-to-call and form-submit tracking script.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Analytics;

defined( 'ABSPATH' ) || exit;

/**
 * Prints no tag and holds no measurement ID: Site Kit owns GA4 and defines the global gtag(). This only adds a small
 * script module that calls gtag() for two events, so it works with whatever tag setup the client ends up with and
 * stays quiet when none exists. The module is the same for every visitor, so page caching is safe.
 */
final class Tracking {

	public const HANDLE = 'bpch-analytics';

	/**
	 * Hooks the enqueue into the front end.
	 */
	public function register(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Enqueues the script module (WordPress 6.5+, the plugin already needs 6.8).
	 */
	public function enqueue(): void {
		wp_enqueue_script_module(
			self::HANDLE,
			BPCH_CORE_URL . 'assets/front/analytics.js',
			array(),
			BPCH_CORE_VERSION
		);
	}
}
