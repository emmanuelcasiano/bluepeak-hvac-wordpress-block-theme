<?php
/**
 * Tests for Forms\OfficeRouting.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Forms\OfficeRouting;

final class OfficeRoutingTest extends TestCase {

	private const FORM_RECIPIENT = 'hello@bluepeakclimate.com';

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		Functions\stubTranslationFunctions();
		Functions\when( 'is_email' )->alias(
			static function ( $email ) {
				return false !== strpos( (string) $email, '@' ) ? $email : false;
			}
		);
		$this->saved(
			array(
				'email'   => 'main@example.com',
				'offices' => array(
					array( 'prefix' => '75', 'name' => 'North Texas', 'email' => 'north@example.com' ),
					array( 'prefix' => '750', 'name' => 'Dallas', 'email' => 'dallas@example.com' ),
					array( 'prefix' => '787', 'name' => 'Austin', 'email' => 'austin@example.com' ),
				),
			)
		);
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * Makes get_option() return the given saved settings.
	 *
	 * @param mixed $saved What the option holds.
	 */
	private function saved( $saved ): void {
		Functions\when( 'get_option' )->justReturn( $saved );
	}

	/**
	 * Routes a submission through the filter the way Fluent Forms calls it.
	 *
	 * @param array<string, mixed> $submitted    Submitted data.
	 * @param string               $title        Form title.
	 * @param string               $recipient_type Notification `sendTo.type`.
	 * @return string|array<int, string>
	 */
	private function route( array $submitted, string $title = 'Service request', string $recipient_type = 'email' ) {
		return ( new OfficeRouting() )->route(
			self::FORM_RECIPIENT,
			array( 'sendTo' => array( 'type' => $recipient_type ) ),
			$submitted,
			(object) array( 'title' => $title )
		);
	}

	public function test_registers_on_the_fluent_forms_recipient_filter(): void {
		$routing = new OfficeRouting();
		$routing->register();

		$this->assertNotFalse( has_filter( 'fluentform/email_to', array( $routing, 'route' ) ) );
	}

	public function test_a_zip_with_an_office_goes_to_that_office(): void {
		$this->assertSame( 'austin@example.com', $this->route( array( 'zip' => '78701' ) ) );
	}

	public function test_the_longest_matching_prefix_wins(): void {
		$this->assertSame( 'dallas@example.com', $this->route( array( 'zip' => '75001' ) ) );
		$this->assertSame( 'north@example.com', $this->route( array( 'zip' => '75901' ) ) );
	}

	public function test_zip_plus_four_matches_on_the_first_five_digits(): void {
		$this->assertSame( 'austin@example.com', $this->route( array( 'zip' => '78701-1234' ) ) );
	}

	public function test_an_unmatched_zip_goes_to_the_main_email(): void {
		$this->assertSame( 'main@example.com', $this->route( array( 'zip' => '90210' ) ) );
	}

	public function test_a_missing_or_odd_zip_goes_to_the_main_email(): void {
		$this->assertSame( 'main@example.com', $this->route( array() ) );
		$this->assertSame( 'main@example.com', $this->route( array( 'zip' => '' ) ) );
		$this->assertSame( 'main@example.com', $this->route( array( 'zip' => array( '78701' ) ) ) );
	}

	public function test_other_forms_keep_their_recipient(): void {
		$this->assertSame( self::FORM_RECIPIENT, $this->route( array( 'zip' => '78701' ), 'Newsletter' ) );
		$this->assertSame(
			self::FORM_RECIPIENT,
			( new OfficeRouting() )->route( self::FORM_RECIPIENT, array(), array( 'zip' => '78701' ), null )
		);
	}

	public function test_a_notification_to_the_visitor_is_not_rerouted(): void {
		$this->assertSame( self::FORM_RECIPIENT, $this->route( array( 'zip' => '78701' ), 'Service request', 'field' ) );
	}

	public function test_an_invalid_saved_address_keeps_the_original_recipient(): void {
		$this->saved( array( 'email' => 'not-an-email', 'offices' => array() ) );

		$this->assertSame( self::FORM_RECIPIENT, $this->route( array( 'zip' => '78701' ) ) );
	}

	public function test_a_new_row_in_site_settings_changes_the_route_without_code(): void {
		$this->assertSame( 'main@example.com', $this->route( array( 'zip' => '77001' ) ) );

		$this->saved(
			array(
				'email'   => 'main@example.com',
				'offices' => array( array( 'prefix' => '770', 'name' => 'Houston', 'email' => 'houston@example.com' ) ),
			)
		);

		$this->assertSame( 'houston@example.com', $this->route( array( 'zip' => '77001' ) ) );
	}
}
