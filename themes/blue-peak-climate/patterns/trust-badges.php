<?php
/**
 * Title: Trust badges
 * Slug: blue-peak-climate/trust-badges
 * Categories: blue-peak-climate
 * Description: Four short promises shown as small pills with icons.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:list {"className":"bpch-trust","fontSize":"small"} -->
<ul class="wp-block-list bpch-trust has-small-font-size">
	<!-- wp:list-item {"className":"bpch-icon bpch-icon--shield"} -->
	<li class="bpch-icon bpch-icon--shield"><?php esc_html_e( 'Licensed and insured', 'blue-peak-climate' ); ?></li>
	<!-- /wp:list-item -->

	<!-- wp:list-item {"className":"bpch-icon bpch-icon--clock"} -->
	<li class="bpch-icon bpch-icon--clock"><?php esc_html_e( '24/7 emergency service', 'blue-peak-climate' ); ?></li>
	<!-- /wp:list-item -->

	<!-- wp:list-item {"className":"bpch-icon bpch-icon--file"} -->
	<li class="bpch-icon bpch-icon--file"><?php esc_html_e( 'Written price up front', 'blue-peak-climate' ); ?></li>
	<!-- /wp:list-item -->

	<!-- wp:list-item {"className":"bpch-icon bpch-icon--badge"} -->
	<li class="bpch-icon bpch-icon--badge"><?php esc_html_e( 'Workmanship guarantee', 'blue-peak-climate' ); ?></li>
	<!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
