<?php
/**
 * Tests for Settings\Settings.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Settings\Settings;

final class SettingsTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		Functions\stubTranslationFunctions();
		Functions\when( 'sanitize_text_field' )->alias( 'trim' );
		Functions\when( 'sanitize_email' )->alias(
			static function ( $email ) {
				return false !== strpos( (string) $email, '@' ) ? trim( $email ) : '';
			}
		);
		Functions\when( 'esc_url_raw' )->alias(
			static function ( $url ) {
				return 0 === strpos( $url, 'http' ) ? $url : '';
			}
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

	public function test_defaults_fill_anything_not_saved(): void {
		$this->saved( array( 'phone' => '(214) 555-0100' ) );

		$this->assertSame( '(214) 555-0100', Settings::get( 'phone' ) );
		$this->assertSame( 'hello@bluepeakclimate.com', Settings::get( 'email' ) );
	}

	public function test_area_served_defaults_to_texas_until_saved(): void {
		$this->saved( array( 'phone' => '(214) 555-0100' ) );

		$this->assertSame( 'Texas', Settings::get( 'area_served' ) );
	}

	public function test_a_broken_option_falls_back_to_defaults(): void {
		$this->saved( 'not an array' );

		$this->assertSame( '(555) 123-4567', Settings::get( 'phone' ) );
	}

	public function test_unknown_key_is_an_empty_string(): void {
		$this->saved( array() );

		$this->assertSame( '', Settings::get( 'nope' ) );
	}

	public function test_phone_href_keeps_digits_and_a_leading_plus(): void {
		$this->assertSame( 'tel:5551234567', Settings::phone_href( '(555) 123-4567' ) );
		$this->assertSame( 'tel:+15551234567', Settings::phone_href( '+1 555.123.4567' ) );
		$this->assertSame( '', Settings::phone_href( 'call us' ) );
	}

	public function test_zip_uses_the_longest_matching_prefix(): void {
		$this->saved(
			array(
				'offices' => array(
					array( 'prefix' => '75', 'name' => 'North', 'email' => 'north@example.com' ),
					array( 'prefix' => '750', 'name' => 'Dallas', 'email' => 'dallas@example.com' ),
				),
			)
		);

		$this->assertSame( 'dallas@example.com', Settings::office_email_for_zip( '75001' ) );
		$this->assertSame( 'north@example.com', Settings::office_email_for_zip( '75901' ) );
	}

	public function test_zip_without_a_rule_goes_to_the_main_email(): void {
		$this->saved( array( 'offices' => array( array( 'prefix' => '750', 'name' => '', 'email' => 'dallas@example.com' ) ) ) );

		$this->assertSame( 'hello@bluepeakclimate.com', Settings::office_email_for_zip( '77001' ) );
		$this->assertSame( 'hello@bluepeakclimate.com', Settings::office_email_for_zip( '' ) );
		$this->assertSame( 'hello@bluepeakclimate.com', Settings::office_email_for_zip( 'abc' ) );
	}

	public function test_zip_accepts_zip_plus_four_and_spaces(): void {
		$this->saved( array( 'offices' => array( array( 'prefix' => '750', 'name' => '', 'email' => 'dallas@example.com' ) ) ) );

		$this->assertSame( 'dallas@example.com', Settings::office_email_for_zip( ' 75001-1234 ' ) );
	}

	public function test_sanitize_cleans_every_field(): void {
		$clean = Settings::sanitize(
			array(
				'legal_name'       => ' Blue Peak Climate LLC ',
				'phone'            => '(214) 555-0100 ext. abc',
				'email'            => 'office@example.com',
				'hours'            => 'Mon-Fri 8-5',
				'service_area'     => 'North Texas',
				'area_served'      => ' Texas ',
				'emergency_banner' => '1',
				'facebook'         => 'https://facebook.com/bluepeak',
				'instagram'        => 'javascript:alert(1)',
				'youtube'          => '',
				'google_business'  => '  ',
				'offices'          => array(
					array( 'prefix' => '7 5 0', 'name' => 'Dallas', 'email' => 'dallas@example.com' ),
				),
			)
		);

		$this->assertSame( 'Blue Peak Climate LLC', $clean['legal_name'] );
		$this->assertSame( '(214) 555-0100 .', $clean['phone'] );
		$this->assertSame( 'Texas', $clean['area_served'] );
		$this->assertTrue( $clean['emergency_banner'] );
		$this->assertSame( 'https://facebook.com/bluepeak', $clean['facebook'] );
		$this->assertSame( '', $clean['instagram'], 'A non-http link is dropped.' );
		$this->assertSame( '', $clean['google_business'] );
		$this->assertSame( array( array( 'prefix' => '750', 'name' => 'Dallas', 'email' => 'dallas@example.com' ) ), $clean['offices'] );
	}

	public function test_unchecked_banner_saves_as_false(): void {
		$clean = Settings::sanitize( array( 'phone' => '(214) 555-0100', 'email' => 'a@example.com' ) );

		$this->assertFalse( $clean['emergency_banner'] );
	}

	public function test_invalid_phone_keeps_the_previous_one_and_reports_it(): void {
		Functions\expect( 'add_settings_error' )->once()->with( Settings::OPTION, 'bpch_invalid_phone', \Mockery::type( 'string' ), 'error' );

		$clean = Settings::sanitize(
			array( 'phone' => 'abc', 'email' => 'a@example.com' ),
			array( 'phone' => '(214) 555-0100' )
		);

		$this->assertSame( '(214) 555-0100', $clean['phone'] );
	}

	public function test_invalid_email_keeps_the_previous_one_and_reports_it(): void {
		Functions\expect( 'add_settings_error' )->once()->with( Settings::OPTION, 'bpch_invalid_email', \Mockery::type( 'string' ), 'error' );

		$clean = Settings::sanitize(
			array( 'phone' => '(214) 555-0100', 'email' => 'not-an-email' ),
			array( 'email' => 'old@example.com' )
		);

		$this->assertSame( 'old@example.com', $clean['email'] );
	}

	public function test_office_rows_need_a_prefix_and_an_email_and_prefixes_are_unique(): void {
		$clean = Settings::sanitize(
			array(
				'phone'   => '(214) 555-0100',
				'email'   => 'a@example.com',
				'offices' => array(
					array( 'prefix' => '', 'name' => 'No prefix', 'email' => 'x@example.com' ),
					array( 'prefix' => '770', 'name' => 'No email', 'email' => '' ),
					array( 'prefix' => '787', 'name' => 'Austin', 'email' => 'austin@example.com' ),
					array( 'prefix' => '787', 'name' => 'Repeat', 'email' => 'other@example.com' ),
					'not a row',
				),
			)
		);

		$this->assertSame( array( array( 'prefix' => '787', 'name' => 'Austin', 'email' => 'austin@example.com' ) ), $clean['offices'] );
	}

	public function test_office_list_is_capped(): void {
		$rows = array();
		for ( $i = 0; $i < Settings::MAX_OFFICES + 10; $i++ ) {
			$rows[] = array( 'prefix' => (string) ( 10000 + $i ), 'name' => '', 'email' => 'o' . $i . '@example.com' );
		}

		$clean = Settings::sanitize( array( 'phone' => '(214) 555-0100', 'email' => 'a@example.com', 'offices' => $rows ) );

		$this->assertCount( Settings::MAX_OFFICES, $clean['offices'] );
	}

	public function test_non_array_input_does_not_break(): void {
		Functions\when( 'add_settings_error' )->justReturn( null );

		$clean = Settings::sanitize( null, array( 'phone' => '(214) 555-0100', 'email' => 'old@example.com' ) );

		$this->assertSame( '(214) 555-0100', $clean['phone'] );
		$this->assertSame( array(), $clean['offices'] );
	}
}
