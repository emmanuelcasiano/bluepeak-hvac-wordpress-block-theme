<?php
/**
 * Title: Blog index
 * Slug: blue-peak-climate/blog-index
 * Categories: blue-peak-climate
 * Inserter: false
 * Description: The blog page: page intro, the list of posts and the closing call to action.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

echo bpch_theme_page_intro( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helper.
	__( 'Blog', 'blue-peak-climate' ),
	__( 'Seasonal tips from the BluePeak crew', 'blue-peak-climate' ),
	__( 'Short notes on what to check, what to leave alone and when to call, for your heating, cooling and wiring.', 'blue-peak-climate' )
);
?>

<!-- wp:pattern {"slug":"blue-peak-climate/blog-list"} /-->
