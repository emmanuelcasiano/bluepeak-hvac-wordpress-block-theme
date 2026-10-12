<?php
/**
 * Makes the cookie banner opt-in for the United States region.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Consent;

defined( 'ABSPATH' ) || exit;

/**
 * Complianz treats the United States as an opt-out region: statistics scripts run on page load and the banner only
 * lets a visitor opt out. The owner chose the stricter rule for this site (nothing but necessary cookies until the
 * visitor accepts, with Accept and Deny of equal weight). Complianz only shows that switch for the US when California
 * is ticked in the wizard, which would put a state we do not serve into the policy, so the banner type is enforced here
 * with its documented `cmplz_consenttype` filter, which lives in git and cannot be undone by a database change. The
 * region itself stays "us". The generated Cookie Policy takes its opt-in wording from the stored `us_california_optin`
 * answer instead, which ai/scripts/configure-complianz.php sets, so the text and the banner say the same thing.
 */
final class OptIn {

	/**
	 * Hooks the filter. Priority 20 runs after Complianz's own adjustments at 10.
	 */
	public function register(): void {
		add_filter( 'cmplz_consenttype', array( $this, 'consent_type' ), 20, 2 );
	}

	/**
	 * Returns "optin" for the US region and leaves every other region alone.
	 *
	 * @param mixed $consent_type Type Complianz picked, "optin" or "optout".
	 * @param mixed $region       Region code, for example "us".
	 * @return mixed
	 */
	public function consent_type( $consent_type, $region ) {
		return 'us' === $region ? 'optin' : $consent_type;
	}
}
