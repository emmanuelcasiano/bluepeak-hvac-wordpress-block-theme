<?php
/**
 * Title: Gallery page
 * Slug: blue-peak-climate/page-gallery
 * Categories: blue-peak-climate
 * Block Types: core/post-content
 * Description: Page intro, a grid of captioned photos and a quote call to action.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

// Photo slug, category slug, category name, kind of work and caption, in the order the design shows them. The
// category class on each item is what the gallery filter block reads to draw its chips and to hide photos.
$bpch_gallery = array(
	array( 'house', 'cooling', __( 'Cooling', 'blue-peak-climate' ), __( 'AC installation', 'blue-peak-climate' ), __( 'New condenser on a fresh concrete pad', 'blue-peak-climate' ) ),
	array( 'tech-unit', 'crew', __( 'Our crew', 'blue-peak-climate' ), __( 'AC repair', 'blue-peak-climate' ), __( 'Checking the electrical side of a condenser', 'blue-peak-climate' ) ),
	array( 'service-heating', 'cooling', __( 'Cooling', 'blue-peak-climate' ), __( 'Ductless installation', 'blue-peak-climate' ), __( 'Wall-mounted ductless unit in a living room', 'blue-peak-climate' ) ),
	array( 'heat-pump-room', 'heating', __( 'Heating', 'blue-peak-climate' ), __( 'Heat pump installation', 'blue-peak-climate' ), __( 'Heat pump in a utility room, ready for winter', 'blue-peak-climate' ) ),
	array( 'tech-wide', 'crew', __( 'Our crew', 'blue-peak-climate' ), __( 'AC maintenance', 'blue-peak-climate' ), __( 'Coil cleaning during a spring tune-up', 'blue-peak-climate' ) ),
	array( 'wall-control', 'heating', __( 'Heating', 'blue-peak-climate' ), __( 'Controls', 'blue-peak-climate' ), __( 'Wall control for a ductless system', 'blue-peak-climate' ) ),
	array( 'family-home', 'comfort', __( 'Comfort at home', 'blue-peak-climate' ), __( 'Air quality', 'blue-peak-climate' ), __( 'Even temperatures from room to room', 'blue-peak-climate' ) ),
	array( 'coil-check', 'crew', __( 'Our crew', 'blue-peak-climate' ), __( 'Maintenance', 'blue-peak-climate' ), __( 'Checking a condenser coil with a leak detector', 'blue-peak-climate' ) ),
);

echo bpch_theme_page_intro( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helper.
	__( 'Gallery', 'blue-peak-climate' ),
	__( 'Our work, inside and outside the house', 'blue-peak-climate' ),
	__( 'Condensers, heat pumps, mini-splits, and the crew that puts them in.', 'blue-peak-climate' )
);
?>

<!-- wp:group {"align":"full","className":"bpch-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-section" style="padding-top:var(--wp--preset--spacing--9);padding-bottom:var(--wp--preset--spacing--9)">
	<!-- wp:bpch/gallery-filter -->
	<!-- wp:group {"className":"bpch-gallery","style":{"spacing":{"blockGap":"var:preset|spacing|6"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"top"}} -->
	<div class="wp-block-group bpch-gallery">
		<?php foreach ( $bpch_gallery as $bpch_item ) : ?>
		<!-- wp:group {"className":"bpch-gallery__item bpch-gallery__item--<?php echo esc_attr( $bpch_item[1] ); ?>","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-gallery__item bpch-gallery__item--<?php echo esc_attr( $bpch_item[1] ); ?>">
			<?php echo bpch_theme_photo_cover( $bpch_item[0], 'card', 'blue-soft', $bpch_item[4] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helper. ?>

			<!-- wp:group {"className":"bpch-gallery__caption","style":{"spacing":{"blockGap":"var:preset|spacing|1"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
			<div class="wp-block-group bpch-gallery__caption">
				<!-- wp:paragraph {"className":"bpch-eyebrow","fontSize":"x-small"} -->
				<p class="bpch-eyebrow has-x-small-font-size"><?php echo esc_html( $bpch_item[2] . ' · ' . $bpch_item[3] ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"bpch-gallery__title","textColor":"ink","fontSize":"medium"} -->
				<p class="bpch-gallery__title has-ink-color has-text-color has-medium-font-size"><?php echo esc_html( $bpch_item[4] ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:group -->
	<!-- /wp:bpch/gallery-filter -->
</div>
<!-- /wp:group -->

<?php
echo bpch_theme_cta_band( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helper.
	__( 'Want a system like these?', 'blue-peak-climate' ),
	__( "We'll measure your home, recommend the right size, and give you a written quote at no charge.", 'blue-peak-climate' ),
	__( 'Get a free quote', 'blue-peak-climate' ),
	home_url( '/contact/' )
);
