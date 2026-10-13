<?php
/**
 * Tests for Services\TermLinks.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Services\PostTypes;
use BluePeakClimate\Core\Services\TermLinks;

final class TermLinksTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	public function test_it_hooks_the_term_link_filter(): void {
		$links = new TermLinks();
		$links->register();

		$this->assertNotFalse( has_filter( 'term_link', array( $links, 'category_link' ) ) );
	}

	public function test_a_service_category_links_to_its_section_of_the_services_page(): void {
		Functions\when( 'get_post_type_archive_link' )->justReturn( 'http://example.test/services/' );

		$link = ( new TermLinks() )->category_link(
			'http://example.test/services/category/heating/',
			(object) array( 'slug' => 'heating' ),
			PostTypes::CATEGORY
		);

		$this->assertSame( 'http://example.test/services/#heating', $link );
	}

	public function test_other_taxonomies_keep_the_link_wordpress_built(): void {
		Functions\expect( 'get_post_type_archive_link' )->never();

		$link = ( new TermLinks() )->category_link(
			'http://example.test/category/seasonal-tips/',
			(object) array( 'slug' => 'seasonal-tips' ),
			'category'
		);

		$this->assertSame( 'http://example.test/category/seasonal-tips/', $link );
	}

	public function test_the_original_link_stays_when_the_services_page_is_missing(): void {
		Functions\when( 'get_post_type_archive_link' )->justReturn( false );

		$link = ( new TermLinks() )->category_link(
			'http://example.test/services/category/heating/',
			(object) array( 'slug' => 'heating' ),
			PostTypes::CATEGORY
		);

		$this->assertSame( 'http://example.test/services/category/heating/', $link );
	}

	public function test_a_term_without_a_slug_keeps_the_original_link(): void {
		Functions\when( 'get_post_type_archive_link' )->justReturn( 'http://example.test/services/' );

		$link = ( new TermLinks() )->category_link(
			'http://example.test/services/category/x/',
			(object) array( 'slug' => '' ),
			PostTypes::CATEGORY
		);

		$this->assertSame( 'http://example.test/services/category/x/', $link );
	}
}
