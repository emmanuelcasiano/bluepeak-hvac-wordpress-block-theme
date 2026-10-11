<?php
/**
 * Server-side check of the ZIP code on the service request form.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Forms;

defined( 'ABSPATH' ) || exit;

/**
 * The request form (Fluent Forms) has a text field named `zip`. The browser checks its format, but a script can post
 * anything, and the nearest-office routing reads this value, so the server checks it too. Fluent Forms already
 * enforces "required"; this only judges a ZIP that was typed.
 *
 * Fluent Forms is a third-party plugin, so this works through its documented filter and never touches its files.
 */
final class ZipValidation {

	/**
	 * Name of the ZIP field in the form. The routing feature reads the same key from the entry.
	 */
	public const FIELD = 'zip';

	/**
	 * Hooks the check into Fluent Forms' per-field validation of text inputs.
	 */
	public function register(): void {
		add_filter( 'fluentform/validate_input_item_input_text', array( $this, 'validate' ), 11, 3 );
	}

	/**
	 * Tells whether a typed value is a US ZIP: five digits, or a ZIP+4. Spaces around it are ignored.
	 *
	 * @param string $zip Value as typed.
	 * @return bool
	 */
	public static function is_valid( string $zip ): bool {
		return 1 === preg_match( '/^\d{5}(-\d{4})?$/', trim( $zip ) );
	}

	/**
	 * Returns an error message for a badly formed ZIP, and leaves every other field (and an empty ZIP) alone.
	 *
	 * @param string|array<int, string> $error Error so far from other callbacks, empty when there is none.
	 * @param array<string, mixed>      $field Field being validated; its `name` is the input name.
	 * @param array<string, mixed>      $form_data Everything submitted, already unslashed by Fluent Forms.
	 * @return string|array<int, string>
	 */
	public function validate( $error, $field = array(), $form_data = array() ) {
		if ( self::FIELD !== ( $field['name'] ?? '' ) ) {
			return $error;
		}

		$value = $form_data[ self::FIELD ] ?? '';

		// Empty is the "required" rule's job, and an array is not a ZIP a person typed.
		if ( ! is_string( $value ) || '' === trim( $value ) || self::is_valid( $value ) ) {
			return $error;
		}

		return __( 'Enter a 5-digit ZIP code, like 78701.', 'blue-peak-climate-core' );
	}
}
