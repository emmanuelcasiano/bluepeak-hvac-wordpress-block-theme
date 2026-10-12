<?php
/**
 * Tests for Reviews\Carousel.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Reviews\Carousel;
use BluePeakClimate\Core\Reviews\PostTypes;

final class ReviewCarouselTest extends TestCase {

	/**
	 * Arguments the last get_posts() call received.
	 *
	 * @var array
	 */
	private $query_args = array();

	/**
	 * Meta by post ID, read by the stubbed get_post_meta().
	 *
	 * @var array
	 */
	private $meta = array();

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		Functions\when( 'wp_strip_all_tags' )->alias(
			static function ( $text ) {
				return trim( strip_tags( $text ) );
			}
		);
		Functions\when( 'get_post_meta' )->alias(
			function ( $post_id, $key ) {
				return $this->meta[ $post_id ][ $key ] ?? '';
			}
		);
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * Makes get_posts() return these reviews.
	 *
	 * @param array $posts Review posts as objects.
	 */
	private function reviews( array $posts ): void {
		Functions\when( 'get_posts' )->alias(
			function ( $args ) use ( $posts ) {
				$this->query_args = $args;
				return $posts;
			}
		);
	}

	private function post( int $id, string $excerpt ): object {
		return (object) array(
			'ID'           => $id,
			'post_excerpt' => $excerpt,
		);
	}

	public function test_it_asks_only_for_published_reviews_in_the_order_set_on_the_screen(): void {
		$this->reviews( array() );

		Carousel::slides();

		$this->assertSame( PostTypes::POST_TYPE, $this->query_args['post_type'] );
		$this->assertSame( 'publish', $this->query_args['post_status'] );
		$this->assertSame(
			array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
			$this->query_args['orderby']
		);
		$this->assertTrue( $this->query_args['no_found_rows'] );
		$this->assertSame( Carousel::LIMIT, $this->query_args['posts_per_page'] );
	}

	public function test_the_limit_is_passed_on_and_never_below_one(): void {
		$this->reviews( array() );

		Carousel::slides( 3 );
		$this->assertSame( 3, $this->query_args['posts_per_page'] );

		Carousel::slides( 0 );
		$this->assertSame( 1, $this->query_args['posts_per_page'] );
	}

	public function test_a_slide_carries_the_text_the_names_and_the_rating(): void {
		$this->reviews( array( $this->post( 7, 'The house was cool again by three.' ) ) );
		$this->meta[7] = array(
			PostTypes::META_REVIEWER => 'Homeowner, Texas',
			PostTypes::META_SERVICE  => 'Emergency AC repair',
			PostTypes::META_RATING   => '4',
		);

		$this->assertSame(
			array(
				array(
					'quote'    => 'The house was cool again by three.',
					'reviewer' => 'Homeowner, Texas',
					'service'  => 'Emergency AC repair',
					'rating'   => 4,
				),
			),
			Carousel::slides()
		);
	}

	public function test_a_review_with_no_text_is_skipped(): void {
		$this->reviews(
			array(
				$this->post( 1, '' ),
				$this->post( 2, "  \n " ),
				$this->post( 3, '<b></b>' ),
				$this->post( 4, 'Fixed in one visit.' ),
			)
		);

		$slides = Carousel::slides();

		$this->assertCount( 1, $slides );
		$this->assertSame( 'Fixed in one visit.', $slides[0]['quote'] );
	}

	public function test_markup_in_the_text_is_removed_and_the_names_are_trimmed(): void {
		$this->reviews( array( $this->post( 5, '<script>x</script>Great <em>work</em>.' ) ) );
		$this->meta[5] = array( PostTypes::META_REVIEWER => '  A. Homeowner ' );

		$slide = Carousel::slides()[0];

		$this->assertStringNotContainsString( '<', $slide['quote'] );
		$this->assertSame( 'A. Homeowner', $slide['reviewer'] );
		$this->assertSame( '', $slide['service'], 'optional' );
	}

	public function test_a_missing_or_wild_rating_is_kept_between_one_and_five(): void {
		$this->reviews( array( $this->post( 8, 'One.' ), $this->post( 9, 'Two.' ), $this->post( 10, 'Three.' ) ) );
		$this->meta[9]  = array( PostTypes::META_RATING => '9' );
		$this->meta[10] = array( PostTypes::META_RATING => '0' );

		$ratings = array_column( Carousel::slides(), 'rating' );

		$this->assertSame( array( 5, 5, 1 ), $ratings );
	}

	public function test_no_reviews_means_no_slides(): void {
		$this->reviews( array() );

		$this->assertSame( array(), Carousel::slides() );
	}
}
