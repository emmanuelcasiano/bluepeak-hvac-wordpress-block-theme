<?php
/**
 * Tests for Services\Installer.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Services\Catalog;
use BluePeakClimate\Core\Services\Installer;
use BluePeakClimate\Core\Services\PostTypes;

final class InstallerTest extends TestCase {

	/**
	 * Calls recorded by the stubs below.
	 *
	 * @var array<string, array>
	 */
	private $calls;

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		$this->calls = array(
			'insert_term'    => array(),
			'insert_post'    => array(),
			'set_terms'      => array(),
			'next_term_id'   => 100,
			'next_post_id'   => 500,
		);

		Functions\when( 'is_wp_error' )->justReturn( false );
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * Stubs the WordPress functions the installer calls.
	 *
	 * @param string[] $existing_terms Term slugs that already exist.
	 * @param string[] $existing_posts Service slugs that already exist.
	 */
	private function stub_wordpress( array $existing_terms = array(), array $existing_posts = array() ): void {
		$calls = &$this->calls;

		Functions\when( 'term_exists' )->alias(
			static function ( $slug ) use ( $existing_terms ) {
				return in_array( $slug, $existing_terms, true ) ? array( 'term_id' => 7 ) : 0;
			}
		);
		Functions\when( 'wp_insert_term' )->alias(
			static function ( $name, $taxonomy, $args ) use ( &$calls ) {
				$calls['insert_term'][] = array( $taxonomy, $args['slug'], $name );
				return array( 'term_id' => $calls['next_term_id']++ );
			}
		);
		Functions\when( 'get_page_by_path' )->alias(
			static function ( $slug ) use ( $existing_posts ) {
				return in_array( $slug, $existing_posts, true ) ? (object) array( 'ID' => 1 ) : null;
			}
		);
		Functions\when( 'wp_insert_post' )->alias(
			static function ( $args ) use ( &$calls ) {
				$calls['insert_post'][] = $args;
				return $calls['next_post_id']++;
			}
		);
		Functions\when( 'wp_set_object_terms' )->alias(
			static function ( $post_id, $terms, $taxonomy ) use ( &$calls ) {
				$calls['set_terms'][ $post_id ][ $taxonomy ] = $terms;
				return $terms;
			}
		);
	}

	public function test_creates_the_terms_and_all_22_services(): void {
		$this->stub_wordpress();

		$created = ( new Installer() )->install();

		$this->assertSame( 22, $created );
		$this->assertCount( 22, $this->calls['insert_post'] );
		$this->assertCount( 3 + 6, $this->calls['insert_term'], '3 categories and 6 types.' );
	}

	public function test_a_service_is_inserted_with_its_catalog_data_and_menu_order(): void {
		$this->stub_wordpress();
		$first = Catalog::services()[0];

		( new Installer() )->install();

		$post = $this->calls['insert_post'][0];
		$this->assertSame( PostTypes::POST_TYPE, $post['post_type'] );
		$this->assertSame( 'publish', $post['post_status'] );
		$this->assertSame( $first['title'], $post['post_title'] );
		$this->assertSame( $first['slug'], $post['post_name'] );
		$this->assertSame( $first['excerpt'], $post['post_excerpt'] );
		$this->assertSame( 1, $post['menu_order'] );
		$this->assertSame( 22, $this->calls['insert_post'][21]['menu_order'] );
	}

	public function test_each_service_gets_its_category_and_type_term(): void {
		$this->stub_wordpress();

		( new Installer() )->install();

		$terms_of_first = $this->calls['set_terms'][500];
		$this->assertCount( 1, $terms_of_first[ PostTypes::CATEGORY ] );
		$this->assertCount( 1, $terms_of_first[ PostTypes::TYPE ] );
		$this->assertIsInt( $terms_of_first[ PostTypes::CATEGORY ][0] );
	}

	public function test_existing_services_are_skipped_so_edits_are_kept(): void {
		$this->stub_wordpress( array(), array( 'boiler-repair', 'ac-repair' ) );

		$created = ( new Installer() )->install();

		$this->assertSame( 20, $created );
		$slugs = array_column( $this->calls['insert_post'], 'post_name' );
		$this->assertNotContains( 'boiler-repair', $slugs );
		$this->assertNotContains( 'ac-repair', $slugs );
	}

	public function test_existing_terms_are_reused_not_created_again(): void {
		$this->stub_wordpress( array( 'heating', 'cooling', 'electrical', 'repair', 'replace', 'install', 'maintain', 'air-quality' ) );

		( new Installer() )->install();

		$this->assertSame( array(), $this->calls['insert_term'] );
		$this->assertSame( array( 7 ), $this->calls['set_terms'][500][ PostTypes::CATEGORY ] );
	}

	public function test_a_second_run_creates_nothing(): void {
		$all_slugs = array_column( Catalog::services(), 'slug' );
		$this->stub_wordpress( array( 'heating', 'cooling', 'electrical', 'repair', 'replace', 'install', 'maintain', 'air-quality' ), $all_slugs );

		$this->assertSame( 0, ( new Installer() )->install() );
		$this->assertSame( array(), $this->calls['insert_post'] );
	}

	public function test_a_failed_insert_is_skipped_without_breaking_the_run(): void {
		$this->stub_wordpress();
		Functions\when( 'wp_insert_post' )->justReturn( 0 );

		$this->assertSame( 0, ( new Installer() )->install() );
		$this->assertSame( array(), $this->calls['set_terms'] );
	}

	public function test_maybe_upgrade_does_nothing_when_the_version_is_current(): void {
		Functions\when( 'get_option' )->justReturn( Installer::DB_VERSION );
		Functions\expect( 'wp_insert_post' )->never();
		Functions\expect( 'update_option' )->never();

		( new Installer() )->maybe_upgrade();

		$this->addToAssertionCount( 1 );
	}

	public function test_maybe_upgrade_does_nothing_for_users_who_cannot_manage_options(): void {
		Functions\when( 'get_option' )->justReturn( false );
		Functions\when( 'current_user_can' )->justReturn( false );
		Functions\expect( 'wp_insert_post' )->never();
		Functions\expect( 'update_option' )->never();

		( new Installer() )->maybe_upgrade();

		$this->addToAssertionCount( 1 );
	}

	public function test_maybe_upgrade_installs_flushes_rewrites_and_stores_the_version(): void {
		$this->stub_wordpress();
		Functions\when( 'get_option' )->justReturn( false );
		Functions\when( 'current_user_can' )->justReturn( true );
		Functions\expect( 'flush_rewrite_rules' )->once()->with( false );
		Functions\expect( 'update_option' )->once()->with( Installer::VERSION_OPTION, Installer::DB_VERSION );

		( new Installer() )->maybe_upgrade();

		$this->assertCount( 22, $this->calls['insert_post'] );
	}
}
