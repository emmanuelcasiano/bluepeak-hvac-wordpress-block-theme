<?php
/**
 * Tests for Security\Hardening.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Security\Hardening;

final class HardeningTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * Runs alone: register() defines DISALLOW_FILE_EDIT for the whole process.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_registers_the_hooks(): void {
		$hardening = new Hardening();
		$hardening->register();

		$this->assertNotFalse( has_filter( 'xmlrpc_enabled', '__return_false' ) );
		$this->assertNotFalse( has_filter( 'xmlrpc_methods', '__return_empty_array' ) );
		$this->assertNotFalse( has_filter( 'the_generator', '__return_empty_string' ) );
		$this->assertNotFalse( has_filter( 'wp_headers', array( $hardening, 'filter_headers' ) ) );
		$this->assertTrue( defined( 'DISALLOW_FILE_EDIT' ) );
	}

	public function test_adds_the_security_headers(): void {
		$headers = ( new Hardening() )->filter_headers( array( 'Content-Type' => 'text/html; charset=UTF-8' ) );

		$this->assertSame( 'nosniff', $headers['X-Content-Type-Options'] );
		$this->assertSame( 'SAMEORIGIN', $headers['X-Frame-Options'] );
		$this->assertSame( 'strict-origin-when-cross-origin', $headers['Referrer-Policy'] );
		$this->assertSame( 'text/html; charset=UTF-8', $headers['Content-Type'], 'Other headers are kept.' );
	}

	public function test_keeps_a_header_that_something_else_already_set(): void {
		$headers = ( new Hardening() )->filter_headers( array( 'X-Frame-Options' => 'DENY' ) );

		$this->assertSame( 'DENY', $headers['X-Frame-Options'] );
		$this->assertSame( 'nosniff', $headers['X-Content-Type-Options'] );
	}

	public function test_drops_the_pingback_header(): void {
		$headers = ( new Hardening() )->filter_headers( array( 'X-Pingback' => 'http://hvac.local/xmlrpc.php' ) );

		$this->assertArrayNotHasKey( 'X-Pingback', $headers );
	}

	public function test_headers_can_be_changed_with_a_filter(): void {
		Monkey\Filters\expectApplied( 'bpch_core_security_headers' )
			->once()
			->with( Hardening::headers() )
			->andReturn( array( 'Permissions-Policy' => 'camera=()' ) );

		$headers = ( new Hardening() )->filter_headers( array() );

		$this->assertSame( array( 'Permissions-Policy' => 'camera=()' ), $headers );
	}

	public function test_a_bad_filter_value_adds_nothing_and_does_not_fail(): void {
		Monkey\Filters\expectApplied( 'bpch_core_security_headers' )->once()->andReturn( 'not an array' );

		$this->assertSame( array( 'A' => 'b' ), ( new Hardening() )->filter_headers( array( 'A' => 'b' ) ) );
	}

	public function test_leaves_a_non_array_headers_value_alone(): void {
		$this->assertNull( ( new Hardening() )->filter_headers( null ) );
	}

	/**
	 * Runs alone: the constant cannot be undefined, and the first define must be the one under test.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_locks_the_file_editor_when_wp_config_has_not(): void {
		$this->assertFalse( defined( 'DISALLOW_FILE_EDIT' ) );

		( new Hardening() )->lock_file_editor();

		$this->assertTrue( DISALLOW_FILE_EDIT );
	}

	/**
	 * Runs alone: wp-config.php's value must win, so it is defined first here.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_does_not_override_wp_config(): void {
		define( 'DISALLOW_FILE_EDIT', false );

		( new Hardening() )->lock_file_editor();

		$this->assertFalse( DISALLOW_FILE_EDIT );
	}
}
