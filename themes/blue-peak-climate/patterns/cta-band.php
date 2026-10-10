<?php
/**
 * Title: CTA band
 * Slug: blue-peak-climate/cta-band
 * Categories: blue-peak-climate
 * Description: Orange band that asks the visitor to book a visit or call.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

$bpch_business        = bpch_theme_business();
$bpch_call_label      = bpch_theme_call_label( $bpch_business['phone'] );
$bpch_call_attributes = bpch_theme_call_button_attributes( array( 'className' => 'is-style-outline' ) );
?>
<!-- wp:group {"align":"full","className":"bpch-band bpch-band--cta","backgroundColor":"orange","textColor":"ink","style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|8"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-band bpch-band--cta has-ink-color has-orange-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--8);padding-bottom:var(--wp--preset--spacing--8)">
	<!-- wp:group {"className":"bpch-band__inner","style":{"spacing":{"blockGap":"var:preset|spacing|7"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group bpch-band__inner">
		<!-- wp:group {"className":"bpch-band__copy","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-band__copy">
			<!-- wp:heading -->
			<h2 class="wp-block-heading"><?php esc_html_e( 'Get your home comfortable again', 'blue-peak-climate' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"ink"} -->
			<p class="has-ink-color has-text-color"><?php esc_html_e( 'Book a repair or a tune-up, or ask for a free quote on a new system.', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:buttons {"className":"bpch-cta-row"} -->
		<div class="wp-block-buttons bpch-cta-row">
			<!-- wp:button {"backgroundColor":"navy","textColor":"white","borderColor":"navy"} -->
			<div class="wp-block-button"><a class="wp-block-button__link has-white-color has-navy-background-color has-text-color has-background has-border-color has-navy-border-color wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Book a service visit', 'blue-peak-climate' ); ?></a></div>
			<!-- /wp:button -->

			<!-- wp:button <?php echo $bpch_call_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
			<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $bpch_business['phone_href'] ); ?>"><?php echo esc_html( $bpch_call_label ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
