<?php
/**
 * Title: No results
 * Slug: blue-peak-climate/no-results
 * Inserter: no
 *
 * Message and search field shown when a post list is empty. Templates are plain HTML and can't be
 * translated, so the text lives in a pattern.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

$bpch_search_attributes = serialize_block_attributes(
	array(
		'label'      => __( 'Search', 'blue-peak-climate' ),
		'showLabel'  => false,
		'buttonText' => __( 'Search', 'blue-peak-climate' ),
	)
);
?>
<!-- wp:paragraph -->
<p><?php esc_html_e( 'Nothing was found here yet. Try a search or head back to the home page.', 'blue-peak-climate' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:search <?php echo $bpch_search_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> /-->
