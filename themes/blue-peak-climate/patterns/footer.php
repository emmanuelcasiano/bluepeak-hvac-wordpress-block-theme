<?php
/**
 * Title: Footer
 * Slug: blue-peak-climate/footer
 * Inserter: no
 *
 * Template parts are plain HTML and can't be translated, so translatable text lives in a pattern.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"2rem","bottom":"2rem"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:2rem;padding-bottom:2rem">
	<!-- wp:paragraph {"fontSize":"small"} -->
	<p class="has-small-font-size">
		<?php
		echo esc_html(
			sprintf(
				/* translators: 1: current year, 2: site name. */
				__( '© %1$s %2$s', 'blue-peak-climate' ),
				gmdate( 'Y' ),
				get_bloginfo( 'name' )
			)
		);
		?>
	</p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
