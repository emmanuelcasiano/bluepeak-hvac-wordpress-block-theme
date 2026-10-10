<?php
/**
 * Theme setup. Design lives in theme.json; site features belong in the blue-peak-climate-core plugin, not here.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Loads style.css inside the editor iframe so the editor matches the front end.
 */
function bpch_theme_setup() {
	add_editor_style( 'style.css' );
}
add_action( 'after_setup_theme', 'bpch_theme_setup' );

/**
 * Loads style.css on the front end, versioned so caches refresh on theme updates.
 */
function bpch_theme_enqueue_styles() {
	wp_enqueue_style( 'blue-peak-climate', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'bpch_theme_enqueue_styles' );

/**
 * Turns off the Font Library so editors can only use the theme's own fonts.
 * theme.json has no switch for this, so it is done through the editor settings.
 *
 * @param array $settings Block editor settings.
 * @return array
 */
function bpch_theme_disable_font_library( $settings ) {
	$settings['fontLibraryEnabled'] = false;
	return $settings;
}
add_filter( 'block_editor_settings_all', 'bpch_theme_disable_font_library' );

/**
 * Groups this theme's patterns under one inserter category.
 */
function bpch_theme_register_pattern_category() {
	register_block_pattern_category(
		'blue-peak-climate',
		array( 'label' => __( 'Blue Peak Climate', 'blue-peak-climate' ) )
	);
}
add_action( 'init', 'bpch_theme_register_pattern_category' );

/**
 * Business details that patterns print as the starting content of blocks bound to the Site Settings
 * (source `bpch/site-settings`). On the front end the binding replaces them with the live value, so editing
 * the settings updates every page, even where an editor has customised the header or footer.
 *
 * The values come from the blue-peak-climate-core plugin. If it is inactive the bindings do not run and the
 * placeholders below are what visitors see.
 *
 * @return array{phone: string, phone_href: string, email: string, hours: string, service_area: string}
 */
function bpch_theme_business() {
	$settings = array(
		'phone'        => '(555) 123-4567',
		'email'        => 'hello@bluepeakclimate.com',
		'hours'        => __( 'Emergency line open 24/7', 'blue-peak-climate' ),
		'service_area' => __( 'Homes across Texas', 'blue-peak-climate' ),
	);

	if ( class_exists( '\BluePeakClimate\Core\Settings\Settings' ) ) {
		foreach ( array_keys( $settings ) as $key ) {
			$settings[ $key ] = \BluePeakClimate\Core\Settings\Settings::get( $key );
		}
		$settings['phone_href'] = \BluePeakClimate\Core\Settings\Settings::phone_href( $settings['phone'] );
	} else {
		$settings['phone_href'] = 'tel:5551234567';
	}

	return $settings;
}

/**
 * Builds block markup for custom navigation links, so menus live in the theme and not in the database.
 *
 * @param array<int, array{label: string, url: string}> $items Menu items.
 * @return string Markup to echo inside a core/navigation block.
 */
function bpch_theme_nav_links_markup( array $items ) {
	$markup = '';
	foreach ( $items as $item ) {
		$markup .= get_comment_delimited_block_content(
			'core/navigation-link',
			array(
				'label'          => $item['label'],
				'url'            => $item['url'],
				'kind'           => 'custom',
				'isTopLevelLink' => true,
			),
			''
		) . "\n";
	}
	return $markup;
}

/**
 * Marks the menu link for the current page. Core only does this for links that hold a page ID,
 * and the theme's links are plain URLs.
 *
 * @param string $block_content Rendered navigation link.
 * @param array  $block         Parsed block.
 * @return string
 */
function bpch_theme_mark_current_nav_link( $block_content, $block ) {
	global $wp;

	// Links to a section of a page (#heating) are not "the current page".
	if ( empty( $block['attrs']['url'] ) || false !== strpos( $block['attrs']['url'], '#' ) || false !== strpos( $block_content, 'aria-current' ) ) {
		return $block_content;
	}

	$link    = trailingslashit( strtok( $block['attrs']['url'], '?#' ) );
	$current = trailingslashit( home_url( $wp->request ) );
	$home    = trailingslashit( home_url() );

	// The home link matches only the front page; other links also match their sub-pages.
	if ( $link !== $current && ( $link === $home || 0 !== strpos( $current, $link ) ) ) {
		return $block_content;
	}

	$tags = new WP_HTML_Tag_Processor( $block_content );
	if ( $tags->next_tag( 'li' ) ) {
		$tags->add_class( 'current-menu-item' );
	}
	if ( $tags->next_tag( 'a' ) ) {
		$tags->set_attribute( 'aria-current', 'page' );
	}
	return $tags->get_updated_html();
}
add_filter( 'render_block_core/navigation-link', 'bpch_theme_mark_current_nav_link', 10, 2 );
