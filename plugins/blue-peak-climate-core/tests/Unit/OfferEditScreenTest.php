<?php
/**
 * Tests for Offers\EditScreen and Offers\Bindings.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Offers\Bindings;
use BluePeakClimate\Core\Offers\EditScreen;
use BluePeakClimate\Core\Offers\PostTypes;

final class OfferEditScreenTest extends TestCase {

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
		Functions\when( 'get_option' )->justReturn( 'Y-m-d' );
		Functions\when( 'mysql2date' )->alias(
			static function ( $format, $date ) {
				return gmdate( $format, strtotime( $date ) );
			}
		);
		$_POST = array();
	}

	protected function tearDown(): void {
		$_POST = array();
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * Fills $_POST as the edit form would.
	 *
	 * @param string $expires Date typed in the form.
	 * @param string $terms   Fine print typed in the form.
	 */
	private function submit_form( string $expires, string $terms ): void {
		$_POST = array(
			'bpch_offer_nonce'   => 'good-nonce',
			'bpch_offer_expires' => $expires,
			'bpch_offer_terms'   => $terms,
		);
	}

	public function test_date_and_fine_print_are_saved_when_the_nonce_and_permission_are_good(): void {
		$this->submit_form( '2026-12-31', ' New customers only. ' );
		Functions\when( 'wp_verify_nonce' )->justReturn( 1 );
		Functions\when( 'current_user_can' )->justReturn( true );
		Functions\expect( 'update_post_meta' )->once()->with( 12, PostTypes::META_EXPIRES, '2026-12-31' );
		Functions\expect( 'update_post_meta' )->once()->with( 12, PostTypes::META_TERMS, 'New customers only.' );

		( new EditScreen() )->save_details( 12 );

		$this->addToAssertionCount( 1 );
	}

	public function test_blank_values_remove_the_meta_so_the_offer_never_expires(): void {
		$this->submit_form( '', '  ' );
		Functions\when( 'wp_verify_nonce' )->justReturn( 1 );
		Functions\when( 'current_user_can' )->justReturn( true );
		Functions\expect( 'delete_post_meta' )->once()->with( 12, PostTypes::META_EXPIRES );
		Functions\expect( 'delete_post_meta' )->once()->with( 12, PostTypes::META_TERMS );
		Functions\expect( 'update_post_meta' )->never();

		( new EditScreen() )->save_details( 12 );

		$this->addToAssertionCount( 1 );
	}

	public function test_an_impossible_date_is_not_stored(): void {
		$this->submit_form( '2026-02-30', '' );
		Functions\when( 'wp_verify_nonce' )->justReturn( 1 );
		Functions\when( 'current_user_can' )->justReturn( true );
		Functions\expect( 'delete_post_meta' )->twice();
		Functions\expect( 'update_post_meta' )->never();

		( new EditScreen() )->save_details( 12 );

		$this->addToAssertionCount( 1 );
	}

	public function test_nothing_is_saved_with_a_bad_nonce(): void {
		$this->submit_form( '2026-12-31', 'x' );
		Functions\when( 'wp_verify_nonce' )->justReturn( false );
		Functions\expect( 'update_post_meta' )->never();
		Functions\expect( 'delete_post_meta' )->never();

		( new EditScreen() )->save_details( 12 );

		$this->addToAssertionCount( 1 );
	}

	public function test_nothing_is_saved_without_a_nonce_as_in_quick_edit(): void {
		$_POST = array( 'bpch_offer_expires' => '2026-12-31' );
		Functions\expect( 'update_post_meta' )->never();
		Functions\expect( 'delete_post_meta' )->never();

		( new EditScreen() )->save_details( 12 );

		$this->addToAssertionCount( 1 );
	}

	public function test_nothing_is_saved_without_permission_to_edit_the_offer(): void {
		$this->submit_form( '2026-12-31', 'x' );
		Functions\when( 'wp_verify_nonce' )->justReturn( 1 );
		Functions\expect( 'current_user_can' )->once()->with( 'edit_post', 12 )->andReturn( false );
		Functions\expect( 'update_post_meta' )->never();

		( new EditScreen() )->save_details( 12 );

		$this->addToAssertionCount( 1 );
	}

	public function test_the_expiry_column_says_expired_ends_or_no_end_date(): void {
		$this->assertSame( 'No end date', EditScreen::expiry_label( '', '2026-10-11' ) );
		$this->assertSame( 'No end date', EditScreen::expiry_label( 'garbage', '2026-10-11' ) );
		$this->assertSame( 'Expired 2026-10-10', EditScreen::expiry_label( '2026-10-10', '2026-10-11' ) );
		$this->assertSame( 'Ends 2026-10-11', EditScreen::expiry_label( '2026-10-11', '2026-10-11' ) );
		$this->assertSame( 'Ends 2026-12-31', EditScreen::expiry_label( '2026-12-31', '2026-10-11' ) );
	}

	public function test_the_ends_label_binding_text(): void {
		$this->assertSame( '', Bindings::ends_label( '', '2026-10-11' ), 'no date, no label' );
		$this->assertSame( '', Bindings::ends_label( 'soon', '2026-10-11' ) );
		$this->assertSame( 'Ends today', Bindings::ends_label( '2026-10-11', '2026-10-11' ) );
		$this->assertSame( 'Ends 2026-12-31', Bindings::ends_label( '2026-12-31', '2026-10-11' ) );
	}

	public function test_the_binding_reads_the_offer_from_the_block_context(): void {
		Functions\when( 'wp_date' )->justReturn( '2026-10-11' );
		Functions\expect( 'get_post_meta' )->once()->with( 41, PostTypes::META_EXPIRES, true )->andReturn( '2026-12-31' );
		$block = (object) array( 'context' => array( 'postId' => 41 ) );

		$this->assertSame( 'Ends 2026-12-31', ( new Bindings() )->get_value( array( 'key' => 'ends_label' ), $block, 'content' ) );
	}

	public function test_the_binding_gives_an_empty_label_for_an_offer_without_a_date(): void {
		Functions\when( 'wp_date' )->justReturn( '2026-10-11' );
		Functions\when( 'get_post_meta' )->justReturn( '' );
		$block = (object) array( 'context' => array( 'postId' => 41 ) );

		$this->assertSame( '', ( new Bindings() )->get_value( array( 'key' => 'ends_label' ), $block, 'content' ) );
	}

	public function test_the_binding_keeps_the_saved_content_for_an_unknown_key_or_no_offer(): void {
		$block = (object) array( 'context' => array( 'postId' => 41 ) );

		$this->assertNull( ( new Bindings() )->get_value( array( 'key' => 'nope' ), $block, 'content' ) );
		$this->assertNull( ( new Bindings() )->get_value( array(), $block, 'content' ) );
		$this->assertNull( ( new Bindings() )->get_value( array( 'key' => 'ends_label' ), (object) array( 'context' => array() ), 'content' ) );
		$this->assertNull( ( new Bindings() )->get_value( array( 'key' => 'ends_label' ), null, 'content' ) );
	}
}
