<?php
/**
 * Front-end markup for the bpch/review-carousel block.
 *
 * The first review is already the visible one in the HTML, so the page looks right before any JavaScript runs. With
 * no JavaScript the controls are hidden by CSS (`scripting: none`) and the first review stays.
 *
 * @var array    $attributes Block attributes (none).
 * @var string   $content    Inner content (none).
 * @var WP_Block $block      Block instance.
 *
 * @package BluePeakClimate\Core
 */

defined( 'ABSPATH' ) || exit;

use BluePeakClimate\Core\Reviews\Carousel;
use BluePeakClimate\Core\Reviews\Rating;
use BluePeakClimate\Core\Support\Icons;

$bpch_slides = Carousel::slides();
$bpch_count  = count( $bpch_slides );

if ( 0 === $bpch_count ) {
	return;
}

wp_interactivity_state(
	'bpch/review-carousel',
	array(
		'isSlideHidden'  => static function (): bool {
			$context = wp_interactivity_get_context( 'bpch/review-carousel' );

			return $context['index'] !== $context['current'];
		},
		'isCurrent'      => static function (): bool {
			$context = wp_interactivity_get_context( 'bpch/review-carousel' );

			return $context['index'] === $context['current'];
		},
		'trackTransform' => static function (): string {
			$context = wp_interactivity_get_context( 'bpch/review-carousel' );

			return 'translateX(-' . ( (int) $context['current'] * 100 ) . '%)';
		},
		'liveMode'       => static function (): string {
			$context = wp_interactivity_get_context( 'bpch/review-carousel' );

			return $context['playing'] && ! $context['hovering'] && ! $context['focused'] ? 'off' : 'polite';
		},
		'playLabel'      => static function (): string {
			$context = wp_interactivity_get_context( 'bpch/review-carousel' );

			return $context['playing'] ? $context['pauseLabel'] : $context['playLabel'];
		},
	)
);

$bpch_context = array(
	'current'    => 0,
	'count'      => $bpch_count,
	'playing'    => true,
	'hovering'   => false,
	'focused'    => false,
	'pauseLabel' => __( 'Pause automatic rotation', 'blue-peak-climate-core' ),
	'playLabel'  => __( 'Start automatic rotation', 'blue-peak-climate-core' ),
);
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'bpch-carousel' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by core. ?>
	role="region"
	aria-roledescription="<?php esc_attr_e( 'carousel', 'blue-peak-climate-core' ); ?>"
	aria-label="<?php esc_attr_e( 'Customer reviews', 'blue-peak-climate-core' ); ?>"
	data-wp-interactive="bpch/review-carousel"
	<?php echo wp_interactivity_data_wp_context( $bpch_context ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by core. ?>
	data-wp-init="callbacks.init"
	data-wp-watch="callbacks.autoplay"
	data-wp-on--mouseenter="actions.hoverStart"
	data-wp-on--mouseleave="actions.hoverEnd"
	data-wp-on--focusin="actions.focusStart"
	data-wp-on--focusout="actions.focusEnd"
>
	<div class="bpch-carousel__viewport" data-wp-bind--aria-live="state.liveMode">
		<div class="bpch-carousel__track" data-wp-style--transform="state.trackTransform">
			<?php foreach ( $bpch_slides as $bpch_index => $bpch_slide ) : ?>
				<div
					class="bpch-carousel__slide"
					role="group"
					aria-roledescription="<?php esc_attr_e( 'slide', 'blue-peak-climate-core' ); ?>"
					aria-label="<?php echo esc_attr( sprintf( /* translators: 1: slide number, 2: number of slides. */ __( '%1$d of %2$d', 'blue-peak-climate-core' ), $bpch_index + 1, $bpch_count ) ); ?>"
					data-wp-bind--aria-hidden="state.isSlideHidden"
					data-wp-bind--inert="state.isSlideHidden"
					<?php echo wp_interactivity_data_wp_context( array( 'index' => $bpch_index ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by core. ?>
				>
					<p class="bpch-carousel__stars">
						<span aria-hidden="true"><?php echo esc_html( Rating::stars( $bpch_slide['rating'] ) ); ?></span>
						<span class="bpch-carousel__sr"><?php echo esc_html( Rating::label( $bpch_slide['rating'] ) ); ?></span>
					</p>
					<blockquote class="bpch-carousel__quote">
						<p><?php echo esc_html( $bpch_slide['quote'] ); ?></p>
					</blockquote>
					<?php if ( '' !== $bpch_slide['reviewer'] || '' !== $bpch_slide['service'] ) : ?>
						<p class="bpch-carousel__person">
							<?php if ( '' !== $bpch_slide['reviewer'] ) : ?>
								<span class="bpch-carousel__name"><?php echo esc_html( $bpch_slide['reviewer'] ); ?></span>
							<?php endif; ?>
							<?php if ( '' !== $bpch_slide['service'] ) : ?>
								<span class="bpch-carousel__service"><?php echo esc_html( $bpch_slide['service'] ); ?></span>
							<?php endif; ?>
						</p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<?php if ( $bpch_count > 1 ) : ?>
		<div class="bpch-carousel__controls">
			<div class="bpch-carousel__dots">
				<?php for ( $bpch_index = 0; $bpch_index < $bpch_count; $bpch_index++ ) : ?>
					<button
						type="button"
						class="bpch-carousel__dot"
						aria-label="<?php echo esc_attr( sprintf( /* translators: %d: review number. */ __( 'Show review %d', 'blue-peak-climate-core' ), $bpch_index + 1 ) ); ?>"
						data-wp-bind--aria-current="state.isCurrent"
						data-wp-on--click="actions.goTo"
						<?php echo wp_interactivity_data_wp_context( array( 'index' => $bpch_index ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by core. ?>
					></button>
				<?php endfor; ?>
			</div>

			<div class="bpch-carousel__buttons">
				<button type="button" class="bpch-carousel__button" aria-label="<?php esc_attr_e( 'Pause automatic rotation', 'blue-peak-climate-core' ); ?>" data-wp-bind--aria-label="state.playLabel" data-wp-on--click="actions.togglePlay">
					<span data-wp-bind--hidden="!context.playing"><?php echo Icons::svg( 'pause' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Constant markup. ?></span>
					<span data-wp-bind--hidden="context.playing"><?php echo Icons::svg( 'play' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Constant markup. ?></span>
				</button>
				<button type="button" class="bpch-carousel__button" aria-label="<?php esc_attr_e( 'Previous review', 'blue-peak-climate-core' ); ?>" data-wp-on--click="actions.previous">
					<?php echo Icons::svg( 'chevron-left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Constant markup. ?>
				</button>
				<button type="button" class="bpch-carousel__button" aria-label="<?php esc_attr_e( 'Next review', 'blue-peak-climate-core' ); ?>" data-wp-on--click="actions.next">
					<?php echo Icons::svg( 'chevron-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Constant markup. ?>
				</button>
			</div>
		</div>
	<?php endif; ?>
</div>
