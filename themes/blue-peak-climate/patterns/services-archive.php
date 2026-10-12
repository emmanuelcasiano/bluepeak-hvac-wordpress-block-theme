<?php
/**
 * Title: Services archive
 * Slug: blue-peak-climate/services-archive
 * Categories: blue-peak-climate
 * Inserter: false
 * Description: The /services/ page: category chips, one section per category with its service cards, and the 24/7 band.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * One category section: icon, live count, title, lead and the cards of its services.
 *
 * The cards come from a Query Loop limited to the category by term ID. The ID is looked up here, on this site,
 * so the pattern works on any copy of the database. Order follows the "Order" box on each service.
 *
 * @param int    $query_id   Unique Query Loop ID on the page.
 * @param string $slug       Category slug.
 * @param string $title      Category name (translated).
 * @param string $lead       One sentence under the title (translated).
 * @param array  $mark       Round icon: colour, then icon name, e.g. array( 'orange', 'flame' ).
 * @param string $background Palette colour behind the section.
 * @param int    $fallback   Count printed until the binding replaces it.
 * @return string Block markup.
 */
$bpch_category_section = static function ( $query_id, $slug, $title, $lead, $mark, $background, $fallback ) {
	$group = bpch_theme_service_group( $slug, $fallback );
	$term  = taxonomy_exists( 'bpch_service_category' ) ? get_term_by( 'slug', $slug, 'bpch_service_category' ) : false;
	$ids   = $term && ! is_wp_error( $term ) ? array( (int) $term->term_id ) : array();

	$count_attributes = serialize_block_attributes(
		array(
			'className' => 'bpch-eyebrow',
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

	$section_attributes = serialize_block_attributes(
		array(
			'align'           => 'full',
			'className'       => 'bpch-section bpch-section--services',
			'backgroundColor' => $background,
			'style'           => array(
				'spacing' => array(
					'padding' => array(
						'top'    => 'var:preset|spacing|9',
						'bottom' => 'var:preset|spacing|9',
					),
				),
			),
			'layout'          => array( 'type' => 'constrained' ),
		)
	);

	$query = array(
		'perPage'  => 30,
		'pages'    => 0,
		'offset'   => 0,
		'postType' => 'bpch_service',
		'order'    => 'asc',
		'orderBy'  => 'menu_order',
		'author'   => '',
		'search'   => '',
		'exclude'  => array(),
		'sticky'   => '',
		'inherit'  => false,
		'parents'  => array(),
	);

	if ( ! empty( $ids ) ) {
		$query['taxQuery'] = array( 'bpch_service_category' => $ids );
	}

	$query_attributes = serialize_block_attributes(
		array(
			'queryId'   => $query_id,
			'query'     => $query,
			'className' => 'bpch-services',
		)
	);

	$background_class = 'has-' . $background . '-background-color';

	ob_start();
	?>
<!-- wp:group <?php echo $section_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
<div id="<?php echo esc_attr( $slug ); ?>" class="wp-block-group alignfull bpch-section bpch-section--services <?php echo esc_attr( $background_class ); ?> has-background" style="padding-top:var(--wp--preset--spacing--9);padding-bottom:var(--wp--preset--spacing--9)">
	<!-- wp:group {"className":"bpch-section__head","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
	<div class="wp-block-group bpch-section__head">
		<!-- wp:bpch/icon {"icon":"<?php echo esc_attr( $mark[1] ); ?>","tone":"<?php echo esc_attr( $mark[0] ); ?>","lock":{"move":true,"remove":true}} /-->

		<!-- wp:paragraph <?php echo $count_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
		<p class="bpch-eyebrow has-x-small-font-size"><?php echo esc_html( $group['count_label'] ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:heading -->
		<h2 class="wp-block-heading"><?php echo esc_html( $title ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"fontSize":"large"} -->
		<p class="has-large-font-size"><?php echo esc_html( $lead ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:query <?php echo $query_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
	<div class="wp-block-query bpch-services">
		<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"grid","minimumColumnWidth":"18rem"}} -->
			<!-- wp:group {"className":"bpch-card bpch-service-card","backgroundColor":"white","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
			<div class="wp-block-group bpch-card bpch-service-card has-white-background-color has-background">
				<!-- wp:bpch/service-type /-->

				<!-- wp:post-title {"level":3,"isLink":true} /-->

				<!-- wp:post-excerpt {"excerptLength":40} /-->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->
	<?php
	return (string) ob_get_clean();
};

// Row of chips under the intro: jump to a section, or call.
$bpch_chips_markup = '<!-- wp:buttons {"className":"bpch-chips"} -->' . "\n"
	. '<div class="wp-block-buttons bpch-chips">' . "\n"
	. bpch_theme_category_chip( 'heating', __( 'Heating', 'blue-peak-climate' ), 10, 'flame' ) . "\n"
	. bpch_theme_category_chip( 'cooling', __( 'Cooling', 'blue-peak-climate' ), 10, 'snowflake' ) . "\n"
	. bpch_theme_category_chip( 'electrical', __( 'Electrical', 'blue-peak-climate' ), 2, 'zap' ) . "\n"
	. bpch_theme_emergency_chip() . "\n"
	. '</div>' . "\n"
	. '<!-- /wp:buttons -->';

echo bpch_theme_page_intro( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helper.
	__( 'Services', 'blue-peak-climate' ),
	__( 'Heating, cooling, and electrical work for your home', 'blue-peak-climate' ),
	__( "Twenty-two services from one crew. Pick the one you need, or call and describe the problem. We'll figure out the rest.", 'blue-peak-climate' ),
	$bpch_chips_markup
);

// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the section builder.
echo $bpch_category_section(
	1,
	'heating',
	__( 'Heating', 'blue-peak-climate' ),
	__( 'Furnaces, heat pumps, and boilers. Texas winters are short, but one cold snap without heat is a long night.', 'blue-peak-climate' ),
	array( 'orange', 'flame' ),
	'white',
	10
);

echo $bpch_category_section(
	2,
	'cooling',
	__( 'Cooling', 'blue-peak-climate' ),
	__( 'Central air, ductless systems, and the air you breathe indoors. This is most of what we do from May through September.', 'blue-peak-climate' ),
	array( 'blue', 'snowflake' ),
	'mist',
	10
);

echo $bpch_category_section(
	3,
	'electrical',
	__( 'Electrical', 'blue-peak-climate' ),
	__( 'The breakers and circuits that keep your heating and cooling equipment running safely.', 'blue-peak-climate' ),
	array( 'navy', 'zap' ),
	'white',
	2
);
// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
?>

<!-- wp:pattern {"slug":"blue-peak-climate/emergency-band"} /-->
