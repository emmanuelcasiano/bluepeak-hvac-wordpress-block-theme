<?php
/**
 * Block Bindings source that lets the offer cards print "Ends June 30" from the offer's expiry date.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Offers;

defined( 'ABSPATH' ) || exit;

/**
 * Registers `bpch/offer`. Used inside the Offers Query Loop, where each card is one offer:
 *
 *   <!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"bpch/offer","args":{"key":"ends_label"}}}}} -->
 *
 * Args: `key` is `ends_label`, "Ends June 30, 2026" (or "Ends today" on the last day). An offer with no expiry date
 * gives an empty string, so the label shows nothing.
 *
 * The date is formatted here because the core `core/post-meta` source prints the raw value (2026-06-30).
 */
final class Bindings {

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
			'bpch/offer',
			array(
				'label'              => __( 'Offer', 'blue-peak-climate-core' ),
				'uses_context'       => array( 'postId' ),
				'get_value_callback' => array( $this, 'get_value' ),
			)
		);
	}

	/**
	 * Returns the value for one bound attribute, or null to keep the block's saved content.
	 *
	 * @param array                 $args           Binding args: key.
	 * @param \WP_Block|object|null $block_instance Block being rendered; its context holds the current offer's postId.
	 * @param string                $attribute_name Bound attribute, e.g. content.
	 * @return string|null
	 */
	public function get_value( array $args, $block_instance, string $attribute_name ): ?string {
		$key     = isset( $args['key'] ) && is_string( $args['key'] ) ? $args['key'] : '';
		$post_id = isset( $block_instance->context['postId'] ) ? (int) $block_instance->context['postId'] : 0;

		if ( 'ends_label' !== $key || $post_id < 1 ) {
			return null;
		}

		$label = self::ends_label( (string) get_post_meta( $post_id, PostTypes::META_EXPIRES, true ), Expiry::today() );

		// Text printed into HTML is escaped here; WordPress escapes other attributes itself.
		return in_array( $attribute_name, array( 'content', 'text', 'caption' ), true ) ? esc_html( $label ) : $label;
	}

	/**
	 * "Ends June 30, 2026", "Ends today", or an empty string when there is no (valid) date.
	 *
	 * @param string $expires_on Stored date (Y-m-d) or an empty string.
	 * @param string $today      Today (Y-m-d).
	 * @return string
	 */
	public static function ends_label( string $expires_on, string $today ): string {
		if ( ! Expiry::is_valid_date( $expires_on ) ) {
			return '';
		}

		if ( $expires_on === $today ) {
			return __( 'Ends today', 'blue-peak-climate-core' );
		}

		/* translators: %s: the last day the offer is valid, e.g. "June 30, 2026". */
		return sprintf( __( 'Ends %s', 'blue-peak-climate-core' ), mysql2date( get_option( 'date_format' ), $expires_on ) );
	}
}
