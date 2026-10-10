<?php
/**
 * Title: Feature split
 * Slug: blue-peak-climate/feature-split
 * Categories: blue-peak-climate
 * Description: A photo beside three reasons to choose the company, with a link to the About page.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"bpch-section bpch-section--split","backgroundColor":"mist","style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-section bpch-section--split has-mist-background-color has-background" style="padding-top:var(--wp--preset--spacing--9);padding-bottom:var(--wp--preset--spacing--9)">
	<!-- wp:group {"className":"bpch-split","style":{"spacing":{"blockGap":"var:preset|spacing|8"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
	<div class="wp-block-group bpch-split">
		<?php echo bpch_theme_photo_cover( 'tech-portrait', 'portrait' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helper. ?>

		<!-- wp:group {"className":"bpch-split__copy","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-split__copy">
			<!-- wp:paragraph {"className":"bpch-eyebrow","fontSize":"x-small"} -->
			<p class="bpch-eyebrow has-x-small-font-size"><?php esc_html_e( 'Why homeowners call us', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:heading -->
			<h2 class="wp-block-heading"><?php esc_html_e( 'Technicians who know what a Texas summer does to an AC', 'blue-peak-climate' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:group {"className":"bpch-reasons","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
			<div class="wp-block-group bpch-reasons">
				<!-- wp:group {"className":"bpch-reason","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
				<div class="wp-block-group bpch-reason">
					<!-- wp:bpch/icon {"icon":"truck","tone":"light","lock":{"move":true,"remove":true}} /-->

					<!-- wp:group {"className":"bpch-reason__text","style":{"spacing":{"blockGap":"var:preset|spacing|1"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
					<div class="wp-block-group bpch-reason__text">
						<!-- wp:paragraph {"className":"bpch-reason__title"} -->
						<p class="bpch-reason__title"><?php esc_html_e( 'Trucks stocked for common failures', 'blue-peak-climate' ); ?></p>
						<!-- /wp:paragraph -->

						<!-- wp:paragraph -->
						<p><?php esc_html_e( 'Capacitors, contactors, fan motors, and refrigerant ride along, so most repairs finish in one trip.', 'blue-peak-climate' ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->

				<!-- wp:group {"className":"bpch-reason","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
				<div class="wp-block-group bpch-reason">
					<!-- wp:bpch/icon {"icon":"scale","tone":"light","lock":{"move":true,"remove":true}} /-->

					<!-- wp:group {"className":"bpch-reason__text","style":{"spacing":{"blockGap":"var:preset|spacing|1"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
					<div class="wp-block-group bpch-reason__text">
						<!-- wp:paragraph {"className":"bpch-reason__title"} -->
						<p class="bpch-reason__title"><?php esc_html_e( 'Straight answers on repair or replace', 'blue-peak-climate' ); ?></p>
						<!-- /wp:paragraph -->

						<!-- wp:paragraph -->
						<p><?php esc_html_e( "If a small part buys you years, we say so. If it doesn't, we show you both prices side by side.", 'blue-peak-climate' ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->

				<!-- wp:group {"className":"bpch-reason","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
				<div class="wp-block-group bpch-reason">
					<!-- wp:bpch/icon {"icon":"house","tone":"light","lock":{"move":true,"remove":true}} /-->

					<!-- wp:group {"className":"bpch-reason__text","style":{"spacing":{"blockGap":"var:preset|spacing|1"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
					<div class="wp-block-group bpch-reason__text">
						<!-- wp:paragraph {"className":"bpch-reason__title"} -->
						<p class="bpch-reason__title"><?php esc_html_e( 'Careful in your home', 'blue-peak-climate' ); ?></p>
						<!-- /wp:paragraph -->

						<!-- wp:paragraph -->
						<p><?php esc_html_e( 'Shoe covers inside, drop cloths under the air handler, and we haul away the old equipment.', 'blue-peak-climate' ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->

			<!-- wp:buttons {"className":"bpch-link-arrow"} -->
			<div class="wp-block-buttons bpch-link-arrow">
				<!-- wp:button -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'More about BluePeak', 'blue-peak-climate' ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
