<?php
/**
 * The "Icon" block: a round icon that an editor picks from two lists in the sidebar, instead of an empty Group
 * block whose icon is a CSS class they can't see or change.
 *
 * It is a PHP-only block (WordPress 7.0+): no JavaScript build, and the sidebar controls are made from the
 * attributes below. The drawing itself is CSS in style.css (.bpch-mark), so the design stays in one place.
 * Kept in the theme because the CSS is here; if the theme is switched the block shows as missing.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Icon names an editor can pick. Each needs a `.bpch-mark--<name>` rule in style.css.
 *
 * @return string[]
 */
function bpch_theme_icon_choices() {
	return array( 'flame', 'snowflake', 'zap', 'phone', 'mail', 'pin', 'clock', 'clipboard', 'file', 'wrench', 'truck', 'scale', 'house' );
}

/**
 * Colours an editor can pick, mapped to the CSS class suffix that draws them (`.bpch-mark--<suffix>`). The picker
 * shows the names on the left, so they say what the colour is.
 *
 * @return array<string, string>
 */
function bpch_theme_icon_tones() {
	return array(
		'light'  => 'soft',
		'orange' => 'warm',
		'blue'   => 'cool',
		'navy'   => 'navy',
	);
}

/**
 * Prints the icon. Both values are checked against the lists, so nothing else reaches the class attribute.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function bpch_theme_render_icon_block( $attributes ) {
	$icon = isset( $attributes['icon'] ) && in_array( $attributes['icon'], bpch_theme_icon_choices(), true ) ? $attributes['icon'] : 'flame';
	$tone = isset( $attributes['tone'] ) && isset( bpch_theme_icon_tones()[ $attributes['tone'] ] ) ? bpch_theme_icon_tones()[ $attributes['tone'] ] : 'soft';

	return sprintf(
		'<span %s></span>',
		get_block_wrapper_attributes(
			array(
				'class'       => 'bpch-mark bpch-mark--' . $tone . ' bpch-mark--' . $icon,
				'aria-hidden' => 'true',
			)
		)
	);
}

/**
 * Registers the block.
 */
function bpch_theme_register_icon_block() {
	register_block_type(
		'bpch/icon',
		array(
			'title'           => __( 'Icon', 'blue-peak-climate' ),
			'description'     => __( 'A round icon. Choose the picture and the colour in the sidebar.', 'blue-peak-climate' ),
			'category'        => 'design',
			'icon'            => 'star-filled',
			'attributes'      => array(
				'icon' => array(
					'type'    => 'string',
					'enum'    => bpch_theme_icon_choices(),
					'default' => 'flame',
				),
				'tone' => array(
					'type'    => 'string',
					'enum'    => array_keys( bpch_theme_icon_tones() ),
					'default' => 'light',
				),
			),
			'supports'        => array(
				'autoRegister' => true,
				'html'         => false,
				'className'    => false,
			),
			'render_callback' => 'bpch_theme_render_icon_block',
		)
	);
}
add_action( 'init', 'bpch_theme_register_icon_block' );
