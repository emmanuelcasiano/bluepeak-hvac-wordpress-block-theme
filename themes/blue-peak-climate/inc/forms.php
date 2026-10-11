<?php
/**
 * Fluent Forms: where the service request form is found and how it is styled and labelled.
 *
 * Fluent Forms is a third-party plugin, so nothing here edits it. The theme uses its documented hooks, and every call
 * is safe if the plugin is deactivated (the hooks never fire and the Contact page falls back to a phone and email note).
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Title of the form that ai/scripts/create-contact-form.php creates. It is how the theme finds the form, because the
 * numeric ID differs on every copy of the site.
 */
const BPCH_THEME_SERVICE_REQUEST_FORM = 'Service request';

/**
 * ID of the service request form on this site.
 *
 * @return int The form ID, or 0 when Fluent Forms is inactive or the form has not been created yet.
 */
function bpch_theme_service_request_form_id() {
	global $wpdb;

	$cache_key = 'service_request_form_id';
	$found     = wp_cache_get( $cache_key, 'blue-peak-climate' );

	if ( false !== $found ) {
		return (int) $found;
	}

	$id = 0;

	if ( defined( 'FLUENTFORM' ) ) {
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Fluent Forms has no lookup by title; cached for the request just above.
		$id = (int) $wpdb->get_var(
			$wpdb->prepare(
				'SELECT id FROM %i WHERE title = %s ORDER BY id ASC LIMIT 1',
				$wpdb->prefix . 'fluentform_forms',
				BPCH_THEME_SERVICE_REQUEST_FORM
			)
		);
	}

	wp_cache_set( $cache_key, $id, 'blue-peak-climate' );

	return $id;
}

/**
 * Loads the form stylesheet only on pages where Fluent Forms loaded its own, and after it so same-weight rules win.
 *
 * It runs on wp_head, not while the form is built: a block theme renders the page content first and prints the head
 * afterwards, by which time Fluent Forms has queued its styles. Queuing ours earlier would print it first.
 */
function bpch_theme_enqueue_form_styles() {
	if ( ! wp_style_is( 'fluent-form-styles', 'enqueued' ) ) {
		return;
	}

	$deps = array_values(
		array_filter(
			array( 'fluent-form-styles', 'fluentform-public-default' ),
			static function ( $handle ) {
				return wp_style_is( $handle, 'enqueued' );
			}
		)
	);

	wp_enqueue_style(
		'blue-peak-climate-forms',
		get_theme_file_uri( 'assets/css/forms.css' ),
		$deps,
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_head', 'bpch_theme_enqueue_form_styles', 1 );

/**
 * Names a group of radio buttons for screen readers. Fluent Forms prints the group's question as a label that points
 * at nothing, so a screen reader would read "Morning, radio button" without saying what is being asked. The label gets
 * an ID and the group a `radiogroup` role that points at it.
 *
 * @param string   $html Field markup.
 * @param mixed    $data Field definition. Fluent Forms 6.2 passes the markup here again instead of the definition, so the
 *                       field name is read from the markup.
 * @param stdClass $form Form being rendered.
 * @return string
 */
function bpch_theme_name_radio_group( $html, $data, $form ) {
	$name = '';

	if ( is_string( $html ) && 1 === preg_match( '/<input\s[^>]*\bname="([^"]+)"/', $html, $found ) ) {
		$name = sanitize_key( $found[1] );
	}

	if ( '' === $name || empty( $form->id ) ) {
		return $html;
	}

	$label_id = 'label_ff_' . (int) $form->id . '_' . $name;

	// The question: the first label, which is the field's own label and has no `for`.
	$html = preg_replace( '/<label(?![^>]*\sfor=)/', '<label id="' . esc_attr( $label_id ) . '"', $html, 1 );
	$html = preg_replace(
		"/<div class='ff-el-input--content'>/",
		"<div class='ff-el-input--content' role='radiogroup' aria-labelledby='" . esc_attr( $label_id ) . "'>",
		$html,
		1
	);

	return $html;
}
add_filter( 'fluentform/rendering_field_html_input_radio', 'bpch_theme_name_radio_group', 10, 3 );
