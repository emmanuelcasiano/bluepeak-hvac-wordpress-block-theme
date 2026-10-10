<?php
/**
 * Tests for Services\EditScreen.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Services\EditScreen;
use BluePeakClimate\Core\Services\PostTypes;

final class EditScreenTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		Functions\when( 'wp_unslash' )->returnArg();
		Functions\when( 'sanitize_text_field' )->alias(
			static function ( $value ) {
				return trim( wp_strip_all_tags_stub( $value ) );
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
	 * @param string $price Price typed in the form.
	 */
	private function submit_form( string $price ): void {
		$_POST = array(
			'bpch_service_nonce' => 'good-nonce',
			'bpch_price_from'    => $price,
		);
	}

	public function test_publishing_without_a_title_becomes_a_draft(): void {
		$screen = new EditScreen();

		$data = $screen->require_title(
			array(
				'post_type'   => PostTypes::POST_TYPE,
				'post_status' => 'publish',
				'post_title'  => '   ',
			)
		);

		$this->assertSame( 'draft', $data['post_status'] );
	}

	public function test_every_public_status_needs_a_title(): void {
		foreach ( array( 'publish', 'future', 'pending', 'private' ) as $status ) {
			$data = ( new EditScreen() )->require_title(
				array(
					'post_type'   => PostTypes::POST_TYPE,
					'post_status' => $status,
					'post_title'  => '',
				)
			);

			$this->assertSame( 'draft', $data['post_status'], $status );
		}
	}

	public function test_a_titled_service_is_left_alone(): void {
		$data = array(
			'post_type'   => PostTypes::POST_TYPE,
			'post_status' => 'publish',
			'post_title'  => 'AC Repair',
		);

		$this->assertSame( $data, ( new EditScreen() )->require_title( $data ) );
	}

	public function test_drafts_and_auto_drafts_may_have_no_title(): void {
		foreach ( array( 'draft', 'auto-draft', 'trash' ) as $status ) {
			$data = array(
				'post_type'   => PostTypes::POST_TYPE,
				'post_status' => $status,
				'post_title'  => '',
			);

			$this->assertSame( $data, ( new EditScreen() )->require_title( $data ), $status );
		}
	}

	public function test_other_post_types_are_never_touched(): void {
		$data = array(
			'post_type'   => 'post',
			'post_status' => 'publish',
			'post_title'  => '',
		);

		$this->assertSame( $data, ( new EditScreen() )->require_title( $data ) );
	}

	public function test_redirect_gets_a_flag_only_after_a_missing_title(): void {
		Functions\when( 'add_query_arg' )->alias(
			static function ( $key, $value, $url ) {
				return $url . '&' . $key . '=' . $value;
			}
		);
		$screen = new EditScreen();

		$this->assertSame( 'post.php?x=1', $screen->flag_missing_title( 'post.php?x=1' ) );

		$screen->require_title(
			array(
				'post_type'   => PostTypes::POST_TYPE,
				'post_status' => 'publish',
				'post_title'  => '',
			)
		);

		$this->assertSame( 'post.php?x=1&bpch_title_required=1', $screen->flag_missing_title( 'post.php?x=1' ) );
	}

	public function test_price_is_saved_when_the_nonce_and_permission_are_good(): void {
		$this->submit_form( ' From $89 ' );
		Functions\when( 'wp_verify_nonce' )->justReturn( 1 );
		Functions\when( 'current_user_can' )->justReturn( true );
		Functions\expect( 'update_post_meta' )->once()->with( 12, PostTypes::META_PRICE, 'From $89' );

		( new EditScreen() )->save_price( 12 );

		$this->addToAssertionCount( 1 );
	}

	public function test_a_blank_price_removes_the_meta(): void {
		$this->submit_form( '  ' );
		Functions\when( 'wp_verify_nonce' )->justReturn( 1 );
		Functions\when( 'current_user_can' )->justReturn( true );
		Functions\expect( 'delete_post_meta' )->once()->with( 12, PostTypes::META_PRICE );
		Functions\expect( 'update_post_meta' )->never();

		( new EditScreen() )->save_price( 12 );

		$this->addToAssertionCount( 1 );
	}

	public function test_price_is_not_saved_with_a_bad_nonce(): void {
		$this->submit_form( 'From $89' );
		Functions\when( 'wp_verify_nonce' )->justReturn( false );
		Functions\expect( 'update_post_meta' )->never();
		Functions\expect( 'delete_post_meta' )->never();

		( new EditScreen() )->save_price( 12 );

		$this->addToAssertionCount( 1 );
	}

	public function test_price_is_not_saved_without_a_nonce_as_in_quick_edit(): void {
		$_POST = array( 'bpch_price_from' => 'From $89' );
		Functions\expect( 'update_post_meta' )->never();
		Functions\expect( 'delete_post_meta' )->never();

		( new EditScreen() )->save_price( 12 );

		$this->addToAssertionCount( 1 );
	}

	public function test_price_is_not_saved_without_permission_to_edit_the_service(): void {
		$this->submit_form( 'From $89' );
		Functions\when( 'wp_verify_nonce' )->justReturn( 1 );
		Functions\expect( 'current_user_can' )->once()->with( 'edit_post', 12 )->andReturn( false );
		Functions\expect( 'update_post_meta' )->never();

		( new EditScreen() )->save_price( 12 );

		$this->addToAssertionCount( 1 );
	}
}

/**
 * Stand-in for wp_strip_all_tags() inside the sanitize stub above.
 *
 * @param string $value Raw text.
 * @return string
 */
function wp_strip_all_tags_stub( $value ) {
	return strip_tags( $value );
}
