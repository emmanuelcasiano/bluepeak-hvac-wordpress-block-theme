<?php
/**
 * Theme setup. Design lives in theme.json; site features belong in the blue-peak-climate-core plugin, not here.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Loads style.css inside the editor iframe so the editor matches the front end.
 */
function bpc_theme_setup() {
	add_editor_style( 'style.css' );
}
add_action( 'after_setup_theme', 'bpc_theme_setup' );

/**
 * Loads style.css on the front end, versioned so caches refresh on theme updates.
 */
function bpc_theme_enqueue_styles() {
	wp_enqueue_style( 'blue-peak-climate', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'bpc_theme_enqueue_styles' );

/**
 * Turns off the Font Library so editors can only use the theme's own fonts.
 * theme.json has no switch for this, so it is done through the editor settings.
 *
 * @param array $settings Block editor settings.
 * @return array
 */
function bpc_theme_disable_font_library( $settings ) {
	$settings['fontLibraryEnabled'] = false;
	return $settings;
}
add_filter( 'block_editor_settings_all', 'bpc_theme_disable_font_library' );

/**
 * Groups this theme's patterns under one inserter category.
 */
function bpc_theme_register_pattern_category() {
	register_block_pattern_category(
		'blue-peak-climate',
		array( 'label' => __( 'Blue Peak Climate', 'blue-peak-climate' ) )
	);
}
add_action( 'init', 'bpc_theme_register_pattern_category' );
