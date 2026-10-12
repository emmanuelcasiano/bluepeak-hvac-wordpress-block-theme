<?php
/**
 * Title: Testimonial band
 * Slug: blue-peak-climate/testimonial-band
 * Categories: blue-peak-climate
 * Description: Navy band with a photo and the review carousel, which shows the newest published reviews one at a time.
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

			<!-- wp:bpch/review-carousel /-->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
