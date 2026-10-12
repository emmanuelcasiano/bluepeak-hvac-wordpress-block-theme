<?php
/**
 * Service schema for single Service pages.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Seo;

use BluePeakClimate\Core\Services\PostTypes;
use BluePeakClimate\Core\Settings\Settings;

defined( 'ABSPATH' ) || exit;

/**
 * Adds one Service node to the graph of every single Service page. The node comes from the service's own fields
 * (title, excerpt, category, type, starting price), and the provider is the business node from BusinessSchema.
 *
 * Rank Math's own schema for services is left off in its settings so there is only one Service node.
 */
final class ServiceSchema {

	/**
	 * Key of the node in Rank Math's graph. Rank Math connects keys that start with "schema-" to the page.
	 */
	public const KEY = 'schema-bpch-service';

	/**
	 * Hooks into Rank Math's graph, before Rank Math connects the nodes (priority 99).
	 */
	public function register(): void {
		add_filter( 'rank_math/json_ld', array( $this, 'filter' ), 20 );
	}

	/**
	 * Adds the Service node on a single Service page.
	 *
	 * @param mixed $data Rank Math's graph, keyed by entity.
	 * @return mixed
	 */
	public function filter( $data ) {
		if ( ! is_array( $data ) || ! is_singular( PostTypes::POST_TYPE ) ) {
			return $data;
		}

		$post = get_post();
		if ( ! is_object( $post ) ) {
			return $data;
		}

		$provider = ! empty( $data['publisher']['@id'] ) ? (string) $data['publisher']['@id'] : home_url( '/#organization' );

		$data[ self::KEY ] = $this->service(
			array(
				'name'     => get_the_title( $post ),
				'excerpt'  => (string) $post->post_excerpt,
				'url'      => get_permalink( $post ),
				'category' => self::first_term_name( $post->ID, PostTypes::CATEGORY ),
				'type'     => self::first_term_name( $post->ID, PostTypes::TYPE ),
				'price'    => get_post_meta( $post->ID, PostTypes::META_PRICE, true ),
			),
			$provider,
			BusinessSchema::area_served( (string) Settings::get( 'area_served' ) )
		);

		return $data;
	}

	/**
	 * Builds the Service node.
	 *
	 * @param array<string, mixed> $fields   name, excerpt, url, category, type and price of the service.
	 * @param string               $provider @id of the business node.
	 * @param array<string, mixed> $area     Area served, from BusinessSchema::area_served().
	 * @return array<string, mixed>
	 */
	public function service( array $fields, string $provider, array $area ): array {
		$node = array(
			'@type'     => 'Service',
			'name'      => (string) ( $fields['name'] ?? '' ),
			'url'       => (string) ( $fields['url'] ?? '' ),
			'provider'  => array( '@id' => $provider ),
			'isPrimary' => true,
		);

		$description = trim( wp_strip_all_tags( (string) ( $fields['excerpt'] ?? '' ) ) );
		if ( '' !== $description ) {
			$node['description'] = $description;
		}

		if ( ! empty( $fields['category'] ) ) {
			$node['category'] = (string) $fields['category'];
		}

		if ( ! empty( $fields['type'] ) ) {
			$node['serviceType'] = (string) $fields['type'];
		}

		if ( array() !== $area ) {
			$node['areaServed'] = $area;
		}

		$price = self::price( $fields['price'] ?? '' );
		if ( null !== $price ) {
			$node['offers'] = array(
				'@type'              => 'Offer',
				'priceSpecification' => array(
					'@type'         => 'PriceSpecification',
					'minPrice'      => $price,
					'priceCurrency' => (string) apply_filters( 'bpch_core_schema_currency', 'USD' ),
				),
			);
		}

		return $node;
	}

	/**
	 * Reads the number out of the starting price. The field is free text ("$89", "89.50"), so a value with no
	 * number in it ("Call for a quote") gives no price.
	 *
	 * @param mixed $value Value of the price_from meta.
	 * @return int|float|null
	 */
	public static function price( $value ) {
		if ( ! is_string( $value ) && ! is_numeric( $value ) ) {
			return null;
		}

		$clean = str_replace( ',', '', (string) $value );
		if ( ! preg_match( '/\d+(?:\.\d+)?/', $clean, $match ) ) {
			return null;
		}

		$number = (float) $match[0];
		if ( $number <= 0 ) {
			return null;
		}

		return floor( $number ) === $number ? (int) $number : $number;
	}

	/**
	 * Name of the first term of a taxonomy on a post.
	 *
	 * @param int    $post_id  Post ID.
	 * @param string $taxonomy Taxonomy name.
	 * @return string Empty when the post has no term.
	 */
	private static function first_term_name( int $post_id, string $taxonomy ): string {
		$terms = get_the_terms( $post_id, $taxonomy );
		if ( ! is_array( $terms ) || array() === $terms ) {
			return '';
		}

		$first = reset( $terms );

		return is_object( $first ) && isset( $first->name ) ? (string) $first->name : '';
	}
}
