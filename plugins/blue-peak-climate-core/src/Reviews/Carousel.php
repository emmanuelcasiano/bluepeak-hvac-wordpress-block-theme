<?php
/**
 * The reviews shown in the Home review carousel.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Reviews;

defined( 'ABSPATH' ) || exit;

/**
 * Reads the published `bpch_review` posts the same way the Reviews page does, so the two never disagree. Only the
 * newest few are shown: a carousel of twenty slides is a slow way to read.
 */
final class Carousel {

	/**
	 * Most reviews the carousel shows.
	 */
	public const LIMIT = 6;

	/**
	 * Published reviews that have text, in the order set on the Review screen (Order), then newest first.
	 *
	 * @param int $limit How many to return at most.
	 * @return array<int, array{quote: string, reviewer: string, service: string, rating: int}>
	 */
	public static function slides( int $limit = self::LIMIT ): array {
		$posts = get_posts(
			array(
				'post_type'        => PostTypes::POST_TYPE,
				'post_status'      => 'publish',
				'posts_per_page'   => max( 1, $limit ),
				'orderby'          => array(
					'menu_order' => 'ASC',
					'date'       => 'DESC',
				),
				'no_found_rows'    => true,
				'suppress_filters' => false,
			)
		);

		$slides = array();

		foreach ( $posts as $post ) {
			$quote = trim( wp_strip_all_tags( (string) $post->post_excerpt ) );

			// A review with no text has nothing to show.
			if ( '' === $quote ) {
				continue;
			}

			$slides[] = array(
				'quote'    => $quote,
				'reviewer' => trim( (string) get_post_meta( $post->ID, PostTypes::META_REVIEWER, true ) ),
				'service'  => trim( (string) get_post_meta( $post->ID, PostTypes::META_SERVICE, true ) ),
				'rating'   => Rating::clamp( get_post_meta( $post->ID, PostTypes::META_RATING, true ) ),
			);
		}

		return $slides;
	}
}
