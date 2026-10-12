<?php
/**
 * Helpers for the gallery filter block.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Gallery;

defined( 'ABSPATH' ) || exit;

/**
 * The gallery page is ordinary blocks: each photo is a Group whose class says its category
 * (`bpch-gallery__item--cooling`). The filter block reads those classes, so the client adds a photo by copying
 * an item and changing its class, and no list of photos is kept anywhere else.
 */
final class Filter {

	/**
	 * Class that marks one photo; the category slug follows the double dash.
	 */
	public const ITEM_CLASS = 'bpch-gallery__item';

	/**
	 * The categories the filter knows, in chip order: slug => label and icon. A slug the photos use that is not
	 * listed here gets no chip (the photo still shows under "All").
	 *
	 * @return array<string, array{label: string, icon: string}>
	 */
	public static function categories(): array {
		return array(
			'cooling' => array(
				'label' => __( 'Cooling', 'blue-peak-climate-core' ),
				'icon'  => 'snowflake',
			),
			'heating' => array(
				'label' => __( 'Heating', 'blue-peak-climate-core' ),
				'icon'  => 'flame',
			),
			'crew'    => array(
				'label' => __( 'Our crew', 'blue-peak-climate-core' ),
				'icon'  => 'users',
			),
			'comfort' => array(
				'label' => __( 'Comfort at home', 'blue-peak-climate-core' ),
				'icon'  => 'sofa',
			),
		);
	}

	/**
	 * The category slug in a class attribute, or '' when there is none.
	 *
	 * @param string $class_attr Value of a class attribute.
	 * @return string
	 */
	public static function category_from_class( string $class_attr ): string {
		if ( preg_match( '/(?:^|\s)' . preg_quote( self::ITEM_CLASS, '/' ) . '--([a-z0-9_-]+)(?:\s|$)/', $class_attr, $match ) ) {
			return $match[1];
		}

		return '';
	}

	/**
	 * How many photos each known category has in a block of HTML, in chip order. Categories with no photo are left out,
	 * so removing the last Heating photo removes the Heating chip.
	 *
	 * @param string $html Rendered inner content.
	 * @return array<string, int> Slug => count.
	 */
	public static function counts( string $html ): array {
		$found = array();

		if ( preg_match_all( '/class="([^"]*\b' . preg_quote( self::ITEM_CLASS, '/' ) . '--[^"]*)"/', $html, $matches ) ) {
			foreach ( $matches[1] as $class_attr ) {
				$slug = self::category_from_class( $class_attr );

				if ( '' !== $slug ) {
					$found[ $slug ] = ( $found[ $slug ] ?? 0 ) + 1;
				}
			}
		}

		$counts = array();

		foreach ( array_keys( self::categories() ) as $slug ) {
			if ( isset( $found[ $slug ] ) ) {
				$counts[ $slug ] = $found[ $slug ];
			}
		}

		return $counts;
	}

	/**
	 * What a screen reader hears after a chip is chosen, by category slug ("all" included).
	 *
	 * @param array<string, int> $counts Result of counts().
	 * @return array<string, string>
	 */
	public static function status_messages( array $counts ): array {
		$total    = array_sum( $counts );
		$messages = array(
			/* translators: %d: number of photos. */
			'all' => sprintf( _n( 'Showing all %d photo', 'Showing all %d photos', $total, 'blue-peak-climate-core' ), $total ),
		);

		foreach ( self::categories() as $slug => $category ) {
			if ( ! isset( $counts[ $slug ] ) ) {
				continue;
			}

			$messages[ $slug ] = sprintf(
				/* translators: 1: number of photos, 2: category name, for example "Cooling". */
				_n( 'Showing %1$d %2$s photo', 'Showing %1$d %2$s photos', $counts[ $slug ], 'blue-peak-climate-core' ),
				$counts[ $slug ],
				$category['label']
			);
		}

		return $messages;
	}
}
