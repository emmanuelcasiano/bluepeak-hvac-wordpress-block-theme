<?php
/**
 * Title: Blog post list
 * Slug: blue-peak-climate/blog-list
 * Categories: blue-peak-climate
 * Inserter: false
 * Description: The posts of the current blog page, category or tag as cards in one column (the Query Loop's Display setting turns it into a grid), then the closing call to action.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

// Follows the page: the blog, a category or a tag. The number of posts per page comes from Settings → Reading.
$bpch_query = serialize_block_attributes(
	array(
		'queryId'   => 1,
		'query'     => array(
			'perPage'  => 10,
			'pages'    => 0,
			'offset'   => 0,
			'postType' => 'post',
			'order'    => 'desc',
			'orderBy'  => 'date',
			'author'   => '',
			'search'   => '',
			'exclude'  => array(),
			'sticky'   => '',
			'inherit'  => true,
		),
		'className' => 'bpch-posts',
	)
);

$bpch_excerpt = serialize_block_attributes(
	array(
		'moreText'      => __( 'Read more', 'blue-peak-climate' ),
		'excerptLength' => 40,
		'className'     => 'bpch-post-card__excerpt',
	)
);
?>

<!-- wp:group {"align":"full","className":"bpch-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-section" style="padding-top:var(--wp--preset--spacing--9);padding-bottom:var(--wp--preset--spacing--9)">
	<!-- wp:query <?php echo $bpch_query; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
	<div class="wp-block-query bpch-posts">
		<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|5"}}} -->
			<!-- wp:group {"className":"bpch-card bpch-post-card","backgroundColor":"white"} -->
			<div class="wp-block-group bpch-card bpch-post-card has-white-background-color has-background">
				<!-- wp:group {"className":"bpch-post-card__inner"} -->
				<div class="wp-block-group bpch-post-card__inner">
					<!-- wp:post-featured-image {"aspectRatio":"3/2","className":"bpch-post-card__image"} /-->

					<!-- wp:group {"className":"bpch-post-card__body","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
					<div class="wp-block-group bpch-post-card__body">
						<!-- wp:group {"className":"bpch-post-card__meta","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
						<div class="wp-block-group bpch-post-card__meta">
							<!-- wp:post-terms {"term":"category","fontSize":"x-small","className":"bpch-tag"} /-->

							<!-- wp:post-date {"fontSize":"small","className":"bpch-post-card__date"} /-->
						</div>
						<!-- /wp:group -->

						<!-- wp:post-title {"level":2,"isLink":true,"className":"bpch-post-card__title"} /-->

						<!-- wp:post-excerpt <?php echo $bpch_excerpt; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> /-->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->

		<!-- wp:query-pagination {"className":"bpch-pagination","layout":{"type":"flex","justifyContent":"space-between"}} -->
			<!-- wp:query-pagination-previous /-->
			<!-- wp:query-pagination-numbers /-->
			<!-- wp:query-pagination-next /-->
		<!-- /wp:query-pagination -->

		<!-- wp:query-no-results -->
			<!-- wp:pattern {"slug":"blue-peak-climate/no-results"} /-->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->

<?php
echo bpch_theme_cta_band( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helper.
	__( 'Something not right at home?', 'blue-peak-climate' ),
	__( 'Tell us what your heating, cooling or wiring is doing. A real person answers the phone, any hour.', 'blue-peak-climate' ),
	__( 'Book a service visit', 'blue-peak-climate' ),
	home_url( '/contact/' )
);
