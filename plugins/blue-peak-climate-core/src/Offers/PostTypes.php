<?php
/**
 * Registers the Offer post type and its meta.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Offers;

defined( 'ABSPATH' ) || exit;

/**
 * Offers are content the office edits, so they live in the plugin and survive a theme change.
 *
 * They have no page of their own (not public, no archive, no rewrite rules): they are listed on the Offers page by a
 * Query Loop. That also keeps thin one-paragraph pages out of search results.
 */
final class PostTypes {

	/**
	 * Post type key.
	 */
	public const POST_TYPE = 'bpch_offer';

	/**
	 * Last day the offer is valid, as Y-m-d. Empty means it never expires.
	 */
	public const META_EXPIRES = 'expires_on';

	/**
	 * Small print shown under the offer ("New customers only. Not valid with other offers.").
	 */
	public const META_TERMS = 'terms';

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
	 * The Offer post type.
	 */
	private function register_post_type(): void {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'              => array(
					'name'               => __( 'Offers', 'blue-peak-climate-core' ),
					'singular_name'      => __( 'Offer', 'blue-peak-climate-core' ),
					'add_new_item'       => __( 'Add new offer', 'blue-peak-climate-core' ),
					'edit_item'          => __( 'Edit offer', 'blue-peak-climate-core' ),
					'new_item'           => __( 'New offer', 'blue-peak-climate-core' ),
					'search_items'       => __( 'Search offers', 'blue-peak-climate-core' ),
					'not_found'          => __( 'No offers found.', 'blue-peak-climate-core' ),
					'not_found_in_trash' => __( 'No offers found in the trash.', 'blue-peak-climate-core' ),
					'all_items'          => __( 'All offers', 'blue-peak-climate-core' ),
				),
				'public'              => false,

				/*
				 * Queryable but with no query var and no rewrite rules: the Query Loop block only lists "viewable" post
				 * types (it falls back to ordinary posts otherwise). A single offer or the offers archive has no page;
				 * ListingOnly redirects them to the Offers page.
				 */
				'publicly_queryable'  => true,
				'query_var'           => false,
				'exclude_from_search' => true,
				'show_ui'             => true,
				'show_in_nav_menus'   => false,
				'has_archive'         => false,
				'rewrite'             => false,
				'show_in_rest'        => true,
				'rest_base'           => 'offers',
				'menu_icon'           => 'dashicons-tag',
				'menu_position'       => 22,

				// No 'editor' support: the same plain form as services. The excerpt is shown as "Description".
				'supports'            => array( 'title', 'excerpt', 'page-attributes' ),
			)
		);
	}

	/**
	 * The expiry date and small print. Both are edited in the Offer details box (EditScreen); show_in_rest lets blocks
	 * read them through the core `core/post-meta` binding source.
	 */
	private function register_meta(): void {
		register_post_meta(
			self::POST_TYPE,
			self::META_EXPIRES,
			array(
				'type'              => 'string',
				'description'       => __( 'Last day the offer is valid (YYYY-MM-DD). Blank means it never expires.', 'blue-peak-climate-core' ),
				'single'            => true,
				'default'           => '',
				'show_in_rest'      => true,
				'sanitize_callback' => array( self::class, 'sanitize_date' ),
				'auth_callback'     => array( self::class, 'can_edit_meta' ),
			)
		);

		register_post_meta(
			self::POST_TYPE,
			self::META_TERMS,
			array(
				'type'              => 'string',
				'description'       => __( 'Small print shown under the offer.', 'blue-peak-climate-core' ),
				'single'            => true,
				'default'           => '',
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => array( self::class, 'can_edit_meta' ),
			)
		);
	}

	/**
	 * Keeps a real Y-m-d date, or an empty string. Anything else (a typo, 2026-02-30, "next friday") is dropped, because
	 * a bad value stored here would either hide the offer for the wrong reason or never expire it.
	 *
	 * @param mixed $value Raw value.
	 * @return string
	 */
	public static function sanitize_date( $value ): string {
		$value = is_string( $value ) ? trim( $value ) : '';

		return Expiry::is_valid_date( $value ) ? $value : '';
	}

	/**
	 * Only people who can edit the offer may change its meta (the default would allow any logged-in editor of any
	 * post, through REST).
	 *
	 * @param bool   $allowed  Unused.
	 * @param string $meta_key Unused.
	 * @param int    $post_id  Offer being edited.
	 * @return bool
	 */
	public static function can_edit_meta( $allowed, $meta_key, $post_id ): bool {
		unset( $allowed, $meta_key );

		return current_user_can( 'edit_post', $post_id );
	}
}
