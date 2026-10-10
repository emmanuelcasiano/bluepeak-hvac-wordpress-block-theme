<?php
/**
 * Registers the Service post type, its two taxonomies and its meta.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Services;

defined( 'ABSPATH' ) || exit;

/**
 * The content model for services. It lives in the plugin, not the theme, so the content survives a theme change.
 *
 * The type tag (Repair, Replace, ...) is a taxonomy and not meta, so the archive and the symptom picker can filter
 * by it and the core Post Terms block can print it.
 */
final class PostTypes {

	/**
	 * Post type key. Prefixed as the coding rules require; the public URLs stay /services/.
	 */
	public const POST_TYPE = 'bpch_service';

	/**
	 * Heating / Cooling / Electrical.
	 */
	public const CATEGORY = 'bpch_service_category';

	/**
	 * Repair / Replace / Install / Maintain / Air quality / Electrical.
	 */
	public const TYPE = 'bpch_service_type';

	/**
	 * Starting-price text, shown as typed (for example "From $89").
	 */
	public const META_PRICE = 'price_from';

	/**
	 * Hooks the registration.
	 */
	public function register(): void {
		add_action( 'init', array( $this, 'register_all' ) );
	}

	/**
	 * Registers everything. Public so the installer can call it before flushing rewrite rules on activation.
	 */
	public function register_all(): void {
		$this->register_post_type();
		$this->register_taxonomies();
		$this->register_meta();
	}

	/**
	 * The Service post type.
	 */
	private function register_post_type(): void {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'        => array(
					'name'               => __( 'Services', 'blue-peak-climate-core' ),
					'singular_name'      => __( 'Service', 'blue-peak-climate-core' ),
					'add_new_item'       => __( 'Add new service', 'blue-peak-climate-core' ),
					'edit_item'          => __( 'Edit service', 'blue-peak-climate-core' ),
					'new_item'           => __( 'New service', 'blue-peak-climate-core' ),
					'view_item'          => __( 'View service', 'blue-peak-climate-core' ),
					'search_items'       => __( 'Search services', 'blue-peak-climate-core' ),
					'not_found'          => __( 'No services found.', 'blue-peak-climate-core' ),
					'not_found_in_trash' => __( 'No services found in the trash.', 'blue-peak-climate-core' ),
					'all_items'          => __( 'All services', 'blue-peak-climate-core' ),
				),
				'public'        => true,
				'has_archive'   => 'services',
				'rewrite'       => array(
					'slug'       => 'services',
					'with_front' => false,
				),
				'show_in_rest'  => true,
				'rest_base'     => 'services',
				'menu_icon'     => 'dashicons-admin-tools',
				'menu_position' => 20,

				/*
				 * No 'editor' support: WordPress then shows the classic form (title and fields) instead of the block
				 * editor, which is what the office staff wants for services. page-attributes gives the Order field;
				 * the excerpt is shown as "Description" (see EditScreen).
				 */
				'supports'      => array( 'title', 'excerpt', 'thumbnail', 'page-attributes' ),
			)
		);
	}

	/**
	 * The category and type taxonomies.
	 */
	private function register_taxonomies(): void {
		register_taxonomy(
			self::CATEGORY,
			self::POST_TYPE,
			array(
				'labels'            => array(
					'name'          => __( 'Service categories', 'blue-peak-climate-core' ),
					'singular_name' => __( 'Service category', 'blue-peak-climate-core' ),
					'add_new_item'  => __( 'Add new service category', 'blue-peak-climate-core' ),
					'edit_item'     => __( 'Edit service category', 'blue-peak-climate-core' ),
					'search_items'  => __( 'Search service categories', 'blue-peak-climate-core' ),
					'all_items'     => __( 'All service categories', 'blue-peak-climate-core' ),
				),
				'hierarchical'      => true,
				'public'            => true,
				'show_in_rest'      => true,
				'rest_base'         => 'service-categories',
				'show_admin_column' => true,
				'meta_box_cb'       => array( EditScreen::class, 'render_term_choice' ),
				'rewrite'           => array(
					'slug'       => 'services/category',
					'with_front' => false,
				),
			)
		);

		register_taxonomy(
			self::TYPE,
			self::POST_TYPE,
			array(
				'labels'            => array(
					'name'                       => __( 'Service types', 'blue-peak-climate-core' ),
					'singular_name'              => __( 'Service type', 'blue-peak-climate-core' ),
					'add_new_item'               => __( 'Add new service type', 'blue-peak-climate-core' ),
					'edit_item'                  => __( 'Edit service type', 'blue-peak-climate-core' ),
					'search_items'               => __( 'Search service types', 'blue-peak-climate-core' ),
					'all_items'                  => __( 'All service types', 'blue-peak-climate-core' ),
					'separate_items_with_commas' => __( 'Separate types with commas', 'blue-peak-climate-core' ),
				),
				// Hierarchical only so the edit form can save a chosen term by ID (tag-style taxonomies save by name).
				'hierarchical'      => true,
				'public'            => true,
				'show_in_rest'      => true,
				'rest_base'         => 'service-types',
				'show_admin_column' => true,
				'meta_box_cb'       => array( EditScreen::class, 'render_term_choice' ),
				'rewrite'           => array(
					'slug'       => 'services/type',
					'with_front' => false,
				),
			)
		);
	}

	/**
	 * The starting-price meta. It is edited in the Starting price box (EditScreen). show_in_rest is what lets blocks
	 * read it through the core `core/post-meta` binding source.
	 */
	private function register_meta(): void {
		register_post_meta(
			self::POST_TYPE,
			self::META_PRICE,
			array(
				'type'              => 'string',
				'description'       => __( 'Starting price, shown as typed. For example "From $89".', 'blue-peak-climate-core' ),
				'single'            => true,
				'default'           => '',
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => array( self::class, 'can_edit_meta' ),
			)
		);
	}

	/**
	 * Only people who can edit the service may change its meta (the default would allow any logged-in editor of
	 * any post, through REST).
	 *
	 * @param bool   $allowed  Unused.
	 * @param string $meta_key Unused.
	 * @param int    $post_id  Service being edited.
	 * @return bool
	 */
	public static function can_edit_meta( $allowed, $meta_key, $post_id ): bool {
		unset( $allowed, $meta_key );

		return current_user_can( 'edit_post', $post_id );
	}
}
