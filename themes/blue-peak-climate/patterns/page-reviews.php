<?php
/**
 * Title: Reviews page
 * Slug: blue-peak-climate/page-reviews
 * Categories: blue-peak-climate
 * Block Types: core/post-content
 * Description: Page intro, a grid of customer reviews from the Reviews post type, and a call to action.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

$bpch_query = serialize_block_attributes(
	array(
		'queryId'   => 1,
		'query'     => array(
			'perPage'  => 30,
			'pages'    => 0,
			'offset'   => 0,
			'postType' => 'bpch_review',
			'order'    => 'asc',
			'orderBy'  => 'menu_order',
			'author'   => '',
			'search'   => '',
			'exclude'  => array(),
			'sticky'   => '',
			'inherit'  => false,
			'parents'  => array(),
		),
		'className' => 'bpch-reviews',
	)
);

$bpch_binding = static function ( $key, $class_name ) {
	return serialize_block_attributes(
		array(
			'className' => $class_name,
			'fontSize'  => 'small',
			'metadata'  => array(
				'bindings' => array(
					'content' => array(
						'source' => 'core/post-meta',
						'args'   => array( 'key' => $key ),
					),
				),
			),
		)
	);
};

echo bpch_theme_page_intro( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helper.
	__( 'Reviews', 'blue-peak-climate' ),
	__( 'What homeowners say about BluePeak', 'blue-peak-climate' ),
	__( 'We would rather earn a good review than ask for one. Here is what customers tell us after the work is done.', 'blue-peak-climate' ),
	'',
	'family-couch'
);
?>

<!-- wp:group {"align":"full","className":"bpch-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-section" style="padding-top:var(--wp--preset--spacing--9);padding-bottom:var(--wp--preset--spacing--9)">
	<!-- wp:query <?php echo $bpch_query; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
	<div class="wp-block-query bpch-reviews">
		<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"grid","minimumColumnWidth":"20rem"}} -->
			<!-- wp:group {"className":"bpch-card bpch-review-card","backgroundColor":"white","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
			<div class="wp-block-group bpch-card bpch-review-card has-white-background-color has-background">
				<!-- wp:bpch/review-rating /-->

				<!-- wp:post-excerpt {"excerptLength":120,"className":"bpch-review-card__text"} /-->

				<!-- wp:group {"className":"bpch-review-card__meta","style":{"spacing":{"blockGap":"var:preset|spacing|1"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
				<div class="wp-block-group bpch-review-card__meta">
					<!-- wp:paragraph <?php echo $bpch_binding( 'reviewer', 'bpch-review-card__who' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
					<p class="bpch-review-card__who has-small-font-size"></p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph <?php echo $bpch_binding( 'reviewed_service', 'bpch-review-card__service' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
					<p class="bpch-review-card__service has-small-font-size"></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->

		<!-- wp:query-no-results -->
			<!-- wp:paragraph {"fontSize":"large"} -->
			<p class="has-large-font-size"><?php esc_html_e( 'Customer reviews are coming soon. In the meantime, call us and ask anything about your system.', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->

<?php
echo bpch_theme_cta_band( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helper.
	__( 'Ready for service you can count on?', 'blue-peak-climate' ),
	__( 'Tell us what your system is doing. A real person answers the phone, any hour.', 'blue-peak-climate' ),
	__( 'Book a service visit', 'blue-peak-climate' ),
	home_url( '/contact/' )
);
