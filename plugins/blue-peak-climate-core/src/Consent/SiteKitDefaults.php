<?php
/**
 * Makes Site Kit's Consent Mode default apply to every visitor.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Consent;

defined( 'ABSPATH' ) || exit;

/**
 * Site Kit's Consent Mode starts every storage type as "denied", but only for the European Economic Area and the
 * United Kingdom (a `region` list in the default). Visitors anywhere else, including Texas, would start as "granted".
 * This site is opt-in everywhere (see OptIn), so the region list is dropped and the default applies to all visitors.
 * The cookie banner then raises the right storage types to "granted" through the WP Consent API when a visitor accepts.
 * Uses Site Kit's documented `googlesitekit_consent_defaults` filter, which only runs while Site Kit's Consent Mode is on.
 */
final class SiteKitDefaults {

	/**
	 * Hooks the filter.
	 */
	public function register(): void {
		add_filter( 'googlesitekit_consent_defaults', array( $this, 'for_everyone' ) );
	}

	/**
	 * Removes the region limit from the consent defaults.
	 *
	 * @param mixed $defaults Consent defaults Site Kit prints.
	 * @return mixed
	 */
	public function for_everyone( $defaults ) {
		if ( is_array( $defaults ) ) {
			unset( $defaults['region'] );
		}

		return $defaults;
	}
}
