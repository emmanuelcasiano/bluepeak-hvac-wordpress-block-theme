<?php
/**
 * Title: Symptom picker
 * Slug: blue-peak-climate/symptom-picker
 * Categories: blue-peak-climate
 * Description: "What's your system doing?" with five tabs: likely causes, what we would do, a tip and call buttons.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"bpch-section bpch-section--picker","style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-section bpch-section--picker" style="padding-top:var(--wp--preset--spacing--9);padding-bottom:var(--wp--preset--spacing--9)">
	<!-- wp:group {"className":"bpch-section__head","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
	<div class="wp-block-group bpch-section__head">
		<!-- wp:paragraph {"className":"bpch-eyebrow","fontSize":"x-small"} -->
		<p class="bpch-eyebrow has-x-small-font-size"><?php esc_html_e( 'Quick check', 'blue-peak-climate' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:heading -->
		<h2 class="wp-block-heading"><?php esc_html_e( "What's your system doing?", 'blue-peak-climate' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"fontSize":"large"} -->
		<p class="has-large-font-size"><?php esc_html_e( "Pick the closest match. You'll see what usually causes it, what we'd do, and what you can try before we arrive.", 'blue-peak-climate' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:bpch/symptom-picker /-->
</div>
<!-- /wp:group -->
