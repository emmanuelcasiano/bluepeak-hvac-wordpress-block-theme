<?php
/**
 * Title: Steps
 * Slug: blue-peak-climate/steps
 * Categories: blue-peak-climate
 * Description: Three numbered steps that explain what happens after a customer calls.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"bpch-section bpch-section--steps","style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-section bpch-section--steps" style="padding-top:var(--wp--preset--spacing--9);padding-bottom:var(--wp--preset--spacing--9)">
	<!-- wp:group {"className":"bpch-section__head","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
	<div class="wp-block-group bpch-section__head">
		<!-- wp:paragraph {"className":"bpch-eyebrow","fontSize":"x-small"} -->
		<p class="bpch-eyebrow has-x-small-font-size"><?php esc_html_e( 'How a visit works', 'blue-peak-climate' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:heading -->
		<h2 class="wp-block-heading"><?php esc_html_e( 'What happens after you call', 'blue-peak-climate' ); ?></h2>
		<!-- /wp:heading -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"bpch-steps","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"stretch"}} -->
	<div class="wp-block-group bpch-steps">
		<!-- wp:group {"className":"bpch-step","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-step">
			<!-- wp:group {"className":"bpch-step__top","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
			<div class="wp-block-group bpch-step__top">
				<!-- wp:paragraph {"className":"bpch-step__number"} -->
				<p class="bpch-step__number"><?php esc_html_e( '01', 'blue-peak-climate' ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:group {"className":"bpch-mark bpch-mark--soft bpch-mark--phone","lock":{"move":true,"remove":true}} -->
				<div class="wp-block-group bpch-mark bpch-mark--soft bpch-mark--phone"></div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->

			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( "Tell us what's wrong", 'blue-peak-climate' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Call or book online. We ask a few questions about your system and give you an arrival window.', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"bpch-step","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-step">
			<!-- wp:group {"className":"bpch-step__top","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
			<div class="wp-block-group bpch-step__top">
				<!-- wp:paragraph {"className":"bpch-step__number"} -->
				<p class="bpch-step__number"><?php esc_html_e( '02', 'blue-peak-climate' ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:group {"className":"bpch-mark bpch-mark--soft bpch-mark--clipboard","lock":{"move":true,"remove":true}} -->
				<div class="wp-block-group bpch-mark bpch-mark--soft bpch-mark--clipboard"></div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->

			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( 'Get a written price', 'blue-peak-climate' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Your technician finds the problem and explains your options. You see the price before any work starts.', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"bpch-step","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-step">
			<!-- wp:group {"className":"bpch-step__top","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
			<div class="wp-block-group bpch-step__top">
				<!-- wp:paragraph {"className":"bpch-step__number"} -->
				<p class="bpch-step__number"><?php esc_html_e( '03', 'blue-peak-climate' ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:group {"className":"bpch-mark bpch-mark--soft bpch-mark--wrench","lock":{"move":true,"remove":true}} -->
				<div class="wp-block-group bpch-mark bpch-mark--soft bpch-mark--wrench"></div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->

			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( 'We fix it and clean up', 'blue-peak-climate' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'We make the repair, run the system with you, and leave the work area as clean as we found it.', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
