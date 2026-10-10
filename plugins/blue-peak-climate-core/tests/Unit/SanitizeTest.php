<?php
/**
 * Tests for Support\Sanitize.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Support\Sanitize;

final class SanitizeTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		Functions\when( 'wp_unslash' )->alias( 'stripslashes' );
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	public function test_splits_on_commas_and_newlines(): void {
		Functions\when( 'sanitize_text_field' )->alias( 'trim' );

		$this->assertSame( array( 'red', 'green', 'blue' ), Sanitize::text_list( "red, green\nblue" ) );
	}

	public function test_drops_empty_items_and_duplicates(): void {
		Functions\when( 'sanitize_text_field' )->alias( 'trim' );

		$this->assertSame( array( 'red' ), Sanitize::text_list( "red,, red ,\n" ) );
	}

	public function test_runs_every_item_through_sanitize_text_field(): void {
		Functions\expect( 'sanitize_text_field' )->twice()->andReturnUsing( 'trim' );

		$this->assertSame( array( 'a', 'b' ), Sanitize::text_list( 'a,b' ) );
	}

	public function test_non_string_input_returns_empty_list(): void {
		$this->assertSame( array(), Sanitize::text_list( array( 'red' ) ) );
		$this->assertSame( array(), Sanitize::text_list( null ) );
	}
}
