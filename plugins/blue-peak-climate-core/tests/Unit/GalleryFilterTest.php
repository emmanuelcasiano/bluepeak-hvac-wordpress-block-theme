<?php
/**
 * Tests for Gallery\Filter.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Gallery\Filter;

final class GalleryFilterTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		Functions\stubTranslationFunctions();
		Functions\when( '_n' )->alias(
			static function ( $single, $plural, $number ) {
				return 1 === $number ? $single : $plural;
			}
		);
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * A photo the way the Group block renders it.
	 *
	 * @param string $slug Category slug.
	 * @return string
	 */
	private function item( string $slug ): string {
		return '<div class="wp-block-group bpch-gallery__item bpch-gallery__item--' . $slug . '"><div class="wp-block-group bpch-gallery__caption">x</div></div>';
	}

	public function test_the_category_is_read_from_the_class_attribute(): void {
		$this->assertSame( 'cooling', Filter::category_from_class( 'wp-block-group bpch-gallery__item bpch-gallery__item--cooling' ) );
		$this->assertSame( 'crew', Filter::category_from_class( 'bpch-gallery__item--crew bpch-gallery__item' ) );
		$this->assertSame( 'comfort', Filter::category_from_class( 'bpch-gallery__item--comfort' ) );
	}

	public function test_a_photo_with_no_category_class_has_no_category(): void {
		$this->assertSame( '', Filter::category_from_class( 'wp-block-group bpch-gallery__item' ) );
		$this->assertSame( '', Filter::category_from_class( '' ) );
		$this->assertSame( '', Filter::category_from_class( 'bpch-gallery__item-- x' ) );
	}

	public function test_a_longer_class_that_only_starts_the_same_is_not_a_category(): void {
		$this->assertSame( '', Filter::category_from_class( 'bpch-gallery__itemx--cooling' ) );
	}

	public function test_counts_follow_the_chip_order_not_the_page_order(): void {
		$html = $this->item( 'crew' ) . $this->item( 'cooling' ) . $this->item( 'crew' ) . $this->item( 'heating' ) . $this->item( 'cooling' ) . $this->item( 'crew' );

		$counts = Filter::counts( $html );

		$this->assertSame( array( 'cooling', 'heating', 'crew' ), array_keys( $counts ) );
		$this->assertSame( array( 2, 1, 3 ), array_values( $counts ) );
	}

	public function test_a_category_with_no_photo_gets_no_chip(): void {
		$counts = Filter::counts( $this->item( 'cooling' ) . $this->item( 'cooling' ) );

		$this->assertSame( array( 'cooling' => 2 ), $counts );
	}

	public function test_a_category_the_filter_does_not_know_gets_no_chip(): void {
		$counts = Filter::counts( $this->item( 'cooling' ) . $this->item( 'solar' ) );

		$this->assertSame( array( 'cooling' => 1 ), $counts );
	}

	public function test_no_photos_means_no_counts(): void {
		$this->assertSame( array(), Filter::counts( '' ) );
		$this->assertSame( array(), Filter::counts( '<p>No photos yet.</p>' ) );
	}

	public function test_the_messages_say_how_many_photos_are_shown(): void {
		$messages = Filter::status_messages(
			array(
				'cooling' => 2,
				'heating' => 1,
			)
		);

		$this->assertSame( 'Showing all 3 photos', $messages['all'] );
		$this->assertSame( 'Showing 2 Cooling photos', $messages['cooling'] );
		$this->assertSame( 'Showing 1 Heating photo', $messages['heating'] );
		$this->assertArrayNotHasKey( 'crew', $messages );
	}

	public function test_one_photo_in_total_is_singular(): void {
		$this->assertSame( 'Showing all 1 photo', Filter::status_messages( array( 'crew' => 1 ) )['all'] );
	}
}
