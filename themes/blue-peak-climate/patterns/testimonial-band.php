<?php
/**
 * Title: Testimonial band
 * Slug: blue-peak-climate/testimonial-band
 * Categories: blue-peak-climate
 * Description: Navy band with a photo, five stars and one customer quote. The review carousel replaces the single quote later.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"bpch-band bpch-band--testimonial","backgroundColor":"navy","textColor":"white","style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-band bpch-band--testimonial has-white-color has-navy-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--9);padding-bottom:var(--wp--preset--spacing--9)">
	<!-- wp:group {"className":"bpch-testimonial","style":{"spacing":{"blockGap":"var:preset|spacing|8"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
	<div class="wp-block-group bpch-testimonial">
		<?php echo bpch_theme_photo_cover( 'tech-wide', 'wide', 'navy-line' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helper. ?>

		<!-- wp:group {"className":"bpch-testimonial__copy","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-testimonial__copy">
			<!-- wp:paragraph {"className":"bpch-eyebrow bpch-eyebrow--light","fontSize":"x-small"} -->
			<p class="bpch-eyebrow bpch-eyebrow--light has-x-small-font-size"><?php esc_html_e( 'From our customers', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"className":"bpch-rating"} -->
			<p class="bpch-rating"><span aria-hidden="true">★★★★★</span><span class="bpch-sr-only"><?php esc_html_e( '5 out of 5 stars', 'blue-peak-climate' ); ?></span></p>
			<!-- /wp:paragraph -->

			<!-- wp:quote {"className":"bpch-quote"} -->
			<blockquote class="wp-block-quote bpch-quote">
				<!-- wp:paragraph -->
				<p><?php esc_html_e( 'Our AC quit on a Saturday in July with a newborn in the house. BluePeak had a technician here by early afternoon. He showed us the burned-out capacitor, replaced it, and the bill matched what he quoted. The house was cool again by three.', 'blue-peak-climate' ); ?></p>
				<!-- /wp:paragraph -->
				<cite><?php esc_html_e( 'Homeowner, Texas', 'blue-peak-climate' ); ?></cite>
			</blockquote>
			<!-- /wp:quote -->

			<!-- wp:paragraph {"textColor":"on-navy","fontSize":"small"} -->
			<p class="has-on-navy-color has-text-color has-small-font-size"><?php esc_html_e( 'Emergency AC repair', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
