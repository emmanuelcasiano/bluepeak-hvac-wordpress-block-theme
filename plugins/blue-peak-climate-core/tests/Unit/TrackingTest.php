<?php
/**
 * Tests for Analytics\Tracking.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Analytics\Tracking;

final class TrackingTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();

		if ( ! defined( 'BPCH_CORE_URL' ) ) {
			define( 'BPCH_CORE_URL', 'http://hvac.local/wp-content/plugins/blue-peak-climate-core/' );
		}
		if ( ! defined( 'BPCH_CORE_VERSION' ) ) {
			define( 'BPCH_CORE_VERSION', '0.1.0' );
		}
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	public function test_registers_on_the_front_end_enqueue_hook(): void {
		$tracking = new Tracking();
		$tracking->register();

		$this->assertNotFalse( has_action( 'wp_enqueue_scripts', array( $tracking, 'enqueue' ) ) );
	}

	public function test_enqueues_the_script_module_with_the_plugin_version(): void {
		Functions\expect( 'wp_enqueue_script_module' )
			->once()
			->with( 'bpch-analytics', BPCH_CORE_URL . 'assets/front/analytics.js', array(), BPCH_CORE_VERSION );

		( new Tracking() )->enqueue();

		$this->assertSame( 'bpch-analytics', Tracking::HANDLE );
	}

	public function test_the_script_has_no_hard_coded_measurement_id(): void {
		$script = file_get_contents( dirname( __DIR__, 2 ) . '/assets/front/analytics.js' );

		$this->assertDoesNotMatchRegularExpression( '/G-[A-Z0-9]{6,}/', $script );
	}
}
