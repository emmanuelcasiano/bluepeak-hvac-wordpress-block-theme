<?php
/**
 * Article schema: the business is the author of blog posts.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Seo;

defined( 'ABSPATH' ) || exit;

/**
 * Rank Math names the WordPress user as the author of a blog post and adds a Person node for them. This site shows no
 * bylines, and the posts are the company's advice, so the author is the business. That also avoids publishing a login
 * name such as "admin" as a person, and avoids a nameless Person when a post has no user.
 *
 * Return false from the `bpch_core_schema_business_is_author` filter to go back to named authors.
 */
final class ArticleSchema {

	/**
	 * Article types Rank Math uses for posts.
	 */
	private const TYPES = array( 'Article', 'BlogPosting', 'NewsArticle' );

	/**
	 * Hooks into Rank Math's graph. Priority 100 runs after Rank Math connects its nodes (priority 99).
	 */
	public function register(): void {
		add_filter( 'rank_math/json_ld', array( $this, 'filter' ), 100 );
	}

	/**
	 * Points the article at the business and drops the author Person node.
	 *
	 * @param mixed $data Rank Math's graph, keyed by entity.
	 * @return mixed
	 */
	public function filter( $data ) {
		if ( ! is_array( $data ) || empty( $data['richSnippet']['@type'] ) || empty( $data['publisher']['@id'] ) ) {
			return $data;
		}

		if ( array() === array_intersect( (array) $data['richSnippet']['@type'], self::TYPES ) ) {
			return $data;
		}

		if ( ! apply_filters( 'bpch_core_schema_business_is_author', true ) ) {
			return $data;
		}

		$data['richSnippet']['author'] = array( '@id' => (string) $data['publisher']['@id'] );
		unset( $data['ProfilePage'] );

		return $data;
	}
}
