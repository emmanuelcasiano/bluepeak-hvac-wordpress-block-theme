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
?>
<!-- wp:group {"align":"full","className":"bpch-page-intro","backgroundColor":"mist","style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|8"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-page-intro has-mist-background-color has-background" style="padding-top:var(--wp--preset--spacing--8);padding-bottom:var(--wp--preset--spacing--8)">
	<!-- wp:group {"className":"bpch-section__head","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
	<div class="wp-block-group bpch-section__head">
		<!-- wp:paragraph {"className":"bpch-eyebrow","fontSize":"x-small"} -->
		<p class="bpch-eyebrow has-x-small-font-size"><?php esc_html_e( 'Services', 'blue-peak-climate' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"level":1} -->
		<h1 class="wp-block-heading"><?php esc_html_e( 'Heating, cooling, and electrical work for your home', 'blue-peak-climate' ); ?></h1>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"fontSize":"large"} -->
		<p class="has-large-font-size"><?php esc_html_e( "Twenty-two services from one crew. Pick the one you need, or call and describe the problem. We'll figure out the rest.", 'blue-peak-climate' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
