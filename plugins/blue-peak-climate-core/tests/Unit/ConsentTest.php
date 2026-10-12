<?php
/**
 * Tests for Consent\OptIn and Consent\SiteKitDefaults.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Consent\OptIn;
use BluePeakClimate\Core\Consent\SiteKitDefaults;

final class ConsentTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	public function test_opt_in_registers_on_the_complianz_consent_type_filter(): void {
		$opt_in = new OptIn();
		$opt_in->register();

		$this->assertSame( 20, has_filter( 'cmplz_consenttype', array( $opt_in, 'consent_type' ) ) );
	}

	public function test_us_visitors_get_opt_in_instead_of_opt_out(): void {
		$this->assertSame( 'optin', ( new OptIn() )->consent_type( 'optout', 'us' ) );
	}

	public function test_other_regions_keep_the_type_complianz_chose(): void {
		$opt_in = new OptIn();

		$this->assertSame( 'optout', $opt_in->consent_type( 'optout', 'ca' ) );
		$this->assertSame( 'optin', $opt_in->consent_type( 'optin', 'eu' ) );
	}

	public function test_site_kit_defaults_register_on_the_documented_filter(): void {
		$defaults = new SiteKitDefaults();
		$defaults->register();

		$this->assertNotFalse( has_filter( 'googlesitekit_consent_defaults', array( $defaults, 'for_everyone' ) ) );
	}

	public function test_the_region_limit_is_removed_and_everything_else_is_kept(): void {
		$result = ( new SiteKitDefaults() )->for_everyone(
			array(
				'analytics_storage' => 'denied',
				'ad_storage'        => 'denied',
				'region'            => array( 'DE', 'FR', 'GB' ),
				'wait_for_update'   => 500,
			)
		);

		$this->assertSame(
			array(
				'analytics_storage' => 'denied',
				'ad_storage'        => 'denied',
				'wait_for_update'   => 500,
			),
			$result
		);
	}

	public function test_a_value_that_is_not_an_array_passes_through(): void {
		$this->assertSame( 'unexpected', ( new SiteKitDefaults() )->for_everyone( 'unexpected' ) );
	}
}
