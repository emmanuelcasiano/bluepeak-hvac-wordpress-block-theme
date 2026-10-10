<?php
/**
 * Front-end markup for the blue-peak-climate-core/example block.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner block content (unused).
 * @var WP_Block $block      Block instance.
 *
 * @package BluePeakClimate\Core
 */

defined( 'ABSPATH' ) || exit;

$bpc_message = $attributes['message'] ?? '';

if ( '' === $bpc_message ) {
	return;
}
?>
<p <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by core. ?>>
	<?php echo wp_kses_post( $bpc_message ); ?>
</p>
