<?php
/**
 * Cookie consent (Complianz): the footer links and the banner stylesheet.
 *
 * Complianz is a third-party plugin, so nothing here edits it. The theme only reads its public helpers and loads one
 * stylesheet, and every call is safe if the plugin is deactivated (the footer shows no cookie links and no CSS loads).
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether Complianz is active and its consent banner is switched on.
 *
 * @return bool
 */
function bpch_theme_consent_active() {
	return function_exists( 'cmplz_get_option' ) && 'yes' === cmplz_get_option( 'enable_cookie_banner' );
}

/**
 * Footer links for the cookie choice: the Cookie Policy page and a button that opens the banner again.
 *
 * The button uses the `cmplz-manage-consent` class, which Complianz's script listens for on the whole document, so a
 * visitor can change the choice later without the plugin's floating "Manage cookies" tab (hidden on this site).
 *
 * @return string HTML for a Custom HTML block, or an empty string when there is no banner.
 */
function bpch_theme_cookie_links_html() {
	if ( ! bpch_theme_consent_active() ) {
		return '';
	}

	$html = '<div class="bpch-footer__cookies">';

	$policy_url = function_exists( 'cmplz_get_document_url' ) ? cmplz_get_document_url( 'cookie-statement', 'us' ) : '#';
	if ( '#' !== $policy_url ) {
		$html .= '<a href="' . esc_url( $policy_url ) . '">' . esc_html__( 'Cookie Policy', 'blue-peak-climate' ) . '</a>';
	}

	$html .= '<button type="button" class="cmplz-manage-consent bpch-footer__cookie-button" aria-haspopup="dialog">' . esc_html__( 'Manage cookies', 'blue-peak-climate' ) . '</button>';

	return $html . '</div>';
}

/**
 * Loads the banner stylesheet after Complianz's own, so same-weight rules win.
 *
 * It runs on wp_head for the same reason as the form stylesheet (see inc/forms.php): Complianz queues its banner
 * stylesheet while the page is built, and ours must print after it.
 */
function bpch_theme_enqueue_consent_styles() {
	$deps = array();

	foreach ( wp_styles()->queue as $handle ) {
		if ( 0 === strpos( $handle, 'cmplz-banner-' ) ) {
			$deps[] = $handle;
		}
	}

	if ( array() === $deps ) {
		return;
	}

	wp_enqueue_style(
		'blue-peak-climate-consent',
		get_theme_file_uri( 'assets/css/consent.css' ),
		$deps,
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_head', 'bpch_theme_enqueue_consent_styles', 1 );
