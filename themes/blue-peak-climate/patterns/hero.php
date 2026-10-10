<?php
/**
 * Title: Hero
 * Slug: blue-peak-climate/hero
 * Categories: blue-peak-climate
 * Description: Home page opening: headline, two buttons, trust badges, a photo and the 24/7 note.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

$bpch_business        = bpch_theme_business();
$bpch_call_label      = bpch_theme_call_label( $bpch_business['phone'] );
$bpch_call_attributes = bpch_theme_call_button_attributes( array( 'className' => 'is-style-outline' ) );
?>
<!-- wp:group {"align":"full","className":"bpch-hero","backgroundColor":"mist","style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|9"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-hero has-mist-background-color has-background" style="padding-top:var(--wp--preset--spacing--8);padding-bottom:var(--wp--preset--spacing--9)">
	<!-- wp:group {"className":"bpch-hero__inner","style":{"spacing":{"blockGap":"var:preset|spacing|8"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
	<div class="wp-block-group bpch-hero__inner">
		<!-- wp:group {"className":"bpch-hero__copy","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-hero__copy">
			<!-- wp:paragraph {"className":"bpch-eyebrow","fontSize":"x-small"} -->
			<p class="bpch-eyebrow has-x-small-font-size"><?php esc_html_e( 'Heating · Cooling · Electrical · Texas homes', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":1,"className":"bpch-hero__title"} -->
			<h1 class="wp-block-heading bpch-hero__title"><?php esc_html_e( 'Fast, honest heating and air for Texas homes.', 'blue-peak-climate' ); ?></h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"fontSize":"large"} -->
			<p class="has-large-font-size"><?php esc_html_e( 'BluePeak Climate repairs, replaces, and maintains central air, furnaces, heat pumps, and boilers. Call at 2 a.m. in August and a real technician answers.', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons {"className":"bpch-cta-row"} -->
			<div class="wp-block-buttons bpch-cta-row">
				<!-- wp:button -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Book a service visit', 'blue-peak-climate' ); ?></a></div>
				<!-- /wp:button -->

				<!-- wp:button <?php echo $bpch_call_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
				<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $bpch_business['phone_href'] ); ?>"><?php echo esc_html( $bpch_call_label ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->

			<!-- wp:pattern {"slug":"blue-peak-climate/trust-badges"} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"bpch-hero__media","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-hero__media">
			<?php echo bpch_theme_photo_cover( 'hero-house', 'hero' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helper. ?>

			<!-- wp:group {"className":"bpch-hero__note","backgroundColor":"white","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
			<div class="wp-block-group bpch-hero__note has-white-background-color has-background">
				<!-- wp:paragraph {"className":"bpch-hero__figure"} -->
				<p class="bpch-hero__figure"><?php esc_html_e( '24/7', 'blue-peak-climate' ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"textColor":"ink","fontSize":"small"} -->
				<p class="has-ink-color has-text-color has-small-font-size"><?php esc_html_e( 'Emergency repairs on nights, weekends, and holidays. An on-call technician picks up, not an answering service.', 'blue-peak-climate' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
