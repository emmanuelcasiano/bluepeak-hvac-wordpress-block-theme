<?php
/**
 * Tests for Seo\BusinessSchema.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Seo\BusinessSchema;

final class BusinessSchemaTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		Functions\stubTranslationFunctions();
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
	 * Makes get_option() return the given saved settings.
	 *
	 * @param mixed $saved What the option holds.
	 */
	private function saved( $saved ): void {
		Functions\when( 'get_option' )->justReturn( $saved );
	}

	/**
	 * The node Rank Math builds for a company, before this class changes it.
	 *
	 * @return array<string, mixed>
	 */
	private function rank_math_node(): array {
		return array(
			'@type'  => 'Organization',
			'@id'    => 'http://hvac.local/#organization',
			'name'   => 'hvac',
			'sameAs' => array( 'https://facebook.com/from-rank-math' ),
			'logo'   => array( '@id' => 'http://hvac.local/#logo' ),
		);
	}

	private function settings( array $override = array() ): array {
		return array_merge(
			array(
				'legal_name'      => 'Blue Peak Climate',
				'phone'           => '(555) 123-4567',
				'email'           => 'hello@bluepeakclimate.com',
				'hours'           => 'Emergency line open 24/7',
				'area_served'     => 'Texas',
				'facebook'        => '',
				'instagram'       => '',
				'youtube'         => '',
				'google_business' => '',
			),
			$override
		);
	}

	public function test_registers_on_the_rank_math_graph_filter(): void {
		$schema = new BusinessSchema();
		$schema->register();

		$this->assertSame( 20, has_filter( 'rank_math/json_ld', array( $schema, 'filter' ) ) );
		$this->assertSame( 10, has_filter( 'rank_math/schema/add_global_entities', array( $schema, 'keep_global_entities' ) ) );
	}

	public function test_a_single_page_always_keeps_the_organization_website_and_page_nodes(): void {
		Functions\when( 'is_singular' )->justReturn( true );

		$this->assertTrue( ( new BusinessSchema() )->keep_global_entities( false ) );
	}

	public function test_other_pages_keep_rank_maths_own_answer(): void {
		Functions\when( 'is_singular' )->justReturn( false );

		$this->assertFalse( ( new BusinessSchema() )->keep_global_entities( false ) );
		$this->assertTrue( ( new BusinessSchema() )->keep_global_entities( true ) );
	}

	public function test_the_node_becomes_an_hvac_business_with_the_settings_values(): void {
		$node = ( new BusinessSchema() )->business( $this->rank_math_node(), $this->settings(), 'http://hvac.local/' );

		$this->assertSame( 'HVACBusiness', $node['@type'] );
		$this->assertSame( 'Blue Peak Climate', $node['name'] );
		$this->assertSame( 'http://hvac.local/', $node['url'] );
		$this->assertSame( '+15551234567', $node['telephone'] );
		$this->assertSame( 'hello@bluepeakclimate.com', $node['email'] );
		$this->assertSame(
			array(
				'@type' => 'AdministrativeArea',
				'name'  => 'Texas',
			),
			$node['areaServed']
		);
	}

	public function test_the_id_and_logo_rank_math_set_are_kept(): void {
		$node = ( new BusinessSchema() )->business( $this->rank_math_node(), $this->settings(), 'http://hvac.local/' );

		$this->assertSame( 'http://hvac.local/#organization', $node['@id'] );
		$this->assertSame( array( '@id' => 'http://hvac.local/#logo' ), $node['logo'] );
	}

	public function test_the_logo_also_serves_as_the_business_image(): void {
		$node = ( new BusinessSchema() )->business( $this->rank_math_node(), $this->settings(), 'http://hvac.local/' );

		$this->assertSame( array( '@id' => 'http://hvac.local/#logo' ), $node['image'] );
	}

	public function test_no_logo_means_no_image(): void {
		$rank_math = $this->rank_math_node();
		unset( $rank_math['logo'] );

		$node = ( new BusinessSchema() )->business( $rank_math, $this->settings(), 'http://hvac.local/' );

		$this->assertArrayNotHasKey( 'image', $node );
	}

	public function test_an_image_already_on_the_node_is_kept(): void {
		$rank_math          = $this->rank_math_node();
		$rank_math['image'] = array( '@id' => 'http://hvac.local/#photo' );

		$node = ( new BusinessSchema() )->business( $rank_math, $this->settings(), 'http://hvac.local/' );

		$this->assertSame( array( '@id' => 'http://hvac.local/#photo' ), $node['image'] );
	}

	public function test_it_has_no_address_because_site_settings_has_none(): void {
		$node = ( new BusinessSchema() )->business( $this->rank_math_node(), $this->settings(), 'http://hvac.local/' );

		$this->assertArrayNotHasKey( 'address', $node );
	}

	public function test_same_as_uses_only_filled_in_site_settings_links_and_drops_rank_maths_list(): void {
		$settings = $this->settings(
			array(
				'facebook'        => 'https://facebook.com/bluepeak',
				'google_business' => 'https://g.page/bluepeak',
			)
		);

		$node = ( new BusinessSchema() )->business( $this->rank_math_node(), $settings, 'http://hvac.local/' );

		$this->assertSame( array( 'https://facebook.com/bluepeak', 'https://g.page/bluepeak' ), $node['sameAs'] );
	}

	public function test_no_social_links_means_no_same_as(): void {
		$node = ( new BusinessSchema() )->business( $this->rank_math_node(), $this->settings(), 'http://hvac.local/' );

		$this->assertArrayNotHasKey( 'sameAs', $node );
	}

	public function test_blank_settings_keep_what_rank_math_had(): void {
		$settings = $this->settings(
			array(
				'legal_name'  => '',
				'phone'       => '',
				'email'       => '',
				'area_served' => '',
				'hours'       => '',
			)
		);

		$node = ( new BusinessSchema() )->business( $this->rank_math_node(), $settings, 'http://hvac.local/' );

		$this->assertSame( 'hvac', $node['name'] );
		foreach ( array( 'telephone', 'email', 'areaServed', 'openingHoursSpecification' ) as $key ) {
			$this->assertArrayNotHasKey( $key, $node );
		}
	}

	/**
	 * @dataProvider phone_provider
	 */
	public function test_telephone( string $typed, string $expected ): void {
		$this->assertSame( $expected, BusinessSchema::telephone( $typed ) );
	}

	/**
	 * @return array<string, array{string, string}>
	 */
	public function phone_provider(): array {
		return array(
			'ten digits with punctuation' => array( '(555) 123-4567', '+15551234567' ),
			'eleven digits with a 1'      => array( '1 (555) 123-4567', '+15551234567' ),
			'plus sign already there'     => array( '+1 555 123 4567', '+15551234567' ),
			'eleven digits without a 1'   => array( '(555) 123-45678', '(555) 123-45678' ),
			'too short is left as typed'  => array( '123-4567', '123-4567' ),
			'blank'                       => array( '  ', '' ),
		);
	}

	/**
	 * @dataProvider hours_provider
	 */
	public function test_hours_are_only_marked_up_when_always_open( string $hours, bool $expected ): void {
		$this->assertSame( $expected, array() !== BusinessSchema::opening_hours( $hours ) );
	}

	/**
	 * @return array<string, array{string, bool}>
	 */
	public function hours_provider(): array {
		return array(
			'24/7'            => array( 'Emergency line open 24/7', true ),
			'24 / 7 spaced'   => array( 'Open 24 / 7', true ),
			'24 hours'        => array( 'Open 24 hours a day', true ),
			'24-hour'         => array( '24-hour emergency service', true ),
			'around the clock' => array( 'Around the clock', true ),
			'office hours'    => array( 'Mon to Fri 8am to 5pm', false ),
			'blank'           => array( '', false ),
		);
	}

	public function test_always_open_covers_all_seven_days(): void {
		$spec = BusinessSchema::opening_hours( '24/7' )[0];

		$this->assertSame( 'OpeningHoursSpecification', $spec['@type'] );
		$this->assertCount( 7, $spec['dayOfWeek'] );
		$this->assertSame( '00:00', $spec['opens'] );
		$this->assertSame( '23:59', $spec['closes'] );
	}

	public function test_the_filter_rewrites_only_the_publisher_node(): void {
		$this->saved( array( 'legal_name' => 'Blue Peak Climate' ) );
		$graph = array(
			'publisher' => $this->rank_math_node(),
			'WebSite'   => array( '@type' => 'WebSite' ),
		);

		$result = ( new BusinessSchema() )->filter( $graph );

		$this->assertSame( 'HVACBusiness', $result['publisher']['@type'] );
		$this->assertSame( array( '@type' => 'WebSite' ), $result['WebSite'] );
	}

	public function test_the_filter_leaves_a_graph_without_a_publisher_alone(): void {
		$graph = array( 'WebSite' => array( '@type' => 'WebSite' ) );

		$this->assertSame( $graph, ( new BusinessSchema() )->filter( $graph ) );
		$this->assertSame( 'not an array', ( new BusinessSchema() )->filter( 'not an array' ) );
	}
}
