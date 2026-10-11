<?php
/**
 * Removes the "Powered by FluentForm" line from the footer of form emails.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Forms;

defined( 'ABSPATH' ) || exit;

/**
 * Form emails go to the client's staff, and the free version of Fluent Forms adds a vendor credit under each one. The
 * footer text before it ("© <site title>.") stays. This uses Fluent Forms' documented `fluentform/email_template_footer_credit`
 * filter, so nothing in the plugin is edited.
 */
final class EmailCredit {

	/**
	 * Hooks the filter into the email footer.
	 */
	public function register(): void {
		add_filter( 'fluentform/email_template_footer_credit', array( $this, 'remove' ) );
	}

	/**
	 * Returns an empty credit.
	 *
	 * @param string $credit Credit line Fluent Forms would print.
	 * @return string
	 */
	public function remove( $credit ): string { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found -- Filter callback; the value is replaced.
		return '';
	}
}
