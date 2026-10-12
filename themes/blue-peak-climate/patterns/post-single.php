<?php
/**
 * Title: Blog post
 * Slug: blue-peak-climate/post-single
 * Categories: blue-peak-climate
 * Inserter: false
 * Description: One blog post: title band with category and date, featured image, the post text, links to the next and previous post, and the closing call to action.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

$bpch_previous = serialize_block_attributes(
	array(
		'type'      => 'previous',
		'label'     => __( 'Previous post', 'blue-peak-climate' ),
		'showTitle' => true,
		'className' => 'bpch-post-nav__link',
	)
);

$bpch_next = serialize_block_attributes(
	array(
		'type'      => 'next',
		'label'     => __( 'Next post', 'blue-peak-climate' ),
		'showTitle' => true,
		'className' => 'bpch-post-nav__link',
	)
);
?>
<!-- wp:group {"align":"full","className":"bpch-page-intro bpch-post-intro","backgroundColor":"mist","style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|8"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-page-intro bpch-post-intro has-mist-background-color has-background" style="padding-top:var(--wp--preset--spacing--8);padding-bottom:var(--wp--preset--spacing--8)">
	<!-- wp:group {"className":"bpch-section__head","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
	<div class="wp-block-group bpch-section__head">
		<!-- wp:post-terms {"term":"category","fontSize":"x-small","className":"bpch-eyebrow"} /-->

		<!-- wp:post-title {"level":1} /-->

		<!-- wp:post-date {"fontSize":"small"} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"bpch-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|9"},"blockGap":"var:preset|spacing|7"}},"layout":{"type":"constrained","contentSize":"720px","wideSize":"1120px"}} -->
<div class="wp-block-group alignfull bpch-section" style="padding-top:var(--wp--preset--spacing--8);padding-bottom:var(--wp--preset--spacing--9)">
	<!-- wp:post-featured-image {"aspectRatio":"16/9","align":"wide","className":"bpch-featured-image"} /-->

	<!-- wp:post-content {"className":"bpch-post-content","layout":{"type":"constrained"}} /-->

	<!-- wp:group {"className":"bpch-post-nav","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group bpch-post-nav">
		<!-- wp:post-navigation-link <?php echo $bpch_previous; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> /-->

		<!-- wp:post-navigation-link <?php echo $bpch_next; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<?php
echo bpch_theme_cta_band( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helper.
	__( 'Want someone to take a look?', 'blue-peak-climate' ),
	__( 'Tell us what your system is doing and we will tell you if it can wait. A real person answers the phone, any hour.', 'blue-peak-climate' ),
	__( 'Book a service visit', 'blue-peak-climate' ),
	home_url( '/contact/' )
);
