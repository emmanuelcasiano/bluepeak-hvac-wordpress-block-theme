<?php
/**
 * Title: Page intro
 * Slug: blue-peak-climate/page-intro
 * Categories: blue-peak-climate
 * Description: Opening block for inner pages: a small label, the page title and a short lead.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

echo bpch_theme_page_intro( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helper.
	__( 'Services', 'blue-peak-climate' ),
	__( 'Heating, cooling, and electrical work for your home', 'blue-peak-climate' ),
	__( "Twenty-two services from one crew. Pick the one you need, or call and describe the problem. We'll figure out the rest.", 'blue-peak-climate' )
);
