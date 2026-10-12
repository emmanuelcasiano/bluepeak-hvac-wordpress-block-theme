<?php
/**
 * Front-end markup for the bpch/symptom-picker block.
 *
 * The first tab is selected in the HTML, so the page looks right before any JavaScript runs. With no JavaScript the
 * tabs are hidden by CSS (`scripting: none`) and every panel shows, one under the other.
 *
 * @var array    $attributes Block attributes (none).
 * @var string   $content    Inner content (none).
 * @var WP_Block $block      Block instance.
 *
 * @package BluePeakClimate\Core
 */

defined( 'ABSPATH' ) || exit;

use BluePeakClimate\Core\Settings\Settings;
use BluePeakClimate\Core\Support\Icons;
use BluePeakClimate\Core\Symptoms\Catalog;

$bpch_symptoms = Catalog::all();

wp_interactivity_state(
	'bpch/symptom-picker',
	array(
		'isActive'      => static function (): bool {
			$context = wp_interactivity_get_context( 'bpch/symptom-picker' );

			return $context['id'] === $context['active'];
		},
		// Only the selected tab is in the tab order; the arrow keys move between the others.
		'tabIndex'      => static function (): int {
			$context = wp_interactivity_get_context( 'bpch/symptom-picker' );

			return $context['id'] === $context['active'] ? 0 : -1;
		},
		'isPanelHidden' => static function (): bool {
			$context = wp_interactivity_get_context( 'bpch/symptom-picker' );

			return $context['id'] !== $context['active'];
		},
	)
);

$bpch_ids   = wp_list_pluck( $bpch_symptoms, 'id' );
$bpch_uid   = wp_unique_id( 'bpch-picker-' );
$bpch_phone = trim( (string) Settings::get( 'phone' ) );
$bpch_call  = '' === $bpch_phone ? '' : sprintf(
	/* translators: %s: phone number. */
	__( 'Call %s', 'blue-peak-climate-core' ),
	$bpch_phone
);
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'bpch-picker' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by core. ?>
	data-wp-interactive="bpch/symptom-picker"
	data-wp-class--is-interacted="context.interacted"
	<?php
	echo wp_interactivity_data_wp_context( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by core.
		array(
			'active'     => $bpch_ids[0],
			'order'      => $bpch_ids,
			'interacted' => false,
		)
	);
	?>
>
	<div class="bpch-picker__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Symptoms', 'blue-peak-climate-core' ); ?>">
		<?php foreach ( $bpch_symptoms as $bpch_symptom ) : ?>
			<button
				type="button"
				role="tab"
				class="bpch-picker__tab"
				id="<?php echo esc_attr( $bpch_uid . '-tab-' . $bpch_symptom['id'] ); ?>"
				aria-controls="<?php echo esc_attr( $bpch_uid . '-panel-' . $bpch_symptom['id'] ); ?>"
				data-wp-bind--aria-selected="state.isActive"
				data-wp-bind--tabindex="state.tabIndex"
				data-wp-on--click="actions.select"
				data-wp-on--keydown="actions.keydown"
				<?php echo wp_interactivity_data_wp_context( array( 'id' => $bpch_symptom['id'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by core. ?>
			>
				<span class="bpch-picker__tab-label">
					<?php echo Icons::svg( $bpch_symptom['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Constant markup. ?>
					<span><?php echo esc_html( $bpch_symptom['label'] ); ?></span>
				</span>
				<span class="bpch-picker__arrow" aria-hidden="true">&rarr;</span>
			</button>
		<?php endforeach; ?>
	</div>

	<?php foreach ( $bpch_symptoms as $bpch_symptom ) : ?>
		<div
			class="bpch-picker__panel"
			role="tabpanel"
			id="<?php echo esc_attr( $bpch_uid . '-panel-' . $bpch_symptom['id'] ); ?>"
			aria-labelledby="<?php echo esc_attr( $bpch_uid . '-tab-' . $bpch_symptom['id'] ); ?>"
			data-wp-bind--hidden="state.isPanelHidden"
			<?php echo wp_interactivity_data_wp_context( array( 'id' => $bpch_symptom['id'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by core. ?>
		>
			<div class="bpch-picker__head">
				<span class="bpch-picker__badge"><?php echo esc_html( $bpch_symptom['badge'] ); ?></span>
				<h3 class="bpch-picker__title"><?php echo esc_html( $bpch_symptom['title'] ); ?></h3>
			</div>

			<div class="bpch-picker__columns">
				<div class="bpch-picker__col">
					<p class="bpch-picker__eyebrow"><?php esc_html_e( 'Likely causes', 'blue-peak-climate-core' ); ?></p>
					<ul class="bpch-picker__list">
						<?php foreach ( $bpch_symptom['causes'] as $bpch_cause ) : ?>
							<li><?php echo esc_html( $bpch_cause ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="bpch-picker__col">
					<p class="bpch-picker__eyebrow"><?php esc_html_e( "What we'll do", 'blue-peak-climate-core' ); ?></p>
					<p class="bpch-picker__plan"><?php echo esc_html( $bpch_symptom['plan'] ); ?></p>
				</div>
			</div>

			<p class="bpch-picker__tip">
				<span class="bpch-picker__tip-dot" aria-hidden="true"></span>
				<span><?php echo esc_html( $bpch_symptom['tip'] ); ?></span>
			</p>

			<div class="bpch-picker__cta">
				<a class="bpch-picker__button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php echo esc_html( $bpch_symptom['cta'] ); ?></a>
				<?php if ( '' !== $bpch_call ) : ?>
					<a class="bpch-picker__button bpch-picker__button--outline" href="<?php echo esc_url( Settings::phone_href( $bpch_phone ) ); ?>"><?php echo esc_html( $bpch_call ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>
