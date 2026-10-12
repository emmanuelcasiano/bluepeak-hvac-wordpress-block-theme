<?php
/**
 * FAQPage schema read from the Details blocks the visitor sees.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Seo;

defined( 'ABSPATH' ) || exit;

/**
 * The FAQ is built from core Details blocks, in a pattern on Home and in a template on every Service page. Typing the
 * same questions again for search engines would drift out of date, so this class listens while WordPress renders the
 * blocks and keeps each question and answer. A block theme renders the page body before it prints the head, so by the
 * time Rank Math builds its JSON-LD the list is complete, and an edit in the editor changes the markup too.
 *
 * Rank Math merges a FAQPage node into the page's WebPage node, which is the form Google reads.
 */
final class FaqSchema {

	/**
	 * Key of the node in Rank Math's graph. Rank Math connects keys that start with "schema-" to the page.
	 */
	public const KEY = 'schema-bpch-faq';

	/**
	 * HTML allowed in an answer. This is the list Google accepts in FAQ answers.
	 */
	private const ANSWER_TAGS = array(
		'h1'     => array(),
		'h2'     => array(),
		'h3'     => array(),
		'h4'     => array(),
		'h5'     => array(),
		'h6'     => array(),
		'br'     => array(),
		'ol'     => array(),
		'ul'     => array(),
		'li'     => array(),
		'a'      => array( 'href' => true ),
		'p'      => array(),
		'div'    => array(),
		'b'      => array(),
		'strong' => array(),
		'i'      => array(),
		'em'     => array(),
	);

	/**
	 * Questions and answers collected while the page rendered, keyed by question so a repeated block counts once.
	 *
	 * @var array<string, string>
	 */
	private array $items = array();

	/**
	 * Hooks the collector and the graph filter.
	 */
	public function register(): void {
		add_filter( 'render_block_core/details', array( $this, 'collect' ), 10, 1 );
		add_filter( 'rank_math/json_ld', array( $this, 'filter' ), 20 );
	}

	/**
	 * Remembers the question and answer of one rendered Details block. The block's HTML is returned unchanged.
	 *
	 * @param mixed $block_content Rendered HTML of the block.
	 * @return mixed
	 */
	public function collect( $block_content ) {
		if ( ! is_string( $block_content ) ) {
			return $block_content;
		}

		$pair = self::parse( $block_content );
		if ( null !== $pair && ! isset( $this->items[ $pair['question'] ] ) ) {
			$this->items[ $pair['question'] ] = $pair['answer'];
		}

		return $block_content;
	}

	/**
	 * Adds the FAQPage node when the page showed at least one question.
	 *
	 * @param mixed $data Rank Math's graph, keyed by entity.
	 * @return mixed
	 */
	public function filter( $data ) {
		if ( ! is_array( $data ) || ! is_singular() || array() === $this->items ) {
			return $data;
		}

		$data[ self::KEY ] = self::page( $this->items );

		return $data;
	}

	/**
	 * Builds the FAQPage node.
	 *
	 * @param array<string, string> $items Answers keyed by question.
	 * @return array<string, mixed>
	 */
	public static function page( array $items ): array {
		$questions = array();
		foreach ( $items as $question => $answer ) {
			$questions[] = array(
				'@type'          => 'Question',
				'name'           => (string) $question,
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => $answer,
				),
			);
		}

		return array(
			'@type'      => 'FAQPage',
			'mainEntity' => $questions,
		);
	}

	/**
	 * Reads the question (the summary) and the answer (everything after it) from a Details block.
	 *
	 * @param string $html Rendered Details block.
	 * @return array{question: string, answer: string}|null Null when the block has no question or no answer.
	 */
	public static function parse( string $html ): ?array {
		if ( ! preg_match( '#<summary[^>]*>(.*?)</summary>(.*)</details>#is', $html, $match ) ) {
			return null;
		}

		// The question is plain text, so entities such as &#039; become the character itself.
		$question = html_entity_decode( wp_strip_all_tags( $match[1] ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		$question = trim( (string) preg_replace( '/\s+/', ' ', $question ) );

		$answer = wp_kses( $match[2], self::ANSWER_TAGS );
		$answer = trim( (string) preg_replace( '/\s+/', ' ', $answer ) );
		$answer = (string) preg_replace( '/>\s+</', '><', $answer );
		// An apostrophe needs no escaping in HTML text. The other entities (&lt;, &amp;) must stay.
		$answer = str_replace( array( '&#039;', '&#39;', '&apos;' ), "'", $answer );

		if ( '' === $question || '' === trim( wp_strip_all_tags( $answer ) ) ) {
			return null;
		}

		return array(
			'question' => $question,
			'answer'   => $answer,
		);
	}
}
