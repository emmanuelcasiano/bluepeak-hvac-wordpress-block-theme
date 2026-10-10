<?php
/**
 * Title: Service group cards
 * Slug: blue-peak-climate/service-groups
 * Categories: blue-peak-climate
 * Description: Heating, Cooling and Electrical cards with a live service count and a link to each section.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

// Starting content of the count and link in each card. The `bpch/service-category` binding replaces them
// with the live values (see bpch_theme_service_group()).
$bpch_heating    = bpch_theme_service_group( 'heating', 10 );
$bpch_cooling    = bpch_theme_service_group( 'cooling', 10 );
$bpch_electrical = bpch_theme_service_group( 'electrical', 2 );

$bpch_count_binding = static function ( $slug ) {
	return serialize_block_attributes(
		array(
			'className' => 'bpch-group-card__count',
			'fontSize'  => 'x-small',
			'metadata'  => array(
				'bindings' => array(
					'content' => array(
						'source' => 'bpch/service-category',
						'args'   => array(
							'slug' => $slug,
							'key'  => 'count_label',
						),
					),
				),
			),
		)
	);
};

$bpch_link_binding = static function ( $slug ) {
	return serialize_block_attributes(
		array(
			'metadata' => array(
				'bindings' => array(
					'url' => array(
						'source' => 'bpch/service-category',
						'args'   => array(
							'slug' => $slug,
							'key'  => 'url',
						),
					),
				),
			),
		)
	);
};

$bpch_heating_count    = $bpch_count_binding( 'heating' );
$bpch_cooling_count    = $bpch_count_binding( 'cooling' );
$bpch_electrical_count = $bpch_count_binding( 'electrical' );
$bpch_heating_link     = $bpch_link_binding( 'heating' );
$bpch_cooling_link     = $bpch_link_binding( 'cooling' );
$bpch_electrical_link  = $bpch_link_binding( 'electrical' );
?>
<!-- wp:group {"align":"full","className":"bpch-section bpch-section--groups","backgroundColor":"mist","style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-section bpch-section--groups has-mist-background-color has-background" style="padding-top:var(--wp--preset--spacing--9);padding-bottom:var(--wp--preset--spacing--9)">
	<!-- wp:group {"className":"bpch-section__head","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
	<div class="wp-block-group bpch-section__head">
		<!-- wp:paragraph {"className":"bpch-eyebrow","fontSize":"x-small"} -->
		<p class="bpch-eyebrow has-x-small-font-size"><?php esc_html_e( 'What we do', 'blue-peak-climate' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:heading -->
		<h2 class="wp-block-heading"><?php esc_html_e( 'Three trades, one company to call', 'blue-peak-climate' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"fontSize":"large"} -->
		<p class="has-large-font-size"><?php esc_html_e( "A lot of HVAC trouble starts at the thermostat or the breaker panel. We handle both, so you don't need a second contractor.", 'blue-peak-climate' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"bpch-cards","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"stretch"}} -->
	<div class="wp-block-group bpch-cards">
		<!-- wp:group {"className":"bpch-card bpch-group-card","backgroundColor":"white","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-card bpch-group-card has-white-background-color has-background">
			<!-- wp:group {"className":"bpch-group-card__top","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
			<div class="wp-block-group bpch-group-card__top">
				<!-- wp:group {"className":"bpch-group-card__title","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
				<div class="wp-block-group bpch-group-card__title">
					<!-- wp:bpch/icon {"icon":"flame","tone":"orange","lock":{"move":true,"remove":true}} /-->

					<!-- wp:heading {"level":3} -->
					<h3 class="wp-block-heading"><?php esc_html_e( 'Heating', 'blue-peak-climate' ); ?></h3>
					<!-- /wp:heading -->
				</div>
				<!-- /wp:group -->

				<!-- wp:paragraph <?php echo $bpch_heating_count; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
				<p class="bpch-group-card__count has-x-small-font-size"><?php echo esc_html( $bpch_heating['count_label'] ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Furnaces, heat pumps, and boilers. Repair, replace, install, and tune up.', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:list {"className":"bpch-service-list","fontSize":"small","textColor":"ink"} -->
			<ul class="wp-block-list bpch-service-list has-ink-color has-text-color has-small-font-size">
				<!-- wp:list-item -->
				<li><?php esc_html_e( 'Furnace repair and replacement', 'blue-peak-climate' ); ?></li>
				<!-- /wp:list-item -->

				<!-- wp:list-item -->
				<li><?php esc_html_e( 'Heat pump repair and installation', 'blue-peak-climate' ); ?></li>
				<!-- /wp:list-item -->

				<!-- wp:list-item -->
				<li><?php esc_html_e( 'Boiler repair and maintenance', 'blue-peak-climate' ); ?></li>
				<!-- /wp:list-item -->

				<!-- wp:list-item -->
				<li><?php esc_html_e( 'Seasonal furnace tune-ups', 'blue-peak-climate' ); ?></li>
				<!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->

			<!-- wp:buttons {"className":"bpch-link-arrow"} -->
			<div class="wp-block-buttons bpch-link-arrow">
				<!-- wp:button <?php echo $bpch_heating_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $bpch_heating['url'] ); ?>"><?php esc_html_e( 'See heating services', 'blue-peak-climate' ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"bpch-card bpch-group-card","backgroundColor":"white","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-card bpch-group-card has-white-background-color has-background">
			<!-- wp:group {"className":"bpch-group-card__top","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
			<div class="wp-block-group bpch-group-card__top">
				<!-- wp:group {"className":"bpch-group-card__title","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
				<div class="wp-block-group bpch-group-card__title">
					<!-- wp:bpch/icon {"icon":"snowflake","tone":"blue","lock":{"move":true,"remove":true}} /-->

					<!-- wp:heading {"level":3} -->
					<h3 class="wp-block-heading"><?php esc_html_e( 'Cooling', 'blue-peak-climate' ); ?></h3>
					<!-- /wp:heading -->
				</div>
				<!-- /wp:group -->

				<!-- wp:paragraph <?php echo $bpch_cooling_count; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
				<p class="bpch-group-card__count has-x-small-font-size"><?php echo esc_html( $bpch_cooling['count_label'] ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Central AC, ductless mini-splits, and the air quality inside your home.', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:list {"className":"bpch-service-list","fontSize":"small","textColor":"ink"} -->
			<ul class="wp-block-list bpch-service-list has-ink-color has-text-color has-small-font-size">
				<!-- wp:list-item -->
				<li><?php esc_html_e( 'AC repair and replacement', 'blue-peak-climate' ); ?></li>
				<!-- /wp:list-item -->

				<!-- wp:list-item -->
				<li><?php esc_html_e( 'Ductless AC installation', 'blue-peak-climate' ); ?></li>
				<!-- /wp:list-item -->

				<!-- wp:list-item -->
				<li><?php esc_html_e( 'Air filtration and purification', 'blue-peak-climate' ); ?></li>
				<!-- /wp:list-item -->

				<!-- wp:list-item -->
				<li><?php esc_html_e( 'Whole house fans and humidifiers', 'blue-peak-climate' ); ?></li>
				<!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->

			<!-- wp:buttons {"className":"bpch-link-arrow"} -->
			<div class="wp-block-buttons bpch-link-arrow">
				<!-- wp:button <?php echo $bpch_cooling_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $bpch_cooling['url'] ); ?>"><?php esc_html_e( 'See cooling services', 'blue-peak-climate' ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"bpch-card bpch-group-card","backgroundColor":"white","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-card bpch-group-card has-white-background-color has-background">
			<!-- wp:group {"className":"bpch-group-card__top","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
			<div class="wp-block-group bpch-group-card__top">
				<!-- wp:group {"className":"bpch-group-card__title","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
				<div class="wp-block-group bpch-group-card__title">
					<!-- wp:bpch/icon {"icon":"zap","tone":"navy","lock":{"move":true,"remove":true}} /-->

					<!-- wp:heading {"level":3} -->
					<h3 class="wp-block-heading"><?php esc_html_e( 'Electrical', 'blue-peak-climate' ); ?></h3>
					<!-- /wp:heading -->
				</div>
				<!-- /wp:group -->

				<!-- wp:paragraph <?php echo $bpch_electrical_count; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
				<p class="bpch-group-card__count has-x-small-font-size"><?php echo esc_html( $bpch_electrical['count_label'] ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'The breakers and wiring your heating and cooling equipment depend on.', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:list {"className":"bpch-service-list","fontSize":"small","textColor":"ink"} -->
			<ul class="wp-block-list bpch-service-list has-ink-color has-text-color has-small-font-size">
				<!-- wp:list-item -->
				<li><?php esc_html_e( 'Circuit breaker replacement', 'blue-peak-climate' ); ?></li>
				<!-- /wp:list-item -->

				<!-- wp:list-item -->
				<li><?php esc_html_e( 'Dedicated circuits for HVAC', 'blue-peak-climate' ); ?></li>
				<!-- /wp:list-item -->

				<!-- wp:list-item -->
				<li><?php esc_html_e( 'Wiring for new equipment', 'blue-peak-climate' ); ?></li>
				<!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->

			<!-- wp:buttons {"className":"bpch-link-arrow"} -->
			<div class="wp-block-buttons bpch-link-arrow">
				<!-- wp:button <?php echo $bpch_electrical_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $bpch_electrical['url'] ); ?>"><?php esc_html_e( 'See electrical services', 'blue-peak-climate' ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
