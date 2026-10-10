<?php
/**
 * Tests for Services\CategoryBindings.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Services\CategoryBindings;
use BluePeakClimate\Core\Services\PostTypes;

final class CategoryBindingsTest extends TestCase {

	/**
	 * @var CategoryBindings
	 */
	private $bindings;

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		Functions\stubTranslationFunctions();
		Functions\stubEscapeFunctions();
		Functions\when( 'is_wp_error' )->justReturn( false );
		Functions\when( 'esc_url_raw' )->returnArg();
		Functions\when( '_n' )->alias(
			static function ( $single, $plural, $number ) {
				return 1 === $number ? $single : $plural;
			}
		);
		Functions\when( 'get_post_type_archive_link' )->justReturn( 'http://example.test/services/' );
		Functions\when( 'get_term_by' )->alias(
			static function ( $field, $value, $taxonomy ) {
				if ( 'slug' !== $field || PostTypes::CATEGORY !== $taxonomy ) {
					return false;
				}
				$terms = array(
					'heating' => (object) array(
						'name'  => 'Heating & Air',
						'count' => 10,
					),
					'single'  => (object) array(
						'name'  => 'Single',
						'count' => 1,
					),
				);
				return $terms[ $value ] ?? false;
			}
		);
		$this->bindings = new CategoryBindings();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	public function test_count_label_is_the_live_count_with_its_unit(): void {
		$this->assertSame( '10 services', $this->bindings->get_value( array( 'slug' => 'heating', 'key' => 'count_label' ), null, 'content' ) );
		$this->assertSame( '1 service', $this->bindings->get_value( array( 'slug' => 'single', 'key' => 'count_label' ), null, 'content' ) );
	}

	public function test_count_is_a_plain_number(): void {
		$this->assertSame( '10', $this->bindings->get_value( array( 'slug' => 'heating', 'key' => 'count' ), null, 'content' ) );
	}

	public function test_url_points_at_the_category_section_of_the_services_page(): void {
		$this->assertSame( 'http://example.test/services/#heating', $this->bindings->get_value( array( 'slug' => 'heating', 'key' => 'url' ), null, 'url' ) );
	}

	public function test_url_is_null_when_the_services_page_has_no_link(): void {
		Functions\when( 'get_post_type_archive_link' )->justReturn( false );

		$this->assertNull( $this->bindings->get_value( array( 'slug' => 'heating', 'key' => 'url' ), null, 'url' ) );
	}

	public function test_name_is_escaped_for_html_attributes_only(): void {
		$this->assertSame( 'Heating &amp; Air', $this->bindings->get_value( array( 'slug' => 'heating', 'key' => 'name' ), null, 'content' ) );
		$this->assertSame( 'Heating & Air', $this->bindings->get_value( array( 'slug' => 'heating', 'key' => 'name' ), null, 'alt' ) );
	}

	public function test_unknown_category_unknown_key_or_missing_args_keep_the_saved_content(): void {
		$this->assertNull( $this->bindings->get_value( array( 'slug' => 'nope', 'key' => 'count' ), null, 'content' ) );
		$this->assertNull( $this->bindings->get_value( array( 'slug' => 'heating', 'key' => 'nope' ), null, 'content' ) );
		$this->assertNull( $this->bindings->get_value( array( 'key' => 'count' ), null, 'content' ) );
		$this->assertNull( $this->bindings->get_value( array( 'slug' => 'heating' ), null, 'content' ) );
	}
}
