<?php
/**
 * Registers the Review post type and its meta.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Reviews;

defined( 'ABSPATH' ) || exit;

/**
 * Customer reviews are content the office edits, so they live in the plugin and survive a theme change. The Reviews
 * page lists them with a Query Loop, and the Home review carousel (Day 3) reads the same posts.
 *
 * Like offers they have no page of their own (not public, no archive, no rewrite rules). The review text is the
 * excerpt, shown in the form as "Review".
 */
final class PostTypes {

	/**
	 * Post type key.
	 */
	public const POST_TYPE = 'bpch_review';

	/**
	 * Star rating, 1 to 5.
	 */
	public const META_RATING = 'rating';

	/**
	 * Who wrote it, as the customer wants to be shown: "Homeowner, Texas". Not a full name unless they agreed.
	 */
	public const META_REVIEWER = 'reviewer';

	/**
	 * What the review is about, in words: "Emergency AC repair". Optional.
	 */
	public const META_SERVICE = 'reviewed_service';

	/**
	 * Hooks the registration.
	 */
	public function register(): void {
		add_action( 'init', array( $this, 'register_all' ) );
	}

	/**
	 * Registers the type and its meta.
	 */
	public function register_all(): void {
		$this->register_post_type();
		$this->register_meta();
	}

	/**
	 * The Review post type.
	 */
	private function register_post_type(): void {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'              => array(
					'name'               => __( 'Reviews', 'blue-peak-climate-core' ),
					'singular_name'      => __( 'Review', 'blue-peak-climate-core' ),
					'add_new_item'       => __( 'Add new review', 'blue-peak-climate-core' ),
					'edit_item'          => __( 'Edit review', 'blue-peak-climate-core' ),
					'new_item'           => __( 'New review', 'blue-peak-climate-core' ),
					'search_items'       => __( 'Search reviews', 'blue-peak-climate-core' ),
					'not_found'          => __( 'No reviews found.', 'blue-peak-climate-core' ),
					'not_found_in_trash' => __( 'No reviews found in the trash.', 'blue-peak-climate-core' ),
					'all_items'          => __( 'All reviews', 'blue-peak-climate-core' ),
				),
				'public'              => false,

				// Queryable so the Query Loop block can list reviews; see Offers\PostTypes. ListingOnly redirects single reviews.
				'publicly_queryable'  => true,
				'query_var'           => false,
				'exclude_from_search' => true,
				'show_ui'             => true,
				'show_in_nav_menus'   => false,
				'has_archive'         => false,
				'rewrite'             => false,
				'show_in_rest'        => true,
				'rest_base'           => 'reviews',
				'menu_icon'           => 'dashicons-star-filled',
				'menu_position'       => 21,

				// No 'editor' support: the same plain form as services. The excerpt is shown as "Review".
				'supports'            => array( 'title', 'excerpt', 'page-attributes' ),
			)
		);
	}

	/**
	 * The rating, the reviewer label and the service. They are edited in the Review details box (EditScreen);
	 * show_in_rest lets blocks read them through the core `core/post-meta` binding source.
	 */
	private function register_meta(): void {
		register_post_meta(
			self::POST_TYPE,
			self::META_RATING,
			array(
				'type'              => 'integer',
				'description'       => __( 'Star rating from 1 to 5.', 'blue-peak-climate-core' ),
				'single'            => true,
				'default'           => Rating::MAX,
				'show_in_rest'      => true,
				'sanitize_callback' => array( Rating::class, 'clamp' ),
				'auth_callback'     => array( self::class, 'can_edit_meta' ),
			)
		);

		foreach ( array( self::META_REVIEWER, self::META_SERVICE ) as $key ) {
			register_post_meta(
				self::POST_TYPE,
				$key,
				array(
					'type'              => 'string',
					'single'            => true,
					'default'           => '',
					'show_in_rest'      => true,
					'sanitize_callback' => 'sanitize_text_field',
					'auth_callback'     => array( self::class, 'can_edit_meta' ),
				)
			);
		}
	}

	/**
	 * Only people who can edit the review may change its meta (the default would allow any logged-in editor of any
	 * post, through REST).
	 *
	 * @param bool   $allowed  Unused.
	 * @param string $meta_key Unused.
	 * @param int    $post_id  Review being edited.
	 * @return bool
	 */
	public static function can_edit_meta( $allowed, $meta_key, $post_id ): bool {
		unset( $allowed, $meta_key );

		return current_user_can( 'edit_post', $post_id );
	}
}
