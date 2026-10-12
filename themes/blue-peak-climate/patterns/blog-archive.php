<?php
/**
 * Title: Blog archive
 * Slug: blue-peak-climate/blog-archive
 * Categories: blue-peak-climate
 * Inserter: false
 * Description: A category, tag, date or search page of the blog: page intro with the archive title, then the list of posts.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"bpch-page-intro","backgroundColor":"mist","style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|8"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-page-intro has-mist-background-color has-background" style="padding-top:var(--wp--preset--spacing--8);padding-bottom:var(--wp--preset--spacing--8)">
	<!-- wp:group {"className":"bpch-section__head","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
	<div class="wp-block-group bpch-section__head">
		<!-- wp:paragraph {"className":"bpch-eyebrow","fontSize":"x-small"} -->
		<p class="bpch-eyebrow has-x-small-font-size"><?php esc_html_e( 'Blog', 'blue-peak-climate' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:query-title {"type":"archive","level":1} /-->

		<!-- wp:term-description {"fontSize":"large"} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:pattern {"slug":"blue-peak-climate/blog-list"} /-->
