<?php
/**
 * Tests for Seo\ServiceSchema.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Filters;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Seo\ServiceSchema;

final class ServiceSchemaTest extends TestCase {

	private const PROVIDER = 'http://hvac.local/#organization';

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		Functions\stubTranslationFunctions();
		Functions\when( 'wp_strip_all_tags' )->alias( 'strip_tags' );
		Functions\when( 'home_url' )->alias(
			static function ( $path = '' ) {
				return 'http://hvac.local' . $path;
			}
		);
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * Fields of a typical service.
	 *
	 * @param array<string, mixed> $override Values to change.
	 * @return array<string, mixed>
	 */
	private function fields( array $override = array() ): array {
		return array_merge(
			array(
				'name'     => 'Furnace Repair',
				'excerpt'  => 'No heat or odd noises. We find the fault and fix it.',
				'url'      => 'http://hvac.local/services/furnace-repair/',
				'category' => 'Heating',
				'type'     => 'Repair',
				'price'    => '',
			),
			$override
		);
	}

	private function area(): array {
		return array(
			'@type' => 'AdministrativeArea',
			'name'  => 'Texas',
		);
	}

	public function test_registers_on_the_rank_math_graph_filter(): void {
		$schema = new ServiceSchema();
		$schema->register();

		$this->assertSame( 20, has_filter( 'rank_math/json_ld', array( $schema, 'filter' ) ) );
	}

	public function test_the_node_describes_the_service_and_points_to_the_business(): void {
		Filters\expectApplied( 'bpch_core_schema_currency' )->never();

		$node = ( new ServiceSchema() )->service( $this->fields(), self::PROVIDER, $this->area() );

		$this->assertSame( 'Service', $node['@type'] );
		$this->assertSame( 'Furnace Repair', $node['name'] );
		$this->assertSame( 'No heat or odd noises. We find the fault and fix it.', $node['description'] );
		$this->assertSame( 'http://hvac.local/services/furnace-repair/', $node['url'] );
		$this->assertSame( 'Heating', $node['category'] );
		$this->assertSame( 'Repair', $node['serviceType'] );
		$this->assertSame( array( '@id' => self::PROVIDER ), $node['provider'] );
		$this->assertSame( $this->area(), $node['areaServed'] );
		$this->assertTrue( $node['isPrimary'] );
	}

	public function test_no_price_means_no_offer(): void {
		$node = ( new ServiceSchema() )->service( $this->fields(), self::PROVIDER, $this->area() );

		$this->assertArrayNotHasKey( 'offers', $node );
	}

	public function test_a_starting_price_becomes_a_minimum_price_offer(): void {
		Filters\expectApplied( 'bpch_core_schema_currency' )->once()->with( 'USD' )->andReturn( 'USD' );

		$node = ( new ServiceSchema() )->service( $this->fields( array( 'price' => '$89' ) ), self::PROVIDER, $this->area() );

		$this->assertSame(
			array(
				'@type'              => 'Offer',
				'priceSpecification' => array(
					'@type'         => 'PriceSpecification',
					'minPrice'      => 89,
					'priceCurrency' => 'USD',
				),
			),
			$node['offers']
		);
	}

	public function test_missing_optional_fields_are_left_out(): void {
		$node = ( new ServiceSchema() )->service(
			$this->fields(
				array(
					'excerpt'  => '   ',
					'category' => '',
					'type'     => '',
				)
			),
			self::PROVIDER,
			array()
		);

		foreach ( array( 'description', 'category', 'serviceType', 'areaServed', 'offers' ) as $key ) {
			$this->assertArrayNotHasKey( $key, $node );
		}
	}

	public function test_html_in_the_excerpt_is_removed(): void {
		$node = ( new ServiceSchema() )->service( $this->fields( array( 'excerpt' => '<p>Fast <strong>fix</strong>.</p>' ) ), self::PROVIDER, array() );

		$this->assertSame( 'Fast fix.', $node['description'] );
	}

	/**
	 * @dataProvider price_provider
	 *
	 * @param mixed          $value    Meta value.
	 * @param int|float|null $expected Price read from it.
	 */
	public function test_price( $value, $expected ): void {
		$this->assertSame( $expected, ServiceSchema::price( $value ) );
	}

	/**
	 * @return array<string, array{mixed, int|float|null}>
	 */
	public function price_provider(): array {
		return array(
			'plain number'          => array( '89', 89 ),
			'dollar sign'           => array( '$89', 89 ),
			'cents'                 => array( '$89.50', 89.5 ),
			'thousands separator'   => array( '$1,250', 1250 ),
			'with words'            => array( 'From $120 per visit', 120 ),
			'a real number'         => array( 75, 75 ),
			'no number'             => array( 'Call for a quote', null ),
			'zero'                  => array( '$0', null ),
			'blank'                 => array( '', null ),
			'not text'              => array( array( '89' ), null ),
		);
	}

	public function test_the_filter_adds_the_node_on_a_single_service(): void {
		Functions\when( 'is_singular' )->alias(
			static function ( $type = '' ) {
				return 'bpch_service' === $type;
			}
		);
		Functions\when( 'get_post' )->justReturn(
			(object) array(
				'ID'           => 12,
				'post_excerpt' => 'No heat or odd noises.',
			)
		);
		Functions\when( 'get_the_title' )->justReturn( 'Furnace Repair' );
		Functions\when( 'get_permalink' )->justReturn( 'http://hvac.local/services/furnace-repair/' );
		Functions\when( 'get_post_meta' )->justReturn( '$89' );
		Functions\when( 'get_the_terms' )->alias(
			static function ( $post_id, $taxonomy ) {
				return 'bpch_service_category' === $taxonomy
					? array( (object) array( 'name' => 'Heating' ) )
					: array( (object) array( 'name' => 'Repair' ) );
			}
		);
		Functions\when( 'get_option' )->justReturn( array( 'area_served' => 'Texas' ) );

		$result = ( new ServiceSchema() )->filter( array( 'publisher' => array( '@id' => 'http://hvac.local/#organization' ) ) );

		$node = $result[ ServiceSchema::KEY ];
		$this->assertSame( 'Furnace Repair', $node['name'] );
		$this->assertSame( 'Heating', $node['category'] );
		$this->assertSame( 'Repair', $node['serviceType'] );
		$this->assertSame( 89, $node['offers']['priceSpecification']['minPrice'] );
		$this->assertSame( array( '@id' => 'http://hvac.local/#organization' ), $node['provider'] );
		$this->assertSame( 'Texas', $node['areaServed']['name'] );
		$this->assertArrayHasKey( 'publisher', $result );
	}

	public function test_the_filter_does_nothing_on_other_pages(): void {
		Functions\when( 'is_singular' )->justReturn( false );

		$graph = array( 'WebSite' => array( '@type' => 'WebSite' ) );

		$this->assertSame( $graph, ( new ServiceSchema() )->filter( $graph ) );
		$this->assertSame( 'not an array', ( new ServiceSchema() )->filter( 'not an array' ) );
	}
}
