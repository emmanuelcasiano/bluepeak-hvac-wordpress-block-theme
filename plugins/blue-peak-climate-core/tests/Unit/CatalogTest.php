<?php
/**
 * Tests for Services\Catalog.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Services\Catalog;

final class CatalogTest extends TestCase {

	public function test_has_the_22_services_of_the_prototype(): void {
		$this->assertCount( 22, Catalog::services() );
	}

	public function test_counts_per_category_match_the_services_page(): void {
		$counts = array_count_values( array_column( Catalog::services(), 'category' ) );

		$this->assertSame(
			array(
				'heating'    => 10,
				'cooling'    => 10,
				'electrical' => 2,
			),
			$counts
		);
	}

	public function test_slugs_and_titles_are_unique(): void {
		$services = Catalog::services();

		$this->assertCount( 22, array_unique( array_column( $services, 'slug' ) ) );
		$this->assertCount( 22, array_unique( array_column( $services, 'title' ) ) );
	}

	public function test_every_service_uses_a_known_category_and_type(): void {
		foreach ( Catalog::services() as $service ) {
			$this->assertArrayHasKey( $service['category'], Catalog::categories(), $service['title'] );
			$this->assertArrayHasKey( $service['type'], Catalog::types(), $service['title'] );
		}
	}

	public function test_every_service_has_a_clean_slug_and_card_text(): void {
		foreach ( Catalog::services() as $service ) {
			$this->assertMatchesRegularExpression( '/^[a-z0-9]+(-[a-z0-9]+)*$/', $service['slug'] );
			$this->assertNotSame( '', trim( $service['excerpt'] ), $service['title'] );
		}
	}

	public function test_every_category_and_type_is_used(): void {
		$services = Catalog::services();

		$this->assertEqualsCanonicalizing( array_keys( Catalog::categories() ), array_unique( array_column( $services, 'category' ) ) );
		$this->assertEqualsCanonicalizing( array_keys( Catalog::types() ), array_unique( array_column( $services, 'type' ) ) );
	}
}
