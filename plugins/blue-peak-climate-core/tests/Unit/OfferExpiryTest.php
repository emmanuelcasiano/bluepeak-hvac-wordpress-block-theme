<?php
/**
 * Tests for Offers\Expiry and the offer date sanitizing.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use Mockery;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Offers\Expiry;
use BluePeakClimate\Core\Offers\PostTypes;

final class OfferExpiryTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	public function test_an_offer_is_valid_through_its_last_day(): void {
		$this->assertFalse( Expiry::is_expired( '2026-10-11', '2026-10-11' ), 'the last day still counts' );
		$this->assertFalse( Expiry::is_expired( '2026-10-12', '2026-10-11' ) );
		$this->assertTrue( Expiry::is_expired( '2026-10-10', '2026-10-11' ), 'hidden from the next day' );
	}

	public function test_expiry_crosses_month_and_year_boundaries(): void {
		$this->assertTrue( Expiry::is_expired( '2026-12-31', '2027-01-01' ) );
		$this->assertTrue( Expiry::is_expired( '2026-09-30', '2026-10-01' ) );
		$this->assertFalse( Expiry::is_expired( '2026-10-01', '2026-09-30' ) );
	}

	public function test_a_blank_or_broken_date_never_expires(): void {
		foreach ( array( '', 'soon', '2026-02-30', '2026-1-5', '11/10/2026', '2026-10-11 10:00:00' ) as $value ) {
			$this->assertFalse( Expiry::is_expired( $value, '2030-01-01' ), $value );
		}
	}

	public function test_only_real_calendar_dates_are_valid(): void {
		$this->assertTrue( Expiry::is_valid_date( '2028-02-29' ), 'leap day' );
		$this->assertFalse( Expiry::is_valid_date( '2027-02-29' ) );
		$this->assertFalse( Expiry::is_valid_date( '2026-13-01' ) );
		$this->assertFalse( Expiry::is_valid_date( '' ) );
	}

	public function test_today_is_the_date_in_the_site_timezone(): void {
		Functions\expect( 'wp_date' )->once()->with( 'Y-m-d' )->andReturn( '2026-10-11' );

		$this->assertSame( '2026-10-11', Expiry::today() );
	}

	public function test_the_meta_query_keeps_offers_with_no_date_or_a_date_from_today_on(): void {
		$query = Expiry::meta_query( array(), '2026-10-11' );

		$this->assertSame( 'OR', $query['relation'] );
		$this->assertSame( 'NOT EXISTS', $query[0]['compare'] );
		$this->assertSame( '', $query[1]['value'] );
		$this->assertSame( '2026-10-11', $query[2]['value'] );
		$this->assertSame( '>=', $query[2]['compare'] );
		$this->assertSame( 'DATE', $query[2]['type'] );
		$this->assertSame( PostTypes::META_EXPIRES, $query[2]['key'] );
	}

	public function test_an_existing_meta_query_is_kept_and_both_must_match(): void {
		$existing = array(
			array(
				'key'   => 'terms',
				'value' => 'x',
			),
		);

		$query = Expiry::meta_query( $existing, '2026-10-11' );

		$this->assertSame( 'AND', $query['relation'] );
		$this->assertSame( $existing, $query[0] );
		$this->assertSame( 'OR', $query[1]['relation'] );
	}

	public function test_only_pure_offer_queries_are_filtered(): void {
		$this->assertTrue( Expiry::is_offer_query( 'bpch_offer' ) );
		$this->assertTrue( Expiry::is_offer_query( array( 'bpch_offer' ) ) );
		$this->assertFalse( Expiry::is_offer_query( 'post' ) );
		$this->assertFalse( Expiry::is_offer_query( 'any' ) );
		$this->assertFalse( Expiry::is_offer_query( array( 'bpch_offer', 'page' ) ) );
		$this->assertFalse( Expiry::is_offer_query( '' ) );
		$this->assertFalse( Expiry::is_offer_query( null ) );
	}

	public function test_an_offer_query_on_the_front_end_gets_the_expiry_condition(): void {
		Functions\when( 'is_admin' )->justReturn( false );
		Functions\when( 'wp_date' )->justReturn( '2026-10-11' );
		$query = Mockery::mock( 'WP_Query' );
		$query->shouldReceive( 'get' )->with( 'post_type' )->andReturn( 'bpch_offer' );
		$query->shouldReceive( 'get' )->with( 'meta_query' )->andReturn( '' );
		$query->shouldReceive( 'set' )->once()->with(
			'meta_query',
			Mockery::on(
				static function ( $meta_query ) {
					return 'OR' === $meta_query['relation'] && '2026-10-11' === $meta_query[2]['value'];
				}
			)
		);

		( new Expiry() )->hide_expired( $query );

		$this->addToAssertionCount( 1 );
	}

	public function test_other_queries_and_the_admin_are_left_alone(): void {
		$other = Mockery::mock( 'WP_Query' );
		$other->shouldReceive( 'get' )->with( 'post_type' )->andReturn( 'post' );
		$other->shouldNotReceive( 'set' );

		Functions\when( 'is_admin' )->justReturn( false );
		( new Expiry() )->hide_expired( $other );

		$offers = Mockery::mock( 'WP_Query' );
		$offers->shouldReceive( 'get' )->with( 'post_type' )->andReturn( 'bpch_offer' );
		$offers->shouldNotReceive( 'set' );

		Functions\when( 'is_admin' )->justReturn( true );
		( new Expiry() )->hide_expired( $offers );

		$this->addToAssertionCount( 1 );
	}

	public function test_a_visitor_asking_for_the_rest_list_gets_only_unexpired_offers(): void {
		Functions\when( 'current_user_can' )->justReturn( false );
		Functions\when( 'wp_date' )->justReturn( '2026-10-11' );

		$args = ( new Expiry() )->hide_expired_in_rest( array( 'post_type' => 'bpch_offer' ) );

		$this->assertSame( 'OR', $args['meta_query']['relation'] );
		$this->assertSame( '2026-10-11', $args['meta_query'][2]['value'] );
	}

	public function test_an_editor_sees_every_offer_in_the_rest_list(): void {
		Functions\expect( 'current_user_can' )->once()->with( 'edit_others_posts' )->andReturn( true );

		$args = array( 'post_type' => 'bpch_offer' );

		$this->assertSame( $args, ( new Expiry() )->hide_expired_in_rest( $args ) );
	}

	public function test_the_offer_date_is_sanitized_to_a_real_date_or_nothing(): void {
		$this->assertSame( '2026-12-31', PostTypes::sanitize_date( ' 2026-12-31 ' ) );
		$this->assertSame( '', PostTypes::sanitize_date( '2026-02-30' ) );
		$this->assertSame( '', PostTypes::sanitize_date( 'next friday' ) );
		$this->assertSame( '', PostTypes::sanitize_date( null ) );
		$this->assertSame( '', PostTypes::sanitize_date( array( '2026-12-31' ) ) );
	}
}
