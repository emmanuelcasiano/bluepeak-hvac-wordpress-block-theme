<?php
/**
 * Title: Page not found
 * Slug: blue-peak-climate/not-found
 * Inserter: no
 *
 * Content of the 404 template. Templates are plain HTML and can't be translated or build URLs,
 * so this lives in a pattern.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

$search_attributes = serialize_block_attributes(
	array(
		'label'      => __( 'Search', 'blue-peak-climate' ),
		'showLabel'  => false,
		'buttonText' => __( 'Search', 'blue-peak-climate' ),
	)
);

$business = bpc_theme_placeholder_business();

$call_label = sprintf(
	/* translators: %s: phone number. */
	__( 'Call %s', 'blue-peak-climate' ),
	$business['phone']
);
?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
	<!-- wp:paragraph {"className":"bpc-eyebrow","fontSize":"x-small"} -->
	<p class="bpc-eyebrow has-x-small-font-size"><?php esc_html_e( 'Error 404', 'blue-peak-climate' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":1} -->
	<h1 class="wp-block-heading"><?php esc_html_e( 'This page could not be found', 'blue-peak-climate' ); ?></h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"fontSize":"large"} -->
	<p class="has-large-font-size"><?php esc_html_e( 'The link may be old or the page may have moved. Search the site, or go back to the home page.', 'blue-peak-climate' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:search <?php echo $search_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> /-->

	<!-- wp:buttons -->
	<div class="wp-block-buttons">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'blue-peak-climate' ); ?></a></div>
		<!-- /wp:button -->

		<!-- wp:button {"className":"is-style-outline"} -->
		<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $business['phone_href'] ); ?>"><?php echo esc_html( $call_label ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
