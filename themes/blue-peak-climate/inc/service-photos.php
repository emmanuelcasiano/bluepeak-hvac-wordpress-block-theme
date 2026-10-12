<?php
/**
 * A photo for every service page: the featured image the office chose, or else a stock photo for the service's
 * category (Heating, Cooling, Electrical) from the Media Library.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Photo slug (see bpch_theme_photo()) for each service category, by term slug.
 *
 * @return array<string, string>
 */
function bpch_theme_service_photo_slugs() {
	return array(
		'heating'    => 'service-heating',
		'cooling'    => 'service-technician',
		'electrical' => 'service-electrical',
	);
}

/**
 * Gives a service with no featured image its category photo, on the public site only.
 *
 * The Featured image block on the single Service template reads `_thumbnail_id`, so this answers for the post
 * without saving anything. It stays off in WP Admin and in REST requests, so the edit form never shows the
 * fallback as a chosen image and saving a service cannot store it.
 *
 * @param mixed  $value    Null to let WordPress read the meta, as it does by default.
 * @param int    $post_id  Post ID.
 * @param string $meta_key Meta key being read.
 * @param bool   $single   Whether one value was asked for.
 * @return mixed The photo's attachment ID (or an array holding it when $single is false), or $value unchanged.
 */
function bpch_theme_service_photo_fallback( $value, $post_id, $meta_key, $single ) {
	static $busy = false;

	if ( '_thumbnail_id' !== $meta_key || null !== $value || $busy || is_admin() || wp_is_serving_rest_request() ) {
		return $value;
	}

	if ( 'bpch_service' !== get_post_type( $post_id ) ) {
		return $value;
	}

	// Read the real value once, without this filter, to see whether a featured image is set.
	$busy  = true;
	$saved = get_post_meta( $post_id, '_thumbnail_id', true );
	$busy  = false;

	if ( ! empty( $saved ) ) {
		return $value;
	}

	$photo_slugs = bpch_theme_service_photo_slugs();

	foreach ( (array) get_the_terms( $post_id, 'bpch_service_category' ) as $term ) {
		if ( $term instanceof WP_Term && isset( $photo_slugs[ $term->slug ] ) ) {
			$photo = bpch_theme_photo( $photo_slugs[ $term->slug ] );

			if ( $photo ) {
				return $single ? $photo['id'] : array( $photo['id'] );
			}
		}
	}

	return $value;
}
add_filter( 'get_post_metadata', 'bpch_theme_service_photo_fallback', 10, 4 );
