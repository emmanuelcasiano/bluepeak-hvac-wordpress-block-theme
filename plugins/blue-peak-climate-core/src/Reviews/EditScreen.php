<?php
/**
 * The "Add review" / "Edit review" screen and the Reviews list in WP Admin.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Reviews;

use BluePeakClimate\Core\Support\RequiredTitle;

defined( 'ABSPATH' ) || exit;

/**
 * Reviews have no block editor (see PostTypes). The form has:
 *
 * - Title (required): a short label for the office's own list. It is not shown on the site.
 * - Review (the excerpt): what the customer said.
 * - Customer: how they are shown, e.g. "Homeowner, Texas".
 * - Service: what the review is about, e.g. "Emergency AC repair". Optional.
 * - Rating: 1 to 5 stars, as radio buttons.
 * - Order: WordPress's own box.
 */
final class EditScreen {

	/**
	 * Nonce action and field.
	 */
	private const NONCE_ACTION = 'bpch_save_review';
	private const NONCE_FIELD  = 'bpch_review_nonce';

	/**
	 * Hooks the screen.
	 */
	public function register(): void {
		add_action( 'add_meta_boxes_' . PostTypes::POST_TYPE, array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post_' . PostTypes::POST_TYPE, array( $this, 'save_details' ) );
		add_filter( 'manage_' . PostTypes::POST_TYPE . '_posts_columns', array( $this, 'add_columns' ) );
		add_action( 'manage_' . PostTypes::POST_TYPE . '_posts_custom_column', array( $this, 'render_column' ), 10, 2 );

		( new RequiredTitle(
			PostTypes::POST_TYPE,
			static function () {
				return __( 'A review needs a title. It was saved as a draft, not published.', 'blue-peak-climate-core' );
			}
		) )->register();
	}

	/**
	 * Replaces WordPress's Excerpt box with a Review box and adds the details box.
	 */
	public function add_meta_boxes(): void {
		remove_meta_box( 'postexcerpt', PostTypes::POST_TYPE, 'normal' );

		add_meta_box( 'bpch-review-text', __( 'Review', 'blue-peak-climate-core' ), array( $this, 'render_text' ), PostTypes::POST_TYPE, 'normal', 'high' );
		add_meta_box( 'bpch-review-details', __( 'Review details', 'blue-peak-climate-core' ), array( $this, 'render_details' ), PostTypes::POST_TYPE, 'normal', 'default' );
	}

	/**
	 * Review box. The textarea is named "excerpt", so WordPress saves it as the excerpt with no extra code.
	 *
	 * @param \WP_Post $post Review being edited.
	 */
	public function render_text( $post ): void {
		?>
		<label for="excerpt" class="screen-reader-text"><?php esc_html_e( 'Review', 'blue-peak-climate-core' ); ?></label>
		<textarea name="excerpt" id="excerpt" rows="5" class="large-text" aria-describedby="bpch-review-text-help"><?php echo esc_textarea( $post->post_excerpt ); ?></textarea>
		<p class="description" id="bpch-review-text-help"><?php esc_html_e( 'What the customer said, in their own words. Shown as a quote on the Reviews page. Only publish a review the customer has agreed to share.', 'blue-peak-climate-core' ); ?></p>
		<?php
	}

	/**
	 * Customer, service and rating.
	 *
	 * @param \WP_Post $post Review being edited.
	 */
	public function render_details( $post ): void {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_FIELD );

		$reviewer = get_post_meta( $post->ID, PostTypes::META_REVIEWER, true );
		$service  = get_post_meta( $post->ID, PostTypes::META_SERVICE, true );
		$rating   = Rating::clamp( get_post_meta( $post->ID, PostTypes::META_RATING, true ) );
		?>
		<p>
			<label for="bpch-review-reviewer"><strong><?php esc_html_e( 'Customer', 'blue-peak-climate-core' ); ?></strong></label><br>
			<input type="text" name="bpch_review_reviewer" id="bpch-review-reviewer" class="large-text" value="<?php echo esc_attr( is_string( $reviewer ) ? $reviewer : '' ); ?>" aria-describedby="bpch-review-reviewer-help">
		</p>
		<p class="description" id="bpch-review-reviewer-help"><?php esc_html_e( 'How the customer is shown, for example "Homeowner, Texas". Use a name only with their permission.', 'blue-peak-climate-core' ); ?></p>
		<p>
			<label for="bpch-review-service"><strong><?php esc_html_e( 'Service', 'blue-peak-climate-core' ); ?></strong></label><br>
			<input type="text" name="bpch_review_service" id="bpch-review-service" class="large-text" value="<?php echo esc_attr( is_string( $service ) ? $service : '' ); ?>" aria-describedby="bpch-review-service-help">
		</p>
		<p class="description" id="bpch-review-service-help"><?php esc_html_e( 'Optional. What the review is about, for example "Emergency AC repair".', 'blue-peak-climate-core' ); ?></p>
		<fieldset>
			<legend><strong><?php esc_html_e( 'Rating', 'blue-peak-climate-core' ); ?></strong></legend>
			<?php for ( $stars = Rating::MAX; $stars >= Rating::MIN; --$stars ) : ?>
				<label style="margin-right:1em">
					<input type="radio" name="bpch_review_rating" value="<?php echo esc_attr( (string) $stars ); ?>" <?php checked( $rating, $stars ); ?>>
					<?php echo esc_html( Rating::label( $stars ) ); ?>
				</label>
			<?php endfor; ?>
		</fieldset>
		<?php
	}

	/**
	 * Saves the details. Checks the nonce, skips autosaves, and requires permission to edit this review. Quick Edit and
	 * bulk edit send no nonce, so they leave the details alone. A blank customer or service removes the meta.
	 *
	 * @param int $post_id Review being saved.
	 */
	public function save_details( $post_id ): void {
		if ( ! isset( $_POST[ self::NONCE_FIELD ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::NONCE_FIELD ] ) ), self::NONCE_ACTION ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$reviewer = isset( $_POST['bpch_review_reviewer'] ) ? sanitize_text_field( wp_unslash( $_POST['bpch_review_reviewer'] ) ) : '';
		$service  = isset( $_POST['bpch_review_service'] ) ? sanitize_text_field( wp_unslash( $_POST['bpch_review_service'] ) ) : '';
		$rating   = isset( $_POST['bpch_review_rating'] ) ? Rating::clamp( sanitize_text_field( wp_unslash( $_POST['bpch_review_rating'] ) ) ) : Rating::MAX;

		update_post_meta( $post_id, PostTypes::META_RATING, $rating );

		foreach ( array(
			PostTypes::META_REVIEWER => $reviewer,
			PostTypes::META_SERVICE  => $service,
		) as $key => $value ) {
			if ( '' === $value ) {
				delete_post_meta( $post_id, $key );
				continue;
			}

			update_post_meta( $post_id, $key, $value );
		}
	}

	/**
	 * Puts the Rating column after the title.
	 *
	 * @param array<string, string> $columns Column keys and headings.
	 * @return array<string, string>
	 */
	public function add_columns( $columns ) {
		$result = array();

		foreach ( $columns as $key => $heading ) {
			$result[ $key ] = $heading;

			if ( 'title' === $key ) {
				$result['bpch_review_rating'] = __( 'Rating', 'blue-peak-climate-core' );
			}
		}

		return $result;
	}

	/**
	 * Prints the Rating column as words ("5 out of 5 stars").
	 *
	 * @param string $column  Column key.
	 * @param int    $post_id Review.
	 */
	public function render_column( $column, $post_id ): void {
		if ( 'bpch_review_rating' !== $column ) {
			return;
		}

		echo esc_html( Rating::label( Rating::clamp( get_post_meta( $post_id, PostTypes::META_RATING, true ) ) ) );
	}
}
