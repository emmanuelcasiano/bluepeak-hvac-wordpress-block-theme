<?php
/**
 * Title: Single service
 * Slug: blue-peak-climate/service-single
 * Categories: blue-peak-climate
 * Inserter: false
 * Description: A service page: title, short description, starting price, the same steps and FAQ on every service, and a call to action.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

$bpch_call_attributes = bpch_theme_call_button_attributes( array( 'className' => 'is-style-outline' ) );
$bpch_business        = bpch_theme_business();
$bpch_price_binding   = serialize_block_attributes(
	array(
		'className' => 'bpch-price',
		'fontSize'  => 'large',
		'metadata'  => array(
			'bindings' => array(
				'content' => array(
					'source' => 'core/post-meta',
					'args'   => array( 'key' => 'price_from' ),
				),
			),
		),
	)
);
?>
<!-- wp:group {"align":"full","className":"bpch-page-intro bpch-service-intro","backgroundColor":"mist","style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|8"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-page-intro bpch-service-intro has-mist-background-color has-background" style="padding-top:var(--wp--preset--spacing--8);padding-bottom:var(--wp--preset--spacing--8)">
	<!-- wp:group {"className":"bpch-intro-split","style":{"spacing":{"blockGap":"var:preset|spacing|7"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
	<div class="wp-block-group bpch-intro-split">
	<!-- wp:group {"className":"bpch-section__head","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
	<div class="wp-block-group bpch-section__head">
		<!-- wp:post-terms {"term":"bpch_service_category","className":"bpch-eyebrow","fontSize":"x-small"} /-->

		<!-- wp:post-title {"level":1} /-->

		<!-- wp:post-excerpt {"excerptLength":60,"fontSize":"large"} /-->

		<!-- wp:group {"className":"bpch-service-facts","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
		<div class="wp-block-group bpch-service-facts">
			<!-- wp:bpch/service-type /-->

			<!-- wp:paragraph <?php echo $bpch_price_binding; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
			<p class="bpch-price has-large-font-size"></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:buttons {"className":"bpch-cta-row"} -->
		<div class="wp-block-buttons bpch-cta-row">
			<!-- wp:button -->
			<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Book this service', 'blue-peak-climate' ); ?></a></div>
			<!-- /wp:button -->

			<!-- wp:button <?php echo $bpch_call_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
			<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $bpch_business['phone_href'] ); ?>"><?php echo esc_html( bpch_theme_call_label( $bpch_business['phone'] ) ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->

	<!-- wp:post-featured-image {"aspectRatio":"4/3","className":"bpch-photo bpch-photo--intro"} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"bpch-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|7","bottom":"var:preset|spacing|2"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-section" style="padding-top:var(--wp--preset--spacing--7);padding-bottom:var(--wp--preset--spacing--2)">
	<!-- wp:buttons {"className":"bpch-link-arrow bpch-link-back"} -->
	<div class="wp-block-buttons bpch-link-arrow bpch-link-back">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'All services', 'blue-peak-climate' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->

<!-- wp:pattern {"slug":"blue-peak-climate/steps"} /-->

<!-- wp:pattern {"slug":"blue-peak-climate/faq"} /-->

<!-- wp:pattern {"slug":"blue-peak-climate/cta-band"} /-->
