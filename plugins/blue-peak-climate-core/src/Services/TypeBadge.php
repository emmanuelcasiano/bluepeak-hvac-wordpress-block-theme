<?php
/**
 * The "Service type" block: a round icon and the type name (Repair, Replace...) for the service in a Query Loop card.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Services;

use BluePeakClimate\Core\Support\Icons;

defined( 'ABSPATH' ) || exit;

/**
 * The core Post Terms block can only print the name, and a Query Loop card is one block tree for every service, so
 * it can't pick a different icon for each type. This PHP-only block (WordPress 7.0+, no JavaScript build) reads the
 * type of the card's service and draws the matching icon next to the name. The icon is decoration: the name carries
 * the meaning. A service with no type, or a type with no icon, still prints its name.
 */
final class TypeBadge {

	/**
	 * Icon for each type slug (see Support\Icons).
	 *
	 * @var array<string, string>
	 */
	private const ICONS = array(
		'repair'      => 'wrench',
		'replace'     => 'refresh-cw',
		'install'     => 'package-plus',
		'maintain'    => 'calendar-check',
		'air-quality' => 'wind',
		'electrical'  => 'zap',
	);

	/**
	 * Hooks the block registration.
	 */
	public function register(): void {
		add_action( 'init', array( $this, 'register_block' ) );
	}

	/**
	 * The icon name for a type slug, or '' when the type has none.
	 *
	 * @param string $slug Type slug, for example "air-quality".
	 * @return string
	 */
	public static function icon_for( string $slug ): string {
		return self::ICONS[ $slug ] ?? '';
	}

	/**
	 * Registers the block.
	 */
	public function register_block(): void {
		register_block_type(
			'bpch/service-type',
			array(
				'title'           => __( 'Service type', 'blue-peak-climate-core' ),
				'description'     => __( 'The type of the service in this card (Repair, Replace...) with its icon.', 'blue-peak-climate-core' ),
				'category'        => 'design',
				'icon'            => 'tag',
				'uses_context'    => array( 'postId' ),
				'supports'        => array(
					'autoRegister' => true,
					'html'         => false,
					'className'    => false,
				),
				'render_callback' => array( $this, 'render' ),
			)
		);
	}

	/**
	 * Prints the icon and name of the current service's type.
	 *
	 * @param array                 $attributes Block attributes (none).
	 * @param string                $content    Inner content (none).
	 * @param \WP_Block|object|null $block      Block instance; its context holds the service's postId.
	 * @return string
	 */
	public function render( $attributes = array(), $content = '', $block = null ): string {
		unset( $attributes, $content );

		$post_id = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : 0;

		if ( $post_id < 1 ) {
			return '';
		}

		$terms = get_the_terms( $post_id, PostTypes::TYPE );

		if ( ! is_array( $terms ) || array() === $terms ) {
			return '';
		}

		$term = reset( $terms );
		$icon = Icons::svg( self::icon_for( (string) $term->slug ) );

		return sprintf(
			'<p %1$s>%2$s<span class="bpch-type__label">%3$s</span></p>',
			get_block_wrapper_attributes( array( 'class' => 'bpch-type' ) ),
			'' === $icon ? '' : '<span class="bpch-type__icon" aria-hidden="true">' . $icon . '</span>',
			esc_html( $term->name )
		);
	}
}
