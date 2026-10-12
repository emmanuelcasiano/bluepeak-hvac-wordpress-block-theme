<?php
/**
 * Helpers the pattern files share, so their markup is written once and every string stays translatable.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Finds a Media Library photo by its slug. Photos are tagged with the `_bpch_photo` meta (the setup script in
 * ai/scripts/create-pages.php does this for the ten placeholder photos), so the patterns find them on any copy
 * of the site, whatever their attachment IDs are. Replacing a file in the Media Library keeps the tag.
 *
 * @param string $slug Photo slug, e.g. "hero-house".
 * @return array{id: int, url: string, alt: string}|null Null when the photo has not been imported.
 */
function bpch_theme_photo( $slug ) {
	static $found = array();

	if ( array_key_exists( $slug, $found ) ) {
		return $found[ $slug ];
	}

	$found[ $slug ] = null;

	$ids = get_posts(
		array(
			'post_type'              => 'attachment',
			'post_status'            => 'inherit',
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key, WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- Looks up one of ten photos per slug, once per request.
			'meta_key'               => '_bpch_photo',
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- See above.
			'meta_value'             => $slug,
		)
	);

	if ( ! empty( $ids ) ) {
		$url = wp_get_attachment_image_url( $ids[0], 'large' );

		if ( $url ) {
			$found[ $slug ] = array(
				'id'  => (int) $ids[0],
				'url' => $url,
				'alt' => (string) get_post_meta( $ids[0], '_wp_attachment_image_alt', true ),
			);
		}
	}

	return $found[ $slug ];
}

/**
 * A photo slot as a Cover block. With the photo imported it shows the picture; without it, a plain colour, so the
 * layout still holds.
 *
 * @param string $slug    Photo slug, see bpch_theme_photo().
 * @param string $variant Shape class: hero, portrait, wide or card.
 * @param string $overlay Palette colour for the empty slot.
 * @param string $caption Text printed next to the photo. When it is the same as the photo's alt text, the alt text is
 *                        left empty, so a screen reader does not read the same line twice.
 * @return string Block markup.
 */
function bpch_theme_photo_cover( $slug, $variant, $overlay = 'blue-soft', $caption = '' ) {
	$photo     = bpch_theme_photo( $slug );
	$classname = 'bpch-photo bpch-photo--' . $variant;

	if ( ! $photo ) {
		$attributes = serialize_block_attributes(
			array(
				'overlayColor' => $overlay,
				'dimRatio'     => 100,
				'className'    => $classname,
			)
		);

		return '<!-- wp:cover ' . $attributes . ' -->' . "\n"
			. '<div class="wp-block-cover ' . esc_attr( $classname ) . '"><span aria-hidden="true" class="wp-block-cover__background has-' . esc_attr( $overlay ) . '-background-color has-background-dim-100 has-background-dim"></span><div class="wp-block-cover__inner-container"></div></div>' . "\n"
			. '<!-- /wp:cover -->';
	}

	if ( '' !== $caption && 0 === strcasecmp( trim( $caption ), trim( $photo['alt'] ) ) ) {
		$photo['alt'] = '';
	}

	$attributes = serialize_block_attributes(
		array(
			'url'       => $photo['url'],
			'id'        => $photo['id'],
			'dimRatio'  => 0,
			'className' => $classname,
		)
	);

	return '<!-- wp:cover ' . $attributes . ' -->' . "\n"
		. '<div class="wp-block-cover ' . esc_attr( $classname ) . '"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><img class="wp-block-cover__image-background wp-image-' . (int) $photo['id'] . '" alt="' . esc_attr( $photo['alt'] ) . '" src="' . esc_url( $photo['url'] ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"></div></div>' . "\n"
		. '<!-- /wp:cover -->';
}

/**
 * The opening block of an inner page: a small label, the page title and a short lead, with a photo beside them
 * when one is asked for and imported.
 *
 * @param string $eyebrow Label above the title (already translated).
 * @param string $title   Page title, the page's h1 (already translated).
 * @param string $lead    One or two sentences under the title (already translated).
 * @param string $extra   Block markup placed under the heading, for example a row of chips.
 * @param string $photo   Photo slug (see bpch_theme_photo()) for a picture beside the text. When empty, or when the
 *                        photo is not in the Media Library, the page opens with text only.
 * @return string Block markup.
 */
function bpch_theme_page_intro( $eyebrow, $title, $lead, $extra = '', $photo = '' ) {
	ob_start();
	?>
	<!-- wp:group {"className":"bpch-section__head","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
	<div class="wp-block-group bpch-section__head">
		<!-- wp:paragraph {"className":"bpch-eyebrow","fontSize":"x-small"} -->
		<p class="bpch-eyebrow has-x-small-font-size"><?php echo esc_html( $eyebrow ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"level":1} -->
		<h1 class="wp-block-heading"><?php echo esc_html( $title ); ?></h1>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"fontSize":"large"} -->
		<p class="has-large-font-size"><?php echo esc_html( $lead ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
	<?php
	$head = (string) ob_get_clean();

	if ( '' !== $photo && null !== bpch_theme_photo( $photo ) ) {
		// The heading, its extras and the photo sit in one row; the row stacks on tablets and phones.
		$inner = '<!-- wp:group {"className":"bpch-intro-split","style":{"spacing":{"blockGap":"var:preset|spacing|7"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->' . "\n"
			. '<div class="wp-block-group bpch-intro-split">' . "\n"
			. '<!-- wp:group {"className":"bpch-intro-split__copy","style":{"spacing":{"blockGap":"var:preset|spacing|6"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->' . "\n"
			. '<div class="wp-block-group bpch-intro-split__copy">' . "\n"
			. $head . "\n" . $extra . "\n"
			. '</div>' . "\n"
			. '<!-- /wp:group -->' . "\n\n"
			. bpch_theme_photo_cover( $photo, 'intro' ) . "\n"
			. '</div>' . "\n"
			. '<!-- /wp:group -->';
	} else {
		$inner = $head . "\n" . $extra;
	}

	return '<!-- wp:group {"align":"full","className":"bpch-page-intro","backgroundColor":"mist","style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|8"}}},"layout":{"type":"constrained"}} -->' . "\n"
		. '<div class="wp-block-group alignfull bpch-page-intro has-mist-background-color has-background" style="padding-top:var(--wp--preset--spacing--8);padding-bottom:var(--wp--preset--spacing--8)">' . "\n"
		. $inner . "\n"
		. '</div>' . "\n"
		. '<!-- /wp:group -->';
}

/**
 * The orange band that closes a page: a heading, one sentence and two buttons. The first button goes to a page of
 * the site; the second calls the business (read from the Site Settings) unless another link is given.
 *
 * @param string                                 $heading       Band heading (already translated).
 * @param string                                 $text          One sentence under it (already translated).
 * @param string                                 $primary_label Text of the dark button.
 * @param string                                 $primary_url   Where the dark button goes.
 * @param array{label: string, url: string}|null $secondary Outline button; null for the call button.
 * @return string Block markup.
 */
function bpch_theme_cta_band( $heading, $text, $primary_label, $primary_url, $secondary = null ) {
	$business   = bpch_theme_business();
	$call_label = bpch_theme_call_label( $business['phone'] );

	if ( null === $secondary ) {
		$second_attributes = bpch_theme_call_button_attributes( array( 'className' => 'is-style-outline' ) );
		$second_url        = $business['phone_href'];
		$second_label      = $call_label;
	} else {
		$second_attributes = serialize_block_attributes( array( 'className' => 'is-style-outline' ) );
		$second_url        = $secondary['url'];
		$second_label      = $secondary['label'];
	}

	ob_start();
	?>
<!-- wp:group {"align":"full","className":"bpch-band bpch-band--cta","backgroundColor":"orange","textColor":"ink","style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|8"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-band bpch-band--cta has-ink-color has-orange-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--8);padding-bottom:var(--wp--preset--spacing--8)">
	<!-- wp:group {"className":"bpch-band__inner","style":{"spacing":{"blockGap":"var:preset|spacing|7"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group bpch-band__inner">
		<!-- wp:group {"className":"bpch-band__copy","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-band__copy">
			<!-- wp:heading -->
			<h2 class="wp-block-heading"><?php echo esc_html( $heading ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"ink"} -->
			<p class="has-ink-color has-text-color"><?php echo esc_html( $text ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:buttons {"className":"bpch-cta-row"} -->
		<div class="wp-block-buttons bpch-cta-row">
			<!-- wp:button {"backgroundColor":"navy","textColor":"white","borderColor":"navy"} -->
			<div class="wp-block-button"><a class="wp-block-button__link has-white-color has-navy-background-color has-text-color has-background has-border-color has-navy-border-color wp-element-button" href="<?php echo esc_url( $primary_url ); ?>"><?php echo esc_html( $primary_label ); ?></a></div>
			<!-- /wp:button -->

			<!-- wp:button <?php echo $second_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
			<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $second_url ); ?>"><?php echo esc_html( $second_label ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
	<?php
	return (string) ob_get_clean();
}

/**
 * A pill link to a category section of the Services page, such as "Heating · 10". The text and the link come from
 * the `bpch/service-category` binding, so the number stays right when services are added.
 *
 * @param string $slug  Category slug: heating, cooling or electrical.
 * @param string $name  Category name for the starting text (already translated).
 * @param int    $count Count to show when the category can't be read.
 * @param string $icon  Icon class suffix, e.g. "flame" (see the .bpch-mark--* icons).
 * @return string Block markup.
 */
function bpch_theme_category_chip( $slug, $name, $count, $icon ) {
	$group = bpch_theme_service_group( $slug, $count );
	$term  = taxonomy_exists( 'bpch_service_category' ) ? get_term_by( 'slug', $slug, 'bpch_service_category' ) : false;

	/* translators: 1: category name, 2: number of services in it. Shown on a chip, e.g. "Heating · 10". */
	$label = sprintf( __( '%1$s · %2$d', 'blue-peak-climate' ), $term && ! is_wp_error( $term ) ? $term->name : $name, $term && ! is_wp_error( $term ) ? (int) $term->count : (int) $count );

	$attributes = serialize_block_attributes(
		array(
			'className' => 'is-style-outline bpch-chip bpch-chip--' . $icon,
			'metadata'  => array(
				'bindings' => array(
					'url'  => array(
						'source' => 'bpch/service-category',
						'args'   => array(
							'slug' => $slug,
							'key'  => 'url',
						),
					),
					'text' => array(
						'source' => 'bpch/service-category',
						'args'   => array(
							'slug' => $slug,
							'key'  => 'name_count',
						),
					),
				),
			),
		)
	);

	return '<!-- wp:button ' . $attributes . ' -->' . "\n"
		. '<div class="wp-block-button is-style-outline bpch-chip bpch-chip--' . esc_attr( $icon ) . '"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $group['url'] ) . '">' . esc_html( $label ) . '</a></div>' . "\n"
		. '<!-- /wp:button -->';
}

/**
 * The "Emergency? Call now" chip: same pill as the category chips, with the link read from the Site Settings.
 *
 * @return string Block markup.
 */
function bpch_theme_emergency_chip() {
	$business   = bpch_theme_business();
	$attributes = serialize_block_attributes(
		array(
			'className' => 'is-style-outline bpch-chip bpch-chip--phone',
			'metadata'  => array(
				'bindings' => array(
					'url' => array(
						'source' => 'bpch/site-settings',
						'args'   => array( 'key' => 'phone' ),
					),
				),
			),
		)
	);

	return '<!-- wp:button ' . $attributes . ' -->' . "\n"
		. '<div class="wp-block-button is-style-outline bpch-chip bpch-chip--phone"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $business['phone_href'] ) . '">' . esc_html__( 'Emergency? Call now', 'blue-peak-climate' ) . '</a></div>' . "\n"
		. '<!-- /wp:button -->';
}
