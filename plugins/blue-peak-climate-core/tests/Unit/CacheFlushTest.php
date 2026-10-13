<?php
/**
 * Tests for Cache\Flush.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Cache\Flush;
use BluePeakClimate\Core\Settings\Settings;

final class CacheFlushTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	private function post( string $type ): object {
		return (object) array( 'post_type' => $type );
	}

	public function test_registers_status_and_settings_hooks(): void {
		$flush = new Flush();
		$flush->register();

		$this->assertNotFalse( has_action( 'transition_post_status', array( $flush, 'on_status_change' ) ) );
		$this->assertNotFalse( has_action( 'update_option_' . Settings::OPTION, array( $flush, 'clear' ) ) );
		$this->assertNotFalse( has_action( 'add_option_' . Settings::OPTION, array( $flush, 'clear' ) ) );
	}

	public function test_clears_when_an_offer_is_published(): void {
		Functions\expect( 'wp_cache_clear_cache' )->once();

		( new Flush() )->on_status_change( 'publish', 'draft', $this->post( 'bpch_offer' ) );

		// The once()/never() expectation above is checked when the test ends.
		$this->addToAssertionCount( 1 );
	}

	public function test_clears_when_a_published_review_is_saved_again(): void {
		Functions\expect( 'wp_cache_clear_cache' )->once();

		( new Flush() )->on_status_change( 'publish', 'publish', $this->post( 'bpch_review' ) );

		// The once()/never() expectation above is checked when the test ends.
		$this->addToAssertionCount( 1 );
	}

	public function test_clears_when_a_service_is_trashed(): void {
		Functions\expect( 'wp_cache_clear_cache' )->once();

		( new Flush() )->on_status_change( 'trash', 'publish', $this->post( 'bpch_service' ) );

		// The once()/never() expectation above is checked when the test ends.
		$this->addToAssertionCount( 1 );
	}

	public function test_ignores_a_draft_that_stays_a_draft(): void {
		Functions\expect( 'wp_cache_clear_cache' )->never();

		( new Flush() )->on_status_change( 'draft', 'draft', $this->post( 'bpch_offer' ) );
		( new Flush() )->on_status_change( 'draft', 'auto-draft', $this->post( 'bpch_review' ) );

		// The once()/never() expectation above is checked when the test ends.
		$this->addToAssertionCount( 1 );
	}

	public function test_ignores_other_post_types(): void {
		Functions\expect( 'wp_cache_clear_cache' )->never();

		( new Flush() )->on_status_change( 'publish', 'draft', $this->post( 'page' ) );
		( new Flush() )->on_status_change( 'publish', 'publish', $this->post( 'post' ) );

		// The once()/never() expectation above is checked when the test ends.
		$this->addToAssertionCount( 1 );
	}

	/**
	 * Runs alone: Brain Monkey defines wp_cache_clear_cache() for the whole process once another test expects it.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_clear_does_nothing_when_the_cache_plugin_is_missing(): void {
		// wp_cache_clear_cache() is not defined here, so calling it would be a fatal error.
		( new Flush() )->clear();

		$this->assertTrue( true );
	}
}
