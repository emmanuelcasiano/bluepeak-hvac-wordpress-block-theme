<?php
/**
 * Sends the service request notification to the office that covers the visitor's ZIP code.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Forms;

use BluePeakClimate\Core\Settings\Settings;

defined( 'ABSPATH' ) || exit;

/**
 * The "Service request" form (Fluent Forms) has a fixed recipient in its notification. This swaps that recipient for the
 * office whose ZIP prefix matches the submitted `zip`, using the table in Site Settings. A ZIP with no matching row goes
 * to the main Site Settings email, so a lead is never dropped.
 *
 * It works through Fluent Forms' documented `fluentform/email_to` filter, which runs on the server just before the mail
 * is sent. The office addresses never reach the page or the browser.
 */
final class OfficeRouting {

	/**
	 * Title of the form that ai/scripts/create-contact-form.php creates. The numeric ID differs on every copy of the
	 * site, so the title is how the theme and this class both recognise the form.
	 */
	public const FORM_TITLE = 'Service request';

	/**
	 * Hooks the routing into the recipient of every Fluent Forms notification; other forms are passed through.
	 */
	public function register(): void {
		add_filter( 'fluentform/email_to', array( $this, 'route' ), 10, 4 );
	}

	/**
	 * Returns the office email for the submitted ZIP when this is the staff notification of the service request form,
	 * and the recipient it came in with for anything else.
	 *
	 * Only a notification with a fixed address is rerouted. One that sends to an address the visitor typed (a
	 * confirmation, for example) must keep going to the visitor.
	 *
	 * @param string|array<int, string> $recipient     Recipient(s) so far, a comma separated string from Fluent Forms.
	 * @param array<string, mixed>      $notification  Notification settings from the form.
	 * @param array<string, mixed>      $submitted     Everything submitted, already unslashed by Fluent Forms.
	 * @param object|null               $form          Fluent Forms form row; its `title` identifies the form.
	 * @return string|array<int, string>
	 */
	public function route( $recipient, $notification = array(), $submitted = array(), $form = null ) {
		if ( ! is_object( $form ) || self::FORM_TITLE !== ( $form->title ?? '' ) ) {
			return $recipient;
		}

		if ( 'email' !== ( $notification['sendTo']['type'] ?? '' ) ) {
			return $recipient;
		}

		$zip = $submitted[ ZipValidation::FIELD ] ?? '';
		$to  = Settings::office_email_for_zip( is_string( $zip ) ? $zip : '' );

		// The Site Settings table is validated when saved, but a hand-edited option should never blank the recipient.
		return is_email( $to ) ? $to : $recipient;
	}
}
