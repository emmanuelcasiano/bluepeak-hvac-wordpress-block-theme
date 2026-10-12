<?php
/**
 * Tests for Services\TypeBadge.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Services\Catalog;
use BluePeakClimate\Core\Services\PostTypes;
use BluePeakClimate\Core\Services\TypeBadge;
use BluePeakClimate\Core\Support\Icons;

final class TypeBadgeTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		Functions\stubEscapeFunctions();
		Functions\when( 'get_block_wrapper_attributes' )->alias(
			static function ( $extra ) {
				return 'class="wp-block-bpch-service-type ' . $extra['class'] . '"';
			}
		);
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * A block with a post in its context, the way the Query Loop passes it.
	 *
	 * @param int $post_id Post ID.
	 * @return object
	 */
	private function block( int $post_id ): object {
		return (object) array( 'context' => array( 'postId' => $post_id ) );
	}

	/**
	 * Makes get_the_terms() return one type term.
	 *
	 * @param string $slug Term slug.
	 * @param string $name Term name.
	 */
	private function type( string $slug, string $name ): void {
		Functions\when( 'get_the_terms' )->justReturn( array( (object) array( 'slug' => $slug, 'name' => $name ) ) );
	}

	public function test_every_type_the_catalog_uses_has_an_icon_that_exists(): void {
		$slugs = array_keys( Catalog::types() );

		$this->assertNotEmpty( $slugs );

		foreach ( $slugs as $slug ) {
			$this->assertNotSame( '', TypeBadge::icon_for( $slug ), $slug );
			$this->assertNotSame( '', Icons::svg( TypeBadge::icon_for( $slug ) ), $slug );
		}
	}

	public function test_an_unknown_type_has_no_icon(): void {
		$this->assertSame( '', TypeBadge::icon_for( 'solar' ) );
		$this->assertSame( '', TypeBadge::icon_for( '' ) );
	}

	public function test_it_prints_the_icon_and_the_name_of_the_type(): void {
		$this->type( 'repair', 'Repair' );

		$html = ( new TypeBadge() )->render( array(), '', $this->block( 7 ) );

		$this->assertStringContainsString( 'bpch-type', $html );
		$this->assertStringContainsString( '<span class="bpch-type__icon" aria-hidden="true"><svg', $html );
		$this->assertStringContainsString( '<span class="bpch-type__label">Repair</span>', $html );
	}

	public function test_the_name_is_escaped(): void {
		$this->type( 'repair', '<b>Repair</b>' );

		$html = ( new TypeBadge() )->render( array(), '', $this->block( 7 ) );

		$this->assertStringNotContainsString( '<b>', $html );
	}

	public function test_a_type_with_no_icon_still_prints_its_name(): void {
		$this->type( 'solar', 'Solar' );

		$html = ( new TypeBadge() )->render( array(), '', $this->block( 7 ) );

		$this->assertStringNotContainsString( 'bpch-type__icon', $html );
		$this->assertStringContainsString( '>Solar</span>', $html );
	}

	public function test_a_service_with_no_type_prints_nothing(): void {
		Functions\when( 'get_the_terms' )->justReturn( false );
		$this->assertSame( '', ( new TypeBadge() )->render( array(), '', $this->block( 7 ) ) );

		Functions\when( 'get_the_terms' )->justReturn( array() );
		$this->assertSame( '', ( new TypeBadge() )->render( array(), '', $this->block( 7 ) ) );
	}

	public function test_it_asks_for_the_type_taxonomy_of_the_card_post(): void {
		Functions\expect( 'get_the_terms' )->once()->with( 42, PostTypes::TYPE )->andReturn( false );

		$this->assertSame( '', ( new TypeBadge() )->render( array(), '', $this->block( 42 ) ) );
	}

	public function test_outside_a_loop_there_is_no_post_and_nothing_is_printed(): void {
		$this->assertSame( '', ( new TypeBadge() )->render( array(), '', null ) );
		$this->assertSame( '', ( new TypeBadge() )->render( array(), '', $this->block( 0 ) ) );
	}
}
