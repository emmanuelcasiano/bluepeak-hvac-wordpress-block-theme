<?php
/**
 * Title: Emergency band
 * Slug: blue-peak-climate/emergency-band
 * Categories: blue-peak-climate
 * Description: Navy band with the 24/7 figure, a short question and call and request buttons.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

$bpch_business   = bpch_theme_business();
$bpch_call_label = bpch_theme_call_label( $bpch_business['phone'] );

// A white button on the navy band: it is not an outline button, so it carries its own colours.
$bpch_call_attributes = bpch_theme_call_button_attributes(
	array(
		'backgroundColor' => 'white',
		'textColor'       => 'navy',
		'borderColor'     => 'white',
	)
);
?>
<!-- wp:group {"align":"full","className":"bpch-band bpch-band--emergency","backgroundColor":"navy","textColor":"white","style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|8"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-band bpch-band--emergency has-white-color has-navy-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--8);padding-bottom:var(--wp--preset--spacing--8)">
	<!-- wp:group {"className":"bpch-band__inner","style":{"spacing":{"blockGap":"var:preset|spacing|7"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group bpch-band__inner">
		<!-- wp:paragraph {"className":"bpch-band__figure","textColor":"orange"} -->
		<p class="bpch-band__figure has-orange-color has-text-color"><?php esc_html_e( '24/7', 'blue-peak-climate' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:group {"className":"bpch-band__copy","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-band__copy">
			<!-- wp:heading {"textColor":"white"} -->
			<h2 class="wp-block-heading has-white-color has-text-color"><?php esc_html_e( 'Not sure which service you need?', 'blue-peak-climate' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"on-navy"} -->
			<p class="has-on-navy-color has-text-color"><?php esc_html_e( "Most people aren't. Tell us what the system is doing, or not doing, and we'll book the right visit.", 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:buttons {"className":"bpch-cta-row"} -->
		<div class="wp-block-buttons bpch-cta-row">
			<!-- wp:button <?php echo $bpch_call_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
			<div class="wp-block-button"><a class="wp-block-button__link has-navy-color has-white-background-color has-text-color has-background has-border-color has-white-border-color wp-element-button" href="<?php echo esc_url( $bpch_business['phone_href'] ); ?>"><?php echo esc_html( $bpch_call_label ); ?></a></div>
			<!-- /wp:button -->

			<!-- wp:button {"textColor":"white","borderColor":"on-navy","className":"is-style-outline"} -->
			<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-white-color has-text-color has-border-color has-on-navy-border-color wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Request a visit', 'blue-peak-climate' ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
