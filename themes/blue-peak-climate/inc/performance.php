<?php
/**
 * Page speed: fewer render-blocking requests and earlier fonts.
 *
 * Images (WebP, sizes) and page caching are handled by the Converter for Media and WP Super Cache plugins, set up by
 * ai/scripts/configure-performance.php. This file only covers what the theme controls.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lets WordPress print the theme's small stylesheets inside the page instead of as separate files. Each file is a
 * request the browser must finish before it can show anything, which on a phone costs more than the extra bytes. The
 * page cache keeps the larger HTML cheap to serve. WordPress rewrites the relative url() paths when it inlines a file,
 * and only inlines while the total stays under the limit (40 KB by default), smallest files first. The block styles a page
 * uses and ours come to about 100 KB uncompressed (about 20 KB sent), so the limit is raised below to fit them all.
 *
 * @param string $handle Stylesheet handle.
 * @param string $file   Path to the file inside the theme.
 */
function bpch_theme_mark_style_inlinable( $handle, $file ) {
	$path = get_theme_file_path( $file );

	if ( is_readable( $path ) ) {
		wp_style_add_data( $handle, 'path', $path );
	}
}

/**
 * Adds the file path to each of the theme's stylesheets once they are registered.
 */
function bpch_theme_mark_styles_inlinable() {
	bpch_theme_mark_style_inlinable( 'blue-peak-climate', 'style.css' );
	bpch_theme_mark_style_inlinable( 'blue-peak-climate-motion', 'assets/css/motion.css' );
	bpch_theme_mark_style_inlinable( 'blue-peak-climate-consent', 'assets/css/consent.css' );
	bpch_theme_mark_style_inlinable( 'blue-peak-climate-forms', 'assets/css/forms.css' );
}
// After inc/motion.php (priority 10) has enqueued its file, and before wp_head prints the styles.
add_action( 'wp_enqueue_scripts', 'bpch_theme_mark_styles_inlinable', 20 );
// The consent and form stylesheets are queued on wp_head at priority 1 (see inc/consent.php and inc/forms.php).
add_action( 'wp_head', 'bpch_theme_mark_styles_inlinable', 2 );

/**
 * Raises the total size of inlined stylesheets so the block styles and ours all fit.
 *
 * @param int $limit Size in bytes.
 * @return int
 */
function bpch_theme_styles_inline_size_limit( $limit ) {
	return max( (int) $limit, 150000 );
}
add_filter( 'styles_inline_size_limit', 'bpch_theme_styles_inline_size_limit' );

/**
 * Starts loading the two body fonts with the page. The browser would otherwise find them only after it has read the
 * stylesheet, which is a second wave of requests. Only the Latin files are preloaded (the Latin Extended files are used
 * only for the rare characters in their unicode range), and a font is only worth preloading when the page uses it,
 * which every page of this theme does. `crossorigin` is required for fonts even on the same origin.
 */
function bpch_theme_preload_fonts() {
	foreach ( array( 'outfit-latin.woff2', 'dm-sans-latin.woff2' ) as $font ) {
		if ( ! is_readable( get_theme_file_path( 'assets/fonts/' . $font ) ) ) {
			continue;
		}

		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( get_theme_file_uri( 'assets/fonts/' . $font ) )
		);
	}
}
add_action( 'wp_head', 'bpch_theme_preload_fonts', 1 );

/**
 * Lets only the first image of a page load straight away. WordPress treats the first three images in the content as
 * possibly above the fold and does not lazy-load them. In this theme the first photo is the hero or the intro photo
 * (core also gives it `fetchpriority="high"`); the second and third sit lower down, and loading them early delays the
 * photo the visitor sees first. Everything after the first is lazy-loaded. This runs when the page is rendered, so
 * pages saved in the database are covered without re-saving them.
 *
 * @return int
 */
function bpch_theme_omit_loading_attr_threshold() {
	return 1;
}
add_filter( 'wp_omit_loading_attr_threshold', 'bpch_theme_omit_loading_attr_threshold' );

/**
 * Loads jQuery and the Fluent Forms script without blocking the first paint. Only pages with a Fluent Forms form load
 * jQuery at all (the theme and the blocks do not use it). WordPress applies `defer` to a script only when every script
 * that depends on it can wait too, and falls back to blocking otherwise, so the form script is marked as well. Deferred
 * scripts run in order after the page is parsed and before DOMContentLoaded, which is when Fluent Forms starts.
 */
function bpch_theme_defer_jquery() {
	foreach ( array( 'jquery-core', 'jquery-migrate', 'fluent-form-submission' ) as $handle ) {
		if ( wp_script_is( $handle, 'registered' ) ) {
			wp_scripts()->add_data( $handle, 'strategy', 'defer' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'bpch_theme_defer_jquery', 100 );
