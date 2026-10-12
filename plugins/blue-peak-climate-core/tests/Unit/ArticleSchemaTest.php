<?php
/**
 * Tests for Seo\ArticleSchema.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Filters;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Seo\ArticleSchema;

final class ArticleSchemaTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * A post's graph as Rank Math builds it.
	 *
	 * @param string|string[] $type Type of the article node.
	 * @return array<string, mixed>
	 */
	private function graph( $type = 'BlogPosting' ): array {
		return array(
			'publisher'   => array( '@id' => 'http://hvac.local/#organization' ),
			'ProfilePage' => array(
				'@type' => 'Person',
				'@id'   => 'http://hvac.local/post/#author',
			),
			'richSnippet' => array(
				'@type'  => $type,
				'author' => array( '@id' => 'http://hvac.local/post/#author' ),
			),
		);
	}

	public function test_registers_after_rank_math_connects_its_nodes(): void {
		$schema = new ArticleSchema();
		$schema->register();

		$this->assertSame( 100, has_filter( 'rank_math/json_ld', array( $schema, 'filter' ) ) );
	}

	public function test_the_business_becomes_the_author_and_the_person_node_goes(): void {
		$result = ( new ArticleSchema() )->filter( $this->graph() );

		$this->assertSame( array( '@id' => 'http://hvac.local/#organization' ), $result['richSnippet']['author'] );
		$this->assertArrayNotHasKey( 'ProfilePage', $result );
		$this->assertArrayHasKey( 'publisher', $result );
	}

	public function test_it_works_for_every_article_type_including_a_list_of_types(): void {
		foreach ( array( 'Article', 'NewsArticle', array( 'BlogPosting', 'Thing' ) ) as $type ) {
			$result = ( new ArticleSchema() )->filter( $this->graph( $type ) );

			$this->assertSame( '#organization', substr( $result['richSnippet']['author']['@id'], -13 ) );
		}
	}

	public function test_other_schema_types_are_left_alone(): void {
		$graph = $this->graph( 'Service' );

		$this->assertSame( $graph, ( new ArticleSchema() )->filter( $graph ) );
	}

	public function test_a_page_with_no_article_is_left_alone(): void {
		$graph = array( 'publisher' => array( '@id' => 'http://hvac.local/#organization' ) );

		$this->assertSame( $graph, ( new ArticleSchema() )->filter( $graph ) );
		$this->assertSame( 'not an array', ( new ArticleSchema() )->filter( 'not an array' ) );
	}

	public function test_without_a_publisher_node_nothing_changes(): void {
		$graph = $this->graph();
		unset( $graph['publisher'] );

		$this->assertSame( $graph, ( new ArticleSchema() )->filter( $graph ) );
	}

	public function test_the_filter_can_bring_back_named_authors(): void {
		Filters\expectApplied( 'bpch_core_schema_business_is_author' )->once()->with( true )->andReturn( false );
		$graph = $this->graph();

		$this->assertSame( $graph, ( new ArticleSchema() )->filter( $graph ) );
	}
}
