<?php
/**
 * Scroll animations: one stylesheet and one script module, front end only.
 *
 * The animations are presentation, so they live in the theme. Nothing is added to the block markup: the script finds the
 * sections by the class names the patterns already use, which means pages saved in the database animate too, with no
 * re-save. A block can opt out with the `bpch-no-motion` class (Advanced > Additional CSS class).
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Loads the motion stylesheet and script on the front end. The editor never gets them, so every block stays visible
 * while editing.
 */
function bpch_theme_enqueue_motion() {
	$version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'blue-peak-climate-motion',
		get_theme_file_uri( 'assets/css/motion.css' ),
		array( 'blue-peak-climate' ),
		$version
	);

	wp_enqueue_script_module(
		'blue-peak-climate-motion',
		get_theme_file_uri( 'assets/js/motion.js' ),
		array(),
		$version
	);
}
add_action( 'wp_enqueue_scripts', 'bpch_theme_enqueue_motion' );
