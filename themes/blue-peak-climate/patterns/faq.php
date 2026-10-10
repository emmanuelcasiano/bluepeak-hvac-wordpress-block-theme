<?php
/**
 * Title: FAQ
 * Slug: blue-peak-climate/faq
 * Categories: blue-peak-climate
 * Description: An introduction with the phone number beside a list of questions that open and close (core Details block).
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

$bpch_business = bpch_theme_business();

// The phone link under the introduction: link and text both come from the Site Settings.
$bpch_phone_attributes = serialize_block_attributes(
	array(
		'metadata' => array(
			'bindings' => array(
				'url'  => array(
					'source' => 'bpch/site-settings',
					'args'   => array( 'key' => 'phone' ),
				),
				'text' => array(
					'source' => 'bpch/site-settings',
					'args'   => array( 'key' => 'phone' ),
				),
			),
		),
	)
);

$bpch_questions = array(
	array(
		__( 'How much does a service visit cost?', 'blue-peak-climate' ),
		__( 'We tell you the diagnostic fee on the phone before we book. Once the technician finds the problem, you get a written price for the repair before any work starts.', 'blue-peak-climate' ),
	),
	array(
		__( 'How fast can you get here?', 'blue-peak-climate' ),
		__( 'For most repairs we give you an arrival window when you book, often the same day. For no cooling in a heat wave or no heat in a freeze, call the 24/7 line.', 'blue-peak-climate' ),
	),
	array(
		__( 'Which brands do you work on?', 'blue-peak-climate' ),
		__( 'All the major brands of central AC, furnaces, heat pumps, boilers, and mini-splits, including Carrier, Trane, Lennox, Goodman, Rheem, and Daikin.', 'blue-peak-climate' ),
	),
	array(
		__( 'Should I repair or replace my AC?', 'blue-peak-climate' ),
		__( "If the system is over 12 years old and the repair costs more than about a third of a new system, replacing usually makes more sense. We'll show you both prices and you decide.", 'blue-peak-climate' ),
	),
	array(
		__( 'How often should my system be serviced?', 'blue-peak-climate' ),
		__( 'Once a year for the AC in spring and once for the furnace in fall. Heat pumps work all year, so they do best with a visit each spring and fall.', 'blue-peak-climate' ),
	),
	array(
		__( 'Do you handle the electrical side too?', 'blue-peak-climate' ),
		__( "Yes. We replace breakers, run dedicated circuits, and wire disconnects for new equipment, so you don't need a separate electrician.", 'blue-peak-climate' ),
	),
);
?>
<!-- wp:group {"align":"full","className":"bpch-section bpch-section--faq","style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-section bpch-section--faq" style="padding-top:var(--wp--preset--spacing--9);padding-bottom:var(--wp--preset--spacing--9)">
	<!-- wp:group {"className":"bpch-faq","style":{"spacing":{"blockGap":"var:preset|spacing|8"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
	<div class="wp-block-group bpch-faq">
		<!-- wp:group {"className":"bpch-faq__intro","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-faq__intro">
			<!-- wp:paragraph {"className":"bpch-eyebrow","fontSize":"x-small"} -->
			<p class="bpch-eyebrow has-x-small-font-size"><?php esc_html_e( 'Questions', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:heading -->
			<h2 class="wp-block-heading"><?php esc_html_e( 'Things people ask before they book', 'blue-peak-climate' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p><?php esc_html_e( "Something else on your mind? Call and ask. You'll get a straight answer.", 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons {"className":"bpch-link-arrow"} -->
			<div class="wp-block-buttons bpch-link-arrow">
				<!-- wp:button <?php echo $bpch_phone_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $bpch_business['phone_href'] ); ?>"><?php echo esc_html( $bpch_business['phone'] ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"bpch-faq__list","layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-faq__list">
			<?php foreach ( $bpch_questions as $bpch_index => $bpch_item ) : ?>
			<!-- wp:details <?php echo 0 === $bpch_index ? '{"name":"bpch-faq","showContent":true,"className":"bpch-faq__item"}' : '{"name":"bpch-faq","className":"bpch-faq__item"}'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed JSON, no user data. ?> -->
			<details class="wp-block-details bpch-faq__item" name="bpch-faq"<?php echo 0 === $bpch_index ? ' open' : ''; ?>><summary><?php echo esc_html( $bpch_item[0] ); ?></summary>
				<!-- wp:paragraph -->
				<p><?php echo esc_html( $bpch_item[1] ); ?></p>
				<!-- /wp:paragraph --></details>
			<!-- /wp:details -->

			<?php endforeach; ?>
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
