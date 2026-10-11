<?php
/**
 * The "Add offer" / "Edit offer" screen and the Offers list in WP Admin.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Offers;

use BluePeakClimate\Core\Support\RequiredTitle;

defined( 'ABSPATH' ) || exit;

/**
 * Offers have no block editor (see PostTypes). The form has:
 *
 * - Title (required) and Description (the excerpt), shown on the offer card.
 * - Expires on: the last day the offer is valid. Blank means it never expires.
 * - Fine print: short terms shown under the offer.
 * - Order: WordPress's own box.
 *
 * The list gets an Expiry column, so the office can see at a glance which offers have ended.
 */
final class EditScreen {

	/**
	 * Nonce action and field.
	 */
	private const NONCE_ACTION = 'bpch_save_offer';
	private const NONCE_FIELD  = 'bpch_offer_nonce';

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
				return __( 'An offer needs a title. It was saved as a draft, not published.', 'blue-peak-climate-core' );
			}
		) )->register();
	}

	/**
	 * Replaces WordPress's Excerpt box with a Description box and adds the details box.
	 */
	public function add_meta_boxes(): void {
		remove_meta_box( 'postexcerpt', PostTypes::POST_TYPE, 'normal' );

		add_meta_box( 'bpch-offer-description', __( 'Description', 'blue-peak-climate-core' ), array( $this, 'render_description' ), PostTypes::POST_TYPE, 'normal', 'high' );
		add_meta_box( 'bpch-offer-details', __( 'Offer details', 'blue-peak-climate-core' ), array( $this, 'render_details' ), PostTypes::POST_TYPE, 'normal', 'default' );
	}

	/**
	 * Description box. The textarea is named "excerpt", so WordPress saves it as the excerpt with no extra code.
	 *
	 * @param \WP_Post $post Offer being edited.
	 */
	public function render_description( $post ): void {
		?>
		<label for="excerpt" class="screen-reader-text"><?php esc_html_e( 'Description', 'blue-peak-climate-core' ); ?></label>
		<textarea name="excerpt" id="excerpt" rows="3" class="large-text" aria-describedby="bpch-offer-description-help"><?php echo esc_textarea( $post->post_excerpt ); ?></textarea>
		<p class="description" id="bpch-offer-description-help"><?php esc_html_e( 'One or two sentences about the offer. Shown on the offer card.', 'blue-peak-climate-core' ); ?></p>
		<?php
	}

	/**
	 * Expiry date and fine print.
	 *
	 * @param \WP_Post $post Offer being edited.
	 */
	public function render_details( $post ): void {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_FIELD );

		$expires = get_post_meta( $post->ID, PostTypes::META_EXPIRES, true );
		$terms   = get_post_meta( $post->ID, PostTypes::META_TERMS, true );
		?>
		<p>
			<label for="bpch-offer-expires"><strong><?php esc_html_e( 'Expires on', 'blue-peak-climate-core' ); ?></strong></label><br>
			<input type="date" name="bpch_offer_expires" id="bpch-offer-expires" value="<?php echo esc_attr( is_string( $expires ) ? $expires : '' ); ?>" aria-describedby="bpch-offer-expires-help">
		</p>
		<p class="description" id="bpch-offer-expires-help"><?php esc_html_e( 'The last day the offer is valid. It disappears from the site the next day. Leave blank if it never expires.', 'blue-peak-climate-core' ); ?></p>
		<p>
			<label for="bpch-offer-terms"><strong><?php esc_html_e( 'Fine print', 'blue-peak-climate-core' ); ?></strong></label><br>
			<input type="text" name="bpch_offer_terms" id="bpch-offer-terms" class="large-text" value="<?php echo esc_attr( is_string( $terms ) ? $terms : '' ); ?>" aria-describedby="bpch-offer-terms-help">
		</p>
		<p class="description" id="bpch-offer-terms-help"><?php esc_html_e( 'Optional. For example "New customers only. Cannot be combined with other offers."', 'blue-peak-climate-core' ); ?></p>
		<?php
	}

	/**
	 * Saves the expiry date and the fine print. Checks the nonce, skips autosaves, and requires permission to edit this
	 * offer. Quick Edit and bulk edit send no nonce, so they leave the details alone. A blank value removes the meta.
	 *
	 * @param int $post_id Offer being saved.
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

		$expires = isset( $_POST['bpch_offer_expires'] ) ? PostTypes::sanitize_date( sanitize_text_field( wp_unslash( $_POST['bpch_offer_expires'] ) ) ) : '';
		$terms   = isset( $_POST['bpch_offer_terms'] ) ? sanitize_text_field( wp_unslash( $_POST['bpch_offer_terms'] ) ) : '';

		$this->save_or_remove( $post_id, PostTypes::META_EXPIRES, $expires );
		$this->save_or_remove( $post_id, PostTypes::META_TERMS, $terms );
	}

	/**
	 * Stores a value, or removes the meta when the value is blank.
	 *
	 * @param int    $post_id Offer.
	 * @param string $key     Meta key.
	 * @param string $value   Clean value.
	 */
	private function save_or_remove( int $post_id, string $key, string $value ): void {
		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
			return;
		}

		update_post_meta( $post_id, $key, $value );
	}

	/**
	 * Puts the Expiry column after the title.
	 *
	 * @param array<string, string> $columns Column keys and headings.
	 * @return array<string, string>
	 */
	public function add_columns( $columns ) {
		$result = array();

		foreach ( $columns as $key => $heading ) {
			$result[ $key ] = $heading;

			if ( 'title' === $key ) {
				$result['bpch_offer_expiry'] = __( 'Expiry', 'blue-peak-climate-core' );
			}
		}

		return $result;
	}

	/**
	 * Prints the Expiry column: "Expired <date>", "Ends <date>" or "No end date". Text, not colour alone.
	 *
	 * @param string $column  Column key.
	 * @param int    $post_id Offer.
	 */
	public function render_column( $column, $post_id ): void {
		if ( 'bpch_offer_expiry' !== $column ) {
			return;
		}

		echo esc_html( self::expiry_label( (string) get_post_meta( $post_id, PostTypes::META_EXPIRES, true ), Expiry::today() ) );
	}

	/**
	 * The words shown in the Expiry column.
	 *
	 * @param string $expires_on Stored date or an empty string.
	 * @param string $today      Today (Y-m-d).
	 * @return string
	 */
	public static function expiry_label( string $expires_on, string $today ): string {
		if ( ! Expiry::is_valid_date( $expires_on ) ) {
			return __( 'No end date', 'blue-peak-climate-core' );
		}

		$date = mysql2date( get_option( 'date_format' ), $expires_on );

		if ( Expiry::is_expired( $expires_on, $today ) ) {
			/* translators: %s: the date the offer ended. */
			return sprintf( __( 'Expired %s', 'blue-peak-climate-core' ), $date );
		}

		/* translators: %s: the last day the offer is valid. */
		return sprintf( __( 'Ends %s', 'blue-peak-climate-core' ), $date );
	}
}
