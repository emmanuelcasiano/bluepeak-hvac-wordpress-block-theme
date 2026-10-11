<?php
/**
 * Title: Offers page
 * Slug: blue-peak-climate/page-offers
 * Categories: blue-peak-climate
 * Block Types: core/post-content
 * Description: Page intro, the offers that have not expired yet (from the Offers post type), and a call to action.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

$bpch_query = serialize_block_attributes(
	array(
		'queryId'   => 1,
		'query'     => array(
			'perPage'  => 12,
			'pages'    => 0,
			'offset'   => 0,
			'postType' => 'bpch_offer',
			'order'    => 'asc',
			'orderBy'  => 'menu_order',
			'author'   => '',
			'search'   => '',
			'exclude'  => array(),
			'sticky'   => '',
			'inherit'  => false,
			'parents'  => array(),
		),
		'className' => 'bpch-offers',
	)
);

// The "Ends June 30" pill: the plugin formats the offer's expiry date. An offer with no date prints nothing.
$bpch_ends = serialize_block_attributes(
	array(
		'className' => 'bpch-offer-card__ends',
		'fontSize'  => 'x-small',
		'metadata'  => array(
			'bindings' => array(
				'content' => array(
					'source' => 'bpch/offer',
					'args'   => array( 'key' => 'ends_label' ),
				),
			),
		),
	)
);

$bpch_terms = serialize_block_attributes(
	array(
		'className' => 'bpch-offer-card__terms',
		'fontSize'  => 'small',
		'metadata'  => array(
			'bindings' => array(
				'content' => array(
					'source' => 'core/post-meta',
					'args'   => array( 'key' => 'terms' ),
				),
			),
		),
	)
);

echo bpch_theme_page_intro( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helper.
	__( 'Offers', 'blue-peak-climate' ),
	__( 'Current offers', 'blue-peak-climate' ),
	__( 'Seasonal savings on heating, cooling, and electrical work. Each offer ends on the date shown, so book while it lasts.', 'blue-peak-climate' )
);
?>

<!-- wp:group {"align":"full","className":"bpch-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-section" style="padding-top:var(--wp--preset--spacing--9);padding-bottom:var(--wp--preset--spacing--9)">
	<!-- wp:query <?php echo $bpch_query; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
	<div class="wp-block-query bpch-offers">
		<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"grid","minimumColumnWidth":"20rem"}} -->
			<!-- wp:group {"className":"bpch-card bpch-offer-card","backgroundColor":"white","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
			<div class="wp-block-group bpch-card bpch-offer-card has-white-background-color has-background">
				<!-- wp:paragraph <?php echo $bpch_ends; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
				<p class="bpch-offer-card__ends has-x-small-font-size"></p>
				<!-- /wp:paragraph -->

				<!-- wp:post-title {"level":2} /-->

				<!-- wp:post-excerpt {"excerptLength":60} /-->

				<!-- wp:paragraph <?php echo $bpch_terms; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
				<p class="bpch-offer-card__terms has-small-font-size"></p>
				<!-- /wp:paragraph -->

				<!-- wp:buttons {"className":"bpch-offer-card__action"} -->
				<div class="wp-block-buttons bpch-offer-card__action">
					<!-- wp:button -->
					<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Claim this offer', 'blue-peak-climate' ); ?></a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->

		<!-- wp:query-no-results -->
			<!-- wp:group {"className":"bpch-card bpch-offer-empty","backgroundColor":"blue-soft","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
			<div class="wp-block-group bpch-card bpch-offer-empty has-blue-soft-background-color has-background">
				<!-- wp:heading {"level":2} -->
				<h2 class="wp-block-heading"><?php esc_html_e( 'No offers right now', 'blue-peak-climate' ); ?></h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p><?php esc_html_e( 'New offers appear here as they start. Call us and ask what we can do for your system today.', 'blue-peak-climate' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->

<?php
echo bpch_theme_cta_band( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helper.
	__( 'Not sure which offer fits?', 'blue-peak-climate' ),
	__( 'Tell us about your system and we will point you to the right one.', 'blue-peak-climate' ),
	__( 'Book a service visit', 'blue-peak-climate' ),
	home_url( '/contact/' )
);
