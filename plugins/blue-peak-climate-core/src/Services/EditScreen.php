<?php
/**
 * The "Add service" / "Edit service" screen: a plain form with only the fields a service needs.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Services;

defined( 'ABSPATH' ) || exit;

/**
 * Services have no block editor (see PostTypes). This class builds the form that replaces it:
 *
 * - Title is required. The browser blocks saving without one, and the server refuses to publish without one.
 * - Description is the excerpt, the one-line text shown on the service card.
 * - Category and Type are radio buttons, so a service has exactly one of each.
 * - Starting price is the `price_from` meta.
 * - Order and Featured image are WordPress's own boxes.
 */
final class EditScreen {

	/**
	 * Nonce action and field for the starting price.
	 */
	private const NONCE_ACTION = 'bpch_save_service';
	private const NONCE_FIELD  = 'bpch_service_nonce';

	/**
	 * Statuses that need a title. Drafts and auto-drafts may be saved without one.
	 */
	private const PUBLIC_STATUSES = array( 'publish', 'future', 'pending', 'private' );

	/**
	 * Set when a save was changed to draft because the title was empty, so the next page can say why.
	 *
	 * @var bool
	 */
	private $title_missing = false;

	/**
	 * Hooks the screen.
	 */
	public function register(): void {
		add_action( 'add_meta_boxes_' . PostTypes::POST_TYPE, array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post_' . PostTypes::POST_TYPE, array( $this, 'save_price' ) );
		add_filter( 'wp_insert_post_data', array( $this, 'require_title' ) );
		add_filter( 'redirect_post_location', array( $this, 'flag_missing_title' ) );
		add_action( 'admin_notices', array( $this, 'show_title_notice' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Replaces WordPress's Excerpt box with a Description box and adds the starting price box.
	 */
	public function add_meta_boxes(): void {
		remove_meta_box( 'postexcerpt', PostTypes::POST_TYPE, 'normal' );

		add_meta_box( 'bpch-service-description', __( 'Description', 'blue-peak-climate-core' ), array( $this, 'render_description' ), PostTypes::POST_TYPE, 'normal', 'high' );
		add_meta_box( 'bpch-service-price', __( 'Starting price', 'blue-peak-climate-core' ), array( $this, 'render_price' ), PostTypes::POST_TYPE, 'normal', 'default' );
	}

	/**
	 * Description box. The textarea is named "excerpt", so WordPress saves it as the excerpt with no extra code.
	 *
	 * @param \WP_Post $post Service being edited.
	 */
	public function render_description( $post ): void {
		?>
		<label for="excerpt" class="screen-reader-text"><?php esc_html_e( 'Description', 'blue-peak-climate-core' ); ?></label>
		<textarea name="excerpt" id="excerpt" rows="3" class="large-text" aria-describedby="bpch-description-help"><?php echo esc_textarea( $post->post_excerpt ); ?></textarea>
		<p class="description" id="bpch-description-help"><?php esc_html_e( 'One or two sentences. Shown on the service card.', 'blue-peak-climate-core' ); ?></p>
		<?php
	}

	/**
	 * Starting price box.
	 *
	 * @param \WP_Post $post Service being edited.
	 */
	public function render_price( $post ): void {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_FIELD );

		$value = get_post_meta( $post->ID, PostTypes::META_PRICE, true );
		?>
		<label for="bpch-price-from" class="screen-reader-text"><?php esc_html_e( 'Starting price', 'blue-peak-climate-core' ); ?></label>
		<input type="text" name="bpch_price_from" id="bpch-price-from" class="regular-text" value="<?php echo esc_attr( is_string( $value ) ? $value : '' ); ?>" aria-describedby="bpch-price-help">
		<p class="description" id="bpch-price-help"><?php esc_html_e( 'Shown as typed, for example "From $89". Leave blank to show no price.', 'blue-peak-climate-core' ); ?></p>
		<?php
	}

	/**
	 * Category / Type box: one radio button per term. The hidden 0 lets "no choice" be saved, as in WordPress's own box.
	 * WordPress saves the choice from tax_input[taxonomy][], so no save code is needed here.
	 *
	 * @param \WP_Post             $post Service being edited.
	 * @param array<string, mixed> $box  Meta box arguments; args.taxonomy is the taxonomy name.
	 */
	public static function render_term_choice( $post, $box ): void {
		$taxonomy = isset( $box['args']['taxonomy'] ) ? (string) $box['args']['taxonomy'] : '';
		$terms    = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			)
		);

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			echo '<p>' . esc_html__( 'No terms yet. Add them under Services in the menu.', 'blue-peak-climate-core' ) . '</p>';
			return;
		}

		$selected = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
		$selected = is_array( $selected ) && ! empty( $selected ) ? (int) $selected[0] : 0;
		$name     = 'tax_input[' . $taxonomy . '][]';
		?>
		<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="0">
		<ul class="categorychecklist form-no-clear">
			<?php foreach ( $terms as $term ) : ?>
				<li>
					<label>
						<input type="radio" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( (string) $term->term_id ); ?>" <?php checked( $selected, $term->term_id ); ?>>
						<?php echo esc_html( $term->name ); ?>
					</label>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}

	/**
	 * Saves the starting price. Checks the nonce, skips autosaves, and requires permission to edit this service.
	 * Quick Edit and bulk edit send no nonce, so they leave the price alone.
	 *
	 * @param int $post_id Service being saved.
	 */
	public function save_price( $post_id ): void {
		if ( ! isset( $_POST[ self::NONCE_FIELD ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::NONCE_FIELD ] ) ), self::NONCE_ACTION ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$price = isset( $_POST['bpch_price_from'] ) ? sanitize_text_field( wp_unslash( $_POST['bpch_price_from'] ) ) : '';

		if ( '' === $price ) {
			delete_post_meta( $post_id, PostTypes::META_PRICE );
			return;
		}

		update_post_meta( $post_id, PostTypes::META_PRICE, $price );
	}

	/**
	 * Server-side title check: a service without a title is saved as a draft instead of being published.
	 * The browser check in service-edit.js covers the normal case; this covers everything else (Quick Edit, REST).
	 *
	 * @param array<string, mixed> $data Post data about to be saved.
	 * @return array<string, mixed>
	 */
	public function require_title( $data ) {
		if ( PostTypes::POST_TYPE !== ( $data['post_type'] ?? '' ) ) {
			return $data;
		}

		if ( in_array( $data['post_status'] ?? '', self::PUBLIC_STATUSES, true ) && '' === trim( (string) ( $data['post_title'] ?? '' ) ) ) {
			$data['post_status'] = 'draft';
			$this->title_missing = true;
		}

		return $data;
	}

	/**
	 * Adds a flag to the address WordPress returns to after saving, when the title was missing.
	 *
	 * @param string $location Address to redirect to.
	 * @return string
	 */
	public function flag_missing_title( $location ) {
		return $this->title_missing ? add_query_arg( 'bpch_title_required', '1', $location ) : $location;
	}

	/**
	 * Explains why the service was saved as a draft.
	 */
	public function show_title_notice(): void {
		// Display only: the flag changes what is printed, not what is saved.
		if ( empty( $_GET['bpch_title_required'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		$screen = get_current_screen();
		if ( ! $screen || PostTypes::POST_TYPE !== $screen->post_type ) {
			return;
		}

		printf(
			'<div class="notice notice-error"><p>%s</p></div>',
			esc_html__( 'A service needs a title. It was saved as a draft, not published.', 'blue-peak-climate-core' )
		);
	}

	/**
	 * Loads the script that makes the title field required in the browser.
	 *
	 * @param string $hook_suffix Current admin page.
	 */
	public function enqueue_assets( $hook_suffix ): void {
		if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( ! $screen || PostTypes::POST_TYPE !== $screen->post_type ) {
			return;
		}

		wp_enqueue_script( 'bpch-service-edit', BPCH_CORE_URL . 'assets/admin/service-edit.js', array(), BPCH_CORE_VERSION, true );
	}
}
