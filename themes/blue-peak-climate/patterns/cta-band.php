<?php
/**
 * Title: CTA band
 * Slug: blue-peak-climate/cta-band
 * Categories: blue-peak-climate
 * Description: Orange band that asks the visitor to book a visit or call.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

echo bpch_theme_cta_band( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helper.
	__( 'Get your home comfortable again', 'blue-peak-climate' ),
	__( 'Book a repair or a tune-up, or ask for a free quote on a new system.', 'blue-peak-climate' ),
	__( 'Book a service visit', 'blue-peak-climate' ),
	home_url( '/contact/' )
);
