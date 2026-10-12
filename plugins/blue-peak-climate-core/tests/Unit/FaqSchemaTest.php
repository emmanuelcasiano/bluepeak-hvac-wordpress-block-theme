<?php
/**
 * Tests for Seo\FaqSchema.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Seo\FaqSchema;

final class FaqSchemaTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		Functions\stubTranslationFunctions();
		Functions\when( 'wp_strip_all_tags' )->alias( 'strip_tags' );
		// Stand-in for wp_kses: keeps only the tags the real list allows, and drops attributes except href.
		Functions\when( 'wp_kses' )->alias(
			static function ( $html, $allowed ) {
				$tags = '<' . implode( '><', array_keys( $allowed ) ) . '>';

				return preg_replace( '/\s(?!href)[a-z-]+="[^"]*"/i', '', strip_tags( $html, $tags ) );
			}
		);
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * What the core Details block prints for one question.
	 *
	 * @param string $question Summary text.
	 * @param string $answer   Inner HTML.
	 * @param string $open     Extra attribute on the details tag.
	 */
	private function block( string $question, string $answer, string $open = '' ): string {
		return '<details class="wp-block-details" name="bpch-faq"' . $open . '><summary>' . $question . '</summary>' . "\n" . $answer . "\n" . '</details>';
	}

	public function test_registers_the_collector_and_the_graph_filter(): void {
		$schema = new FaqSchema();
		$schema->register();

		$this->assertSame( 10, has_filter( 'render_block_core/details', array( $schema, 'collect' ) ) );
		$this->assertSame( 20, has_filter( 'rank_math/json_ld', array( $schema, 'filter' ) ) );
	}

	public function test_parse_reads_the_question_and_the_answer(): void {
		$pair = FaqSchema::parse( $this->block( 'How fast can you come?', '<p class="x">Within the hour for emergencies.</p>', ' open' ) );

		$this->assertSame( 'How fast can you come?', $pair['question'] );
		$this->assertSame( '<p>Within the hour for emergencies.</p>', $pair['answer'] );
	}

	public function test_parse_keeps_links_lists_and_line_breaks_in_the_answer(): void {
		$pair = FaqSchema::parse( $this->block( 'Q?', '<p>Call <a href="tel:5551234567" class="c">us</a>.</p><ul><li>One</li><li>Two</li></ul>' ) );

		$this->assertSame( '<p>Call <a href="tel:5551234567">us</a>.</p><ul><li>One</li><li>Two</li></ul>', $pair['answer'] );
	}

	public function test_parse_removes_tags_google_does_not_allow(): void {
		$pair = FaqSchema::parse( $this->block( 'Q?', '<p>Text</p><script>alert(1)</script><img src="x.jpg">' ) );

		$this->assertStringNotContainsString( '<img', $pair['answer'] );
		$this->assertStringNotContainsString( '<script', $pair['answer'] );
		$this->assertStringContainsString( '<p>Text</p>', $pair['answer'] );
	}

	public function test_parse_strips_markup_and_extra_spaces_from_the_question(): void {
		$pair = FaqSchema::parse( $this->block( "  Do you   <strong>repair</strong>\n boilers?  ", '<p>Yes.</p>' ) );

		$this->assertSame( 'Do you repair boilers?', $pair['question'] );
	}

	public function test_parse_turns_apostrophe_entities_into_apostrophes_but_keeps_other_entities(): void {
		$pair = FaqSchema::parse( $this->block( 'What&#039;s included?', '<p>We&#039;ll show you both prices &amp; you decide.</p>' ) );

		$this->assertSame( "What's included?", $pair['question'] );
		$this->assertSame( "<p>We'll show you both prices &amp; you decide.</p>", $pair['answer'] );
	}

	public function test_parse_skips_a_block_with_no_answer_or_no_question(): void {
		$this->assertNull( FaqSchema::parse( $this->block( 'Question only?', '<p> </p>' ) ) );
		$this->assertNull( FaqSchema::parse( $this->block( '  ', '<p>Answer only.</p>' ) ) );
		$this->assertNull( FaqSchema::parse( '<div>Not a details block</div>' ) );
	}

	public function test_collect_returns_the_block_html_unchanged(): void {
		$html = $this->block( 'Q?', '<p>A.</p>' );

		$this->assertSame( $html, ( new FaqSchema() )->collect( $html ) );
		$this->assertSame( 42, ( new FaqSchema() )->collect( 42 ) );
	}

	public function test_the_filter_builds_a_faq_page_from_the_blocks_in_page_order(): void {
		Functions\when( 'is_singular' )->justReturn( true );
		$schema = new FaqSchema();
		$schema->collect( $this->block( 'First?', '<p>One.</p>' ) );
		$schema->collect( $this->block( 'Second?', '<p>Two.</p>' ) );

		$result = $schema->filter( array( 'WebSite' => array( '@type' => 'WebSite' ) ) );

		$node = $result[ FaqSchema::KEY ];
		$this->assertSame( 'FAQPage', $node['@type'] );
		$this->assertCount( 2, $node['mainEntity'] );
		$this->assertSame( 'Question', $node['mainEntity'][0]['@type'] );
		$this->assertSame( 'First?', $node['mainEntity'][0]['name'] );
		$this->assertSame( 'Answer', $node['mainEntity'][0]['acceptedAnswer']['@type'] );
		$this->assertSame( '<p>One.</p>', $node['mainEntity'][0]['acceptedAnswer']['text'] );
		$this->assertSame( 'Second?', $node['mainEntity'][1]['name'] );
		$this->assertArrayHasKey( 'WebSite', $result );
	}

	public function test_the_same_question_rendered_twice_counts_once(): void {
		Functions\when( 'is_singular' )->justReturn( true );
		$schema = new FaqSchema();
		$schema->collect( $this->block( 'Same?', '<p>A.</p>' ) );
		$schema->collect( $this->block( 'Same?', '<p>A.</p>' ) );

		$result = $schema->filter( array() );

		$this->assertCount( 1, $result[ FaqSchema::KEY ]['mainEntity'] );
	}

	public function test_no_questions_means_no_node(): void {
		Functions\when( 'is_singular' )->justReturn( true );

		$this->assertArrayNotHasKey( FaqSchema::KEY, ( new FaqSchema() )->filter( array() ) );
	}

	public function test_no_node_outside_a_single_page(): void {
		Functions\when( 'is_singular' )->justReturn( false );
		$schema = new FaqSchema();
		$schema->collect( $this->block( 'Q?', '<p>A.</p>' ) );

		$this->assertArrayNotHasKey( FaqSchema::KEY, $schema->filter( array() ) );
		$this->assertSame( 'not an array', $schema->filter( 'not an array' ) );
	}
}
