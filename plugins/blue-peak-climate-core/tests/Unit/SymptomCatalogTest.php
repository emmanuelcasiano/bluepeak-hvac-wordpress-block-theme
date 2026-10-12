<?php
/**
 * Tests for Symptoms\Catalog and Support\Icons.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Support\Icons;
use BluePeakClimate\Core\Symptoms\Catalog;

final class SymptomCatalogTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		Functions\stubTranslationFunctions();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	public function test_there_are_five_symptoms_in_the_designed_order(): void {
		$ids = array_column( Catalog::all(), 'id' );

		$this->assertSame( array( 'warm-air', 'no-heat', 'breaker-trips', 'water-leak', 'noises' ), $ids );
	}

	public function test_ids_are_unique_and_safe_to_use_in_element_ids(): void {
		$ids = array_column( Catalog::all(), 'id' );

		$this->assertSame( $ids, array_values( array_unique( $ids ) ) );

		foreach ( $ids as $id ) {
			$this->assertMatchesRegularExpression( '/^[a-z0-9-]+$/', $id );
		}
	}

	public function test_every_symptom_has_all_its_copy(): void {
		foreach ( Catalog::all() as $symptom ) {
			foreach ( array( 'label', 'badge', 'title', 'plan', 'tip', 'cta' ) as $key ) {
				$this->assertIsString( $symptom[ $key ], $symptom['id'] . ' ' . $key );
				$this->assertNotSame( '', trim( $symptom[ $key ] ), $symptom['id'] . ' ' . $key );
			}

			$this->assertCount( 3, $symptom['causes'], $symptom['id'] );
			$this->assertStringStartsWith( 'Before we arrive:', $symptom['tip'], $symptom['id'] );
		}
	}

	public function test_every_symptom_icon_exists(): void {
		foreach ( Catalog::all() as $symptom ) {
			$this->assertNotSame( '', Icons::svg( $symptom['icon'] ), $symptom['id'] );
		}
	}

	public function test_an_icon_is_hidden_from_screen_readers_and_takes_the_text_colour(): void {
		$svg = Icons::svg( 'flame' );

		$this->assertStringStartsWith( '<svg ', $svg );
		$this->assertStringContainsString( 'aria-hidden="true"', $svg );
		$this->assertStringContainsString( 'stroke="currentColor"', $svg );
	}

	public function test_an_unknown_icon_draws_nothing(): void {
		$this->assertSame( '', Icons::svg( 'no-such-icon' ) );
		$this->assertSame( '', Icons::svg( '' ) );
	}
}
