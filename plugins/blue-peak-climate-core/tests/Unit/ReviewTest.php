<?php
/**
 * Tests for Reviews\Rating and Reviews\EditScreen.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Reviews\EditScreen;
use BluePeakClimate\Core\Reviews\PostTypes;
use BluePeakClimate\Core\Reviews\Rating;

final class ReviewTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		Functions\stubTranslationFunctions();
		Functions\stubEscapeFunctions();
		Functions\when( 'wp_unslash' )->returnArg();
		Functions\when( 'sanitize_text_field' )->alias(
			static function ( $value ) {
				return trim( strip_tags( $value ) );
			}
		);
		$_POST = array();
	}

	protected function tearDown(): void {
		$_POST = array();
		Monkey\tearDown();
		parent::tearDown();
	}

	public function test_a_rating_is_kept_between_one_and_five(): void {
		$this->assertSame( 1, Rating::clamp( 0 ) );
		$this->assertSame( 1, Rating::clamp( -3 ) );
		$this->assertSame( 3, Rating::clamp( '3' ) );
		$this->assertSame( 5, Rating::clamp( 9 ) );
		$this->assertSame( 4, Rating::clamp( 3.6 ) );
	}

	public function test_a_missing_or_junk_rating_falls_back_to_five(): void {
		foreach ( array( '', null, 'lots', array( 3 ), false ) as $value ) {
			$this->assertSame( 5, Rating::clamp( $value ), var_export( $value, true ) );
		}
	}

	public function test_stars_fill_to_the_rating_and_always_make_five(): void {
		$this->assertSame( '★★★★★', Rating::stars( 5 ) );
		$this->assertSame( '★★★★☆', Rating::stars( 4 ) );
		$this->assertSame( '★☆☆☆☆', Rating::stars( 1 ) );
		$this->assertSame( '★☆☆☆☆', Rating::stars( 0 ), 'clamped up to one star' );
		$this->assertSame( '★★★★★', Rating::stars( 7 ), 'clamped down to five' );
	}

	public function test_the_label_for_screen_readers_says_the_rating_in_words(): void {
		$this->assertSame( '4 out of 5 stars', Rating::label( 4 ) );
		$this->assertSame( '5 out of 5 stars', Rating::label( 99 ) );
	}

	public function test_the_block_prints_stars_hidden_from_screen_readers_and_a_text_alternative(): void {
		Functions\when( 'get_post_meta' )->justReturn( '4' );
		Functions\when( 'get_block_wrapper_attributes' )->justReturn( 'class="bpch-rating wp-block-bpch-review-rating"' );
		$block = (object) array( 'context' => array( 'postId' => 7 ) );

		$html = ( new Rating() )->render( array(), '', $block );

		$this->assertStringContainsString( '<span aria-hidden="true">★★★★☆</span>', $html );
		$this->assertStringContainsString( '<span class="bpch-sr-only">4 out of 5 stars</span>', $html );
	}

	public function test_the_block_prints_nothing_outside_a_review(): void {
		$this->assertSame( '', ( new Rating() )->render( array(), '', (object) array( 'context' => array() ) ) );
		$this->assertSame( '', ( new Rating() )->render( array(), '', null ) );
	}

	/**
	 * Fills $_POST as the edit form would.
	 *
	 * @param array<string, string> $fields Fields typed in the form.
	 */
	private function submit_form( array $fields ): void {
		$_POST = array_merge( array( 'bpch_review_nonce' => 'good-nonce' ), $fields );
	}

	public function test_details_are_saved_when_the_nonce_and_permission_are_good(): void {
		$this->submit_form(
			array(
				'bpch_review_reviewer' => ' Homeowner, Texas ',
				'bpch_review_service'  => 'Emergency AC repair',
				'bpch_review_rating'   => '4',
			)
		);
		Functions\when( 'wp_verify_nonce' )->justReturn( 1 );
		Functions\when( 'current_user_can' )->justReturn( true );
		Functions\expect( 'update_post_meta' )->once()->with( 12, PostTypes::META_RATING, 4 );
		Functions\expect( 'update_post_meta' )->once()->with( 12, PostTypes::META_REVIEWER, 'Homeowner, Texas' );
		Functions\expect( 'update_post_meta' )->once()->with( 12, PostTypes::META_SERVICE, 'Emergency AC repair' );

		( new EditScreen() )->save_details( 12 );

		$this->addToAssertionCount( 1 );
	}

	public function test_a_blank_customer_or_service_removes_the_meta_and_a_bad_rating_is_clamped(): void {
		$this->submit_form(
			array(
				'bpch_review_reviewer' => '',
				'bpch_review_service'  => '   ',
				'bpch_review_rating'   => '12',
			)
		);
		Functions\when( 'wp_verify_nonce' )->justReturn( 1 );
		Functions\when( 'current_user_can' )->justReturn( true );
		Functions\expect( 'update_post_meta' )->once()->with( 12, PostTypes::META_RATING, 5 );
		Functions\expect( 'delete_post_meta' )->once()->with( 12, PostTypes::META_REVIEWER );
		Functions\expect( 'delete_post_meta' )->once()->with( 12, PostTypes::META_SERVICE );

		( new EditScreen() )->save_details( 12 );

		$this->addToAssertionCount( 1 );
	}

	public function test_nothing_is_saved_with_a_bad_nonce_no_nonce_or_no_permission(): void {
		Functions\expect( 'update_post_meta' )->never();
		Functions\expect( 'delete_post_meta' )->never();

		$this->submit_form( array( 'bpch_review_rating' => '1' ) );
		Functions\when( 'wp_verify_nonce' )->justReturn( false );
		( new EditScreen() )->save_details( 12 );

		$_POST = array( 'bpch_review_rating' => '1' );
		( new EditScreen() )->save_details( 12 );

		$this->submit_form( array( 'bpch_review_rating' => '1' ) );
		Functions\when( 'wp_verify_nonce' )->justReturn( 1 );
		Functions\expect( 'current_user_can' )->once()->with( 'edit_post', 12 )->andReturn( false );
		( new EditScreen() )->save_details( 12 );

		$this->addToAssertionCount( 1 );
	}

	public function test_the_rating_column_reads_in_words(): void {
		Functions\when( 'get_post_meta' )->justReturn( '3' );

		ob_start();
		( new EditScreen() )->render_column( 'bpch_review_rating', 5 );
		$this->assertSame( '3 out of 5 stars', ob_get_clean() );

		ob_start();
		( new EditScreen() )->render_column( 'date', 5 );
		$this->assertSame( '', ob_get_clean() );
	}
}
