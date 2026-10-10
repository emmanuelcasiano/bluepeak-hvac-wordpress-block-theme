<?php
/**
 * Block Bindings source that lets blocks read a service category: its name, live service count and page link.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Services;

defined( 'ABSPATH' ) || exit;

/**
 * Registers `bpch/service-category`. The service group cards use it so "10 services" and the link to the
 * Heating section stay right when the client adds or removes a service:
 *
 *   <!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"bpch/service-category","args":{"slug":"heating","key":"count_label"}}}}} -->
 *
 * Args: `slug` is the category slug (heating, cooling, electrical) and `key` is one of:
 *   name        - the category name
 *   count       - the number of published services, as a number
 *   count_label - the count with its unit, e.g. "10 services" (translated, singular for 1)
 *   name_count  - the name and the number for a filter chip, e.g. "Heating · 10"
 *   url         - the Services page, scrolled to the category's section (/services/#heating)
 *
 * If the category or the Services page doesn't exist the block keeps the content saved in the pattern.
 */
final class CategoryBindings {

	/**
	 * Hooks the source registration.
	 */
	public function register(): void {
		add_action( 'init', array( $this, 'register_source' ) );
	}

	/**
	 * Registers the source with WordPress.
	 */
	public function register_source(): void {
		if ( ! function_exists( 'register_block_bindings_source' ) ) {
			return;
		}

		register_block_bindings_source(
			'bpch/service-category',
			array(
				'label'              => __( 'Service category', 'blue-peak-climate-core' ),
				'get_value_callback' => array( $this, 'get_value' ),
			)
		);
	}

	/**
	 * Returns the value for one bound attribute, or null to keep the block's saved content.
	 *
	 * @param array  $args           Binding args: slug and key.
	 * @param mixed  $block_instance Block being rendered (unused).
	 * @param string $attribute_name Bound attribute, e.g. content, text, url.
	 * @return string|null
	 */
	public function get_value( array $args, $block_instance, string $attribute_name ): ?string {
		unset( $block_instance );

		$slug = isset( $args['slug'] ) && is_string( $args['slug'] ) ? $args['slug'] : '';
		$key  = isset( $args['key'] ) && is_string( $args['key'] ) ? $args['key'] : '';
		$term = '' === $slug ? false : get_term_by( 'slug', $slug, PostTypes::CATEGORY );

		if ( ! $term || is_wp_error( $term ) ) {
			return null;
		}

		// Text printed into HTML is escaped here. A URL goes through esc_url_raw(); WordPress escapes the attribute.
		$is_html = in_array( $attribute_name, array( 'content', 'text', 'caption' ), true );

		switch ( $key ) {
			case 'name':
				return $is_html ? esc_html( $term->name ) : $term->name;

			case 'count':
				return (string) (int) $term->count;

			case 'count_label':
				return self::count_label( (int) $term->count );

			case 'name_count':
				/* translators: 1: category name, 2: number of services in it. Shown on a chip, e.g. "Heating · 10". */
				$label = sprintf( __( '%1$s · %2$d', 'blue-peak-climate-core' ), $term->name, (int) $term->count );

				return $is_html ? esc_html( $label ) : $label;

			case 'url':
				return $this->section_url( $slug );

			default:
				return null;
		}
	}

	/**
	 * "10 services", or "1 service".
	 *
	 * @param int $count Number of services.
	 * @return string
	 */
	public static function count_label( int $count ): string {
		/* translators: %d: number of services in a category. */
		return sprintf( _n( '%d service', '%d services', $count, 'blue-peak-climate-core' ), $count );
	}

	/**
	 * The category's section on the Services page, e.g. /services/#heating.
	 *
	 * @param string $slug Category slug.
	 * @return string|null Null when the Services page link is not available.
	 */
	private function section_url( string $slug ): ?string {
		$archive = get_post_type_archive_link( PostTypes::POST_TYPE );

		return $archive ? esc_url_raw( $archive . '#' . $slug ) : null;
	}
}
