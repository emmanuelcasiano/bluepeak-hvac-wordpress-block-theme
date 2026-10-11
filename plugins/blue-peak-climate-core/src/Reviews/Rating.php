<?php
/**
 * Star rating helpers and the "Review rating" block.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Reviews;

defined( 'ABSPATH' ) || exit;

/**
 * A rating is a whole number from 1 to 5. The helpers are plain functions of their input so they are easy to test.
 *
 * The block draws the stars for the review in the current Query Loop card. The stars are hidden from screen readers
 * and a visually hidden "4 out of 5 stars" carries the meaning, so the rating never depends on the symbols.
 */
final class Rating {

	/**
	 * Highest rating.
	 */
	public const MAX = 5;

	/**
	 * Lowest rating.
	 */
	public const MIN = 1;

	/**
	 * Hooks the block registration.
	 */
	public function register(): void {
		add_action( 'init', array( $this, 'register_block' ) );
	}

	/**
	 * Turns anything into a rating from 1 to 5. Junk and blanks become 5, the form's default, so an old or
	 * half-saved review isn't shown with no stars.
	 *
	 * @param mixed $value Raw value.
	 * @return int
	 */
	public static function clamp( $value ): int {
		if ( ! is_numeric( $value ) ) {
			return self::MAX;
		}

		return max( self::MIN, min( self::MAX, (int) round( (float) $value ) ) );
	}

	/**
	 * The stars as text: "★★★★☆" for 4.
	 *
	 * @param int $rating Rating, 1 to 5.
	 * @return string
	 */
	public static function stars( int $rating ): string {
		$rating = self::clamp( $rating );

		return str_repeat( '★', $rating ) . str_repeat( '☆', self::MAX - $rating );
	}

	/**
	 * "4 out of 5 stars", for screen readers.
	 *
	 * @param int $rating Rating, 1 to 5.
	 * @return string
	 */
	public static function label( int $rating ): string {
		/* translators: %d: star rating from 1 to 5. */
		return sprintf( __( '%d out of 5 stars', 'blue-peak-climate-core' ), self::clamp( $rating ) );
	}

	/**
	 * Registers the block. It is a PHP-only block (WordPress 7.0+): no JavaScript build.
	 */
	public function register_block(): void {
		register_block_type(
			'bpch/review-rating',
			array(
				'title'           => __( 'Review rating', 'blue-peak-climate-core' ),
				'description'     => __( 'The star rating of the review in this card.', 'blue-peak-climate-core' ),
				'category'        => 'design',
				'icon'            => 'star-filled',
				'uses_context'    => array( 'postId' ),
				'supports'        => array(
					'autoRegister' => true,
					'html'         => false,
					'className'    => false,
				),
				'render_callback' => array( $this, 'render' ),
			)
		);
	}

	/**
	 * Prints the stars for the current review.
	 *
	 * @param array                 $attributes Block attributes (none).
	 * @param string                $content    Inner content (none).
	 * @param \WP_Block|object|null $block      Block instance; its context holds the review's postId.
	 * @return string
	 */
	public function render( $attributes = array(), $content = '', $block = null ): string {
		unset( $attributes, $content );

		$post_id = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : 0;

		if ( $post_id < 1 ) {
			return '';
		}

		$rating = self::clamp( get_post_meta( $post_id, PostTypes::META_RATING, true ) );

		return sprintf(
			'<p %s><span aria-hidden="true">%s</span><span class="bpch-sr-only">%s</span></p>',
			get_block_wrapper_attributes( array( 'class' => 'bpch-rating' ) ),
			esc_html( self::stars( $rating ) ),
			esc_html( self::label( $rating ) )
		);
	}
}
