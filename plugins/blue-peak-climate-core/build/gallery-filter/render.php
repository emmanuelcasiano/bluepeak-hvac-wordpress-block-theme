<?php
/**
 * Front-end markup for the bpch/gallery-filter block.
 *
 * Adds a row of filter chips above the photos and puts the filter directives on each photo. Without JavaScript the
 * chips are hidden by CSS (`scripting: none`) and every photo shows.
 *
 * @var array    $attributes Block attributes (none).
 * @var string   $content    Rendered inner blocks: the photo grid.
 * @var WP_Block $block      Block instance.
 *
 * @package BluePeakClimate\Core
 */

defined( 'ABSPATH' ) || exit;

use BluePeakClimate\Core\Gallery\Filter;
use BluePeakClimate\Core\Support\Icons;

$bpch_counts = Filter::counts( $content );

// No photo has a category class: nothing to filter, so show the photos as they are.
if ( array() === $bpch_counts ) {
	echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Inner blocks, already escaped.
	return;
}

$bpch_categories = Filter::categories();

wp_interactivity_state(
	'bpch/gallery-filter',
	array(
		'isChipActive' => static function (): bool {
			$context = wp_interactivity_get_context( 'bpch/gallery-filter' );

			return $context['category'] === $context['active'];
		},
		'isItemHidden' => static function (): bool {
			$context = wp_interactivity_get_context( 'bpch/gallery-filter' );

			return 'all' !== $context['active'] && $context['category'] !== $context['active'];
		},
		'status'       => static function (): string {
			$context = wp_interactivity_get_context( 'bpch/gallery-filter' );

			return (string) ( $context['status'][ $context['active'] ] ?? '' );
		},
	)
);

// Each photo gets its category in its own context and is hidden when another chip is chosen.
$bpch_processor = new WP_HTML_Tag_Processor( $content );

while ( $bpch_processor->next_tag( array( 'class_name' => Filter::ITEM_CLASS ) ) ) {
	$bpch_processor->set_attribute(
		'data-wp-context',
		wp_json_encode( array( 'category' => Filter::category_from_class( (string) $bpch_processor->get_attribute( 'class' ) ) ) )
	);
	$bpch_processor->set_attribute( 'data-wp-bind--hidden', 'state.isItemHidden' );
}

$bpch_photos = $bpch_processor->get_updated_html();

$bpch_chips = array(
	'all' => array(
		'label' => __( 'All', 'blue-peak-climate-core' ),
		'icon'  => 'layout-grid',
	),
);

foreach ( array_keys( $bpch_counts ) as $bpch_slug ) {
	$bpch_chips[ $bpch_slug ] = $bpch_categories[ $bpch_slug ];
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'bpch-filter' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by core. ?>
	data-wp-interactive="bpch/gallery-filter"
	<?php
	echo wp_interactivity_data_wp_context( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by core.
		array(
			'active' => 'all',
			'status' => Filter::status_messages( $bpch_counts ),
		)
	);
	?>
>
	<div class="bpch-filter__chips" role="group" aria-label="<?php esc_attr_e( 'Filter photos by category', 'blue-peak-climate-core' ); ?>">
		<?php foreach ( $bpch_chips as $bpch_slug => $bpch_chip ) : ?>
			<button
				type="button"
				class="bpch-filter__chip"
				aria-pressed="<?php echo 'all' === $bpch_slug ? 'true' : 'false'; ?>"
				data-wp-bind--aria-pressed="state.isChipActive"
				data-wp-on--click="actions.select"
				<?php echo wp_interactivity_data_wp_context( array( 'category' => $bpch_slug ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by core. ?>
			>
				<?php echo Icons::svg( $bpch_chip['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Constant markup. ?>
				<span><?php echo esc_html( $bpch_chip['label'] ); ?></span>
			</button>
		<?php endforeach; ?>
	</div>

	<p class="bpch-filter__status" role="status" data-wp-text="state.status"><?php echo esc_html( Filter::status_messages( $bpch_counts )['all'] ); ?></p>

	<?php echo $bpch_photos; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Inner blocks, already escaped. ?>
</div>
