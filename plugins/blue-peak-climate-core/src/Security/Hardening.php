<?php
/**
 * Small hardening rules that Wordfence does not cover.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Security;

defined( 'ABSPATH' ) || exit;

/**
 * Wordfence (set up by ai/scripts/configure-security.php) covers login limits, two-factor login, the firewall, the
 * malware scan and username discovery (?author=N, the REST users route and oEmbed). This class adds only what it does
 * not do, as plain hooks, so the rules stay when Wordfence is switched off or replaced:
 *
 * - XML-RPC is turned off. The site has no use for it (no mobile app, no Jetpack) and it is a common route for password
 *   guessing and for pingback abuse.
 * - The WordPress version is left out of the page head and the feeds. Wordfence's own option for this renames readme.html
 *   in the WordPress folder, which is core, so it is not used.
 * - WordPress sends three response headers that block content-type guessing, framing by other sites and long referrers,
 *   through `wp_headers`. That covers every page WordPress builds (first visit, logged-in staff, form posts, 404s), but NOT
 *   a copy served from WP Super Cache's static files, which carry no headers (checked on this site: the cached /about/
 *   answered without them). The same three headers must therefore also be set in the live web server config
 *   (ai/security-notes.md has the lines).
 * - The theme and plugin file editor in WP Admin is turned off, as `DISALLOW_FILE_EDIT` in wp-config.php would. The
 *   constant stays the developer's to set there (it wins when set first); this is the safety net for a site where it is missing.
 *
 * Left out on purpose: a Content-Security-Policy (the consent banner and Site Kit print inline scripts, so a policy that
 * works needs nonces and testing on the live domain) and Strict-Transport-Security (it is sticky and only right once the
 * live site is on HTTPS, so it belongs to the host's server config).
 */
final class Hardening {

	/**
	 * Hooks the rules.
	 */
	public function register(): void {
		add_filter( 'xmlrpc_enabled', '__return_false' );
		add_filter( 'xmlrpc_methods', '__return_empty_array' );
		add_filter( 'the_generator', '__return_empty_string' );
		add_filter( 'wp_headers', array( $this, 'filter_headers' ) );

		$this->lock_file_editor();
	}

	/**
	 * Response headers the front end sends.
	 *
	 * @return array<string,string>
	 */
	public static function headers(): array {
		return array(
			'X-Content-Type-Options' => 'nosniff',
			'X-Frame-Options'        => 'SAMEORIGIN',
			'Referrer-Policy'        => 'strict-origin-when-cross-origin',
		);
	}

	/**
	 * Adds the security headers (never replacing one that something else already set) and drops X-Pingback, which
	 * points to xmlrpc.php.
	 *
	 * @param mixed $headers Headers WordPress is about to send.
	 * @return mixed
	 */
	public function filter_headers( $headers ) {
		if ( ! is_array( $headers ) ) {
			return $headers;
		}

		/**
		 * Filters the security headers added to front-end responses.
		 *
		 * @param array<string,string> $security_headers Header name => value.
		 */
		$security_headers = apply_filters( 'bpch_core_security_headers', self::headers() );

		if ( is_array( $security_headers ) ) {
			foreach ( $security_headers as $name => $value ) {
				if ( ! isset( $headers[ $name ] ) ) {
					$headers[ $name ] = $value;
				}
			}
		}

		unset( $headers['X-Pingback'] );

		return $headers;
	}

	/**
	 * Turns off the theme and plugin file editor, unless wp-config.php already decided.
	 */
	public function lock_file_editor(): void {
		if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound -- WordPress core's own constant, deliberately unprefixed.
			define( 'DISALLOW_FILE_EDIT', true );
		}
	}
}
