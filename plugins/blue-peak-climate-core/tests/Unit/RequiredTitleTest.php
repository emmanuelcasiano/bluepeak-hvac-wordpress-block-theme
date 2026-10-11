<?php
/**
 * Tests for Support\RequiredTitle.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Support\RequiredTitle;

final class RequiredTitleTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * A guard for the offer type.
	 *
	 * @return RequiredTitle
	 */
	private function guard(): RequiredTitle {
		return new RequiredTitle(
			'bpch_offer',
			static function () {
				return 'Needs a title.';
			}
		);
	}

	public function test_publishing_without_a_title_becomes_a_draft_for_every_public_status(): void {
		foreach ( array( 'publish', 'future', 'pending', 'private' ) as $status ) {
			$data = $this->guard()->require_title(
				array(
					'post_type'   => 'bpch_offer',
					'post_status' => $status,
					'post_title'  => '  ',
				)
			);

			$this->assertSame( 'draft', $data['post_status'], $status );
		}
	}

	public function test_a_titled_post_drafts_and_other_types_are_left_alone(): void {
		$titled = array(
			'post_type'   => 'bpch_offer',
			'post_status' => 'publish',
			'post_title'  => 'Spring tune-up',
		);
		$draft  = array(
			'post_type'   => 'bpch_offer',
			'post_status' => 'draft',
			'post_title'  => '',
		);
		$other  = array(
			'post_type'   => 'post',
			'post_status' => 'publish',
			'post_title'  => '',
		);

		$this->assertSame( $titled, $this->guard()->require_title( $titled ) );
		$this->assertSame( $draft, $this->guard()->require_title( $draft ) );
		$this->assertSame( $other, $this->guard()->require_title( $other ) );
	}

	public function test_the_redirect_gets_a_flag_only_after_a_missing_title(): void {
		Functions\when( 'add_query_arg' )->alias(
			static function ( $key, $value, $url ) {
				return $url . '&' . $key . '=' . $value;
			}
		);
		$guard = $this->guard();

		$this->assertSame( 'post.php?x=1', $guard->flag_missing_title( 'post.php?x=1' ) );

		$guard->require_title(
			array(
				'post_type'   => 'bpch_offer',
				'post_status' => 'publish',
				'post_title'  => '',
			)
		);

		$this->assertSame( 'post.php?x=1&bpch_title_required=1', $guard->flag_missing_title( 'post.php?x=1' ) );
	}

	public function test_each_guard_remembers_only_its_own_missing_title(): void {
		Functions\when( 'add_query_arg' )->alias(
			static function ( $key, $value, $url ) {
				return $url . '&' . $key . '=' . $value;
			}
		);
		$offers  = $this->guard();
		$reviews = new RequiredTitle(
			'bpch_review',
			static function () {
				return 'Needs a title.';
			}
		);

		$offers->require_title(
			array(
				'post_type'   => 'bpch_offer',
				'post_status' => 'publish',
				'post_title'  => '',
			)
		);

		$this->assertSame( 'post.php?x=1', $reviews->flag_missing_title( 'post.php?x=1' ) );
	}
}
