<?php
/**
 * Building blocks for the legal pages (Privacy Policy and Terms).
 *
 * The patterns `page-privacy` and `page-terms` hold the text. These helpers turn it into block markup: a draft notice,
 * a "Last updated" line, a table of contents that links to each section, and the sections themselves (an h2 with an
 * anchor, then paragraphs, lists and marked placeholders). Everything printed here is escaped or passed through
 * wp_kses_post(), and the text stays translatable because the patterns call __() on every string.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * The text that stands in for the date until the creation script writes the real one (ai/scripts/create-legal-pages.php).
 * A person who inserts the pattern by hand sees it and fills it in.
 *
 * @return string
 */
function bpch_theme_legal_date_token() {
	return __( '[Month day, year]', 'blue-peak-climate' );
}

/**
 * A paragraph block.
 *
 * @param string $html       Text, with links or <strong> if needed (passed through wp_kses_post()).
 * @param string $class_name Optional extra class.
 * @return string Block markup.
 */
function bpch_theme_legal_p( $html, $class_name = '' ) {
	$attributes = '' === $class_name ? '' : ' ' . serialize_block_attributes( array( 'className' => $class_name ) );
	$class      = '' === $class_name ? '' : ' class="' . esc_attr( $class_name ) . '"';

	return '<!-- wp:paragraph' . $attributes . " -->\n<p" . $class . '>' . wp_kses_post( $html ) . "</p>\n<!-- /wp:paragraph -->";
}

/**
 * A marked note: a small dashed box that tells the owner or lawyer what is still missing ("[Placeholder]") or that the
 * page is a draft ("[Draft]"). The creation script lists every one it finds, so none is forgotten before launch.
 *
 * @param string $text What is missing, or the draft warning. The label is added in front.
 * @param string $kind "placeholder" (default) or "draft".
 * @return string Block markup.
 */
function bpch_theme_legal_note( $text, $kind = 'placeholder' ) {
	$attributes = serialize_block_attributes(
		array(
			'className' => 'bpch-placeholder-note',
			'fontSize'  => 'small',
		)
	);

	if ( 'draft' === $kind ) {
		/* translators: %s: explanation that the page is a draft. */
		$label = sprintf( __( '[Draft] %s', 'blue-peak-climate' ), $text );
	} else {
		/* translators: %s: what is missing from a draft page. */
		$label = sprintf( __( '[Placeholder] %s', 'blue-peak-climate' ), $text );
	}

	return '<!-- wp:paragraph ' . $attributes . " -->\n<p class=\"bpch-placeholder-note has-small-font-size\">" . esc_html( $label ) . "</p>\n<!-- /wp:paragraph -->";
}

/**
 * A list block.
 *
 * @param string[] $items      List items (passed through wp_kses_post()).
 * @param bool     $ordered    True for a numbered list.
 * @param string   $class_name Optional extra class.
 * @return string Block markup.
 */
function bpch_theme_legal_list( array $items, $ordered = false, $class_name = '' ) {
	$tag        = $ordered ? 'ol' : 'ul';
	$attributes = array();
	if ( $ordered ) {
		$attributes['ordered'] = true;
	}
	if ( '' !== $class_name ) {
		$attributes['className'] = $class_name;
	}

	$comment = array() === $attributes ? '' : ' ' . serialize_block_attributes( $attributes );
	$class   = 'wp-block-list' . ( '' === $class_name ? '' : ' ' . $class_name );

	$markup = '';
	foreach ( $items as $item ) {
		$markup .= "<!-- wp:list-item -->\n<li>" . wp_kses_post( $item ) . "</li>\n<!-- /wp:list-item -->\n";
	}

	return '<!-- wp:list' . $comment . " -->\n<" . $tag . ' class="' . esc_attr( $class ) . '">' . $markup . '</' . $tag . ">\n<!-- /wp:list -->";
}

/**
 * A heading block.
 *
 * @param string $text   Heading text (plain text, escaped here).
 * @param int    $level  2 or 3.
 * @param string $anchor Optional id, so the table of contents can link to it.
 * @return string Block markup.
 */
function bpch_theme_legal_heading( $text, $level = 2, $anchor = '' ) {
	$attributes = 2 === (int) $level ? '' : ' ' . serialize_block_attributes( array( 'level' => (int) $level ) );
	$id         = '' === $anchor ? '' : ' id="' . esc_attr( $anchor ) . '"';

	return '<!-- wp:heading' . $attributes . " -->\n<h" . (int) $level . ' class="wp-block-heading"' . $id . '>' . esc_html( $text ) . '</h' . (int) $level . ">\n<!-- /wp:heading -->";
}

/**
 * One section of a legal page: an h2 with an anchor, then its blocks.
 *
 * @param string   $id      Anchor, lowercase letters and hyphens.
 * @param string   $heading Section heading (plain text).
 * @param string[] $blocks  Block markup made by the helpers above.
 * @return string Block markup.
 */
function bpch_theme_legal_section( $id, $heading, array $blocks ) {
	return bpch_theme_legal_heading( $heading, 2, $id ) . "\n\n" . implode( "\n\n", $blocks );
}

/**
 * The business name, email and phone at the end of a legal page. Each line is bound to the Site Settings, so the
 * numbers on the page can never differ from the ones in the header and footer.
 *
 * @return string Block markup.
 */
function bpch_theme_legal_contact() {
	$business = bpch_theme_business();
	$name     = class_exists( '\BluePeakClimate\Core\Settings\Settings' ) ? trim( (string) \BluePeakClimate\Core\Settings\Settings::get( 'legal_name' ) ) : '';
	if ( '' === $name ) {
		$name = get_bloginfo( 'name' );
	}

	$bind = static function ( $key, $format ) {
		return array(
			'bindings' => array(
				'content' => array(
					'source' => 'bpch/site-settings',
					'args'   => array(
						'key'    => $key,
						'format' => $format,
					),
				),
			),
		);
	};

	$name_attributes  = serialize_block_attributes(
		array(
			'className' => 'bpch-legal__name',
			'metadata'  => $bind( 'legal_name', 'text' ),
		)
	);
	$email_attributes = serialize_block_attributes(
		array(
			'className' => 'bpch-icon bpch-icon--mail',
			'metadata'  => $bind( 'email', 'link' ),
		)
	);
	$phone_attributes = serialize_block_attributes(
		array(
			'className' => 'bpch-icon bpch-icon--phone',
			'metadata'  => $bind( 'phone', 'link' ),
		)
	);

	$email_link = '<a href="mailto:' . esc_attr( antispambot( $business['email'] ) ) . '">' . esc_html( antispambot( $business['email'] ) ) . '</a>';
	$phone_link = '<a href="' . esc_url( $business['phone_href'] ) . '">' . esc_html( $business['phone'] ) . '</a>';

	return '<!-- wp:group {"className":"bpch-legal__contact","style":{"spacing":{"blockGap":"var:preset|spacing|2"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->' . "\n"
		. '<div class="wp-block-group bpch-legal__contact">' . "\n"
		. '<!-- wp:paragraph ' . $name_attributes . " -->\n"
		. '<p class="bpch-legal__name">' . esc_html( $name ) . "</p>\n"
		. "<!-- /wp:paragraph -->\n\n"
		. '<!-- wp:paragraph ' . $email_attributes . " -->\n"
		. '<p class="bpch-icon bpch-icon--mail">' . $email_link . "</p>\n"
		. "<!-- /wp:paragraph -->\n\n"
		. '<!-- wp:paragraph ' . $phone_attributes . " -->\n"
		. '<p class="bpch-icon bpch-icon--phone">' . $phone_link . "</p>\n"
		. "<!-- /wp:paragraph -->\n"
		. "</div>\n"
		. '<!-- /wp:group -->';
}

/**
 * A whole legal page: the page intro, the draft notice, the date, the table of contents and the sections.
 *
 * @param string                                                           $eyebrow  Small label above the title.
 * @param string                                                           $title    Page title (the h1).
 * @param string                                                           $lead     One or two sentences under the title.
 * @param string                                                           $draft    Text of the draft notice (without "[Draft]").
 * @param array<int, array{id: string, heading: string, blocks: string[]}> $sections Sections in reading order.
 * @return string Block markup.
 */
function bpch_theme_legal_page( $eyebrow, $title, $lead, $draft, array $sections ) {
	$toc = array();
	foreach ( $sections as $section ) {
		$toc[] = '<a href="#' . esc_attr( $section['id'] ) . '">' . esc_html( $section['heading'] ) . '</a>';
	}

	/* translators: %s: date the text was last changed, for example "October 13, 2026". */
	$updated = sprintf( __( 'Last updated: %s', 'blue-peak-climate' ), bpch_theme_legal_date_token() );

	$body   = array();
	$body[] = bpch_theme_legal_note( $draft, 'draft' );
	$body[] = bpch_theme_legal_p( '<strong>' . esc_html( $updated ) . '</strong>', 'bpch-legal__updated' );
	$body[] = bpch_theme_legal_p( '<strong>' . esc_html__( 'On this page', 'blue-peak-climate' ) . '</strong>', 'bpch-legal__toc-label' );
	$body[] = bpch_theme_legal_list( $toc, true, 'bpch-legal__toc' );

	foreach ( $sections as $section ) {
		$body[] = bpch_theme_legal_section( $section['id'], $section['heading'], $section['blocks'] );
	}

	return bpch_theme_page_intro( $eyebrow, $title, $lead ) . "\n\n"
		. '<!-- wp:group {"align":"full","className":"bpch-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|9"}}},"layout":{"type":"constrained"}} -->' . "\n"
		. '<div class="wp-block-group alignfull bpch-section" style="padding-top:var(--wp--preset--spacing--8);padding-bottom:var(--wp--preset--spacing--9)">' . "\n"
		. '<!-- wp:group {"className":"bpch-legal","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"constrained","contentSize":"720px","justifyContent":"left"}} -->' . "\n"
		. '<div class="wp-block-group bpch-legal">' . "\n\n"
		. implode( "\n\n", $body ) . "\n\n"
		. "</div>\n"
		. "<!-- /wp:group -->\n"
		. "</div>\n"
		. '<!-- /wp:group -->';
}
