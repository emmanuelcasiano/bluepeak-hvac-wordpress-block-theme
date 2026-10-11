<?php
/**
 * Tests for Support\ListingOnly.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Support\ListingOnly;

final class ListingOnlyTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * A guard for the review and offer types.
	 *
	 * @return ListingOnly
	 */
	private function guard(): ListingOnly {
		return new ListingOnly(
			array(
				'bpch_review' => '/reviews/',
				'bpch_offer'  => '/offers/',
			)
		);
	}

	public function test_each_guarded_type_maps_to_its_listing_page(): void {
		$this->assertSame( '/reviews/', $this->guard()->listing_path( 'bpch_review' ) );
		$this->assertSame( '/offers/', $this->guard()->listing_path( 'bpch_offer' ) );
	}

	public function test_other_types_and_blanks_have_no_listing_page(): void {
		foreach ( array( 'post', 'page', 'bpch_service', '' ) as $type ) {
			$this->assertNull( $this->guard()->listing_path( $type ), $type );
		}
	}

	public function test_a_normal_page_is_not_redirected(): void {
		Functions\when( 'is_singular' )->justReturn( false );
		Functions\when( 'is_post_type_archive' )->justReturn( false );
		Functions\expect( 'wp_safe_redirect' )->never();

		$this->guard()->redirect();

		$this->addToAssertionCount( 1 );
	}
}
