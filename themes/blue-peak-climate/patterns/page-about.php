<?php
/**
 * Title: About page
 * Slug: blue-peak-climate/page-about
 * Categories: blue-peak-climate
 * Block Types: core/post-content
 * Description: Story hero, three promises, what to expect from a technician, the service area and a call to action.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

$bpch_call_attributes = bpch_theme_call_button_attributes();
$bpch_business        = bpch_theme_business();

$bpch_promises = array(
	array( '01', 'clock', __( 'We show up in the window we give you', 'blue-peak-climate' ), __( "If we're running late, you hear from us before the window closes, not after.", 'blue-peak-climate' ) ),
	array( '02', 'file', __( 'You see the price first', 'blue-peak-climate' ), __( 'Every job gets a written price before we start. No surprise line items on the invoice.', 'blue-peak-climate' ) ),
	array( '03', 'wrench', __( "We fix what's actually broken", 'blue-peak-climate' ), __( "We won't sell a new system to a house that needs a capacitor. If replacing makes more sense, we'll show you why.", 'blue-peak-climate' ) ),
);

$bpch_expect = array(
	__( 'Arrives in a marked BluePeak truck and uniform', 'blue-peak-climate' ),
	__( 'Wears shoe covers inside your home', 'blue-peak-climate' ),
	__( 'Explains the problem in plain language and shows you the failed part', 'blue-peak-climate' ),
	__( 'Gives you repair and replace options with written prices', 'blue-peak-climate' ),
	__( 'Runs the system with you before leaving', 'blue-peak-climate' ),
);
?>
<!-- wp:group {"align":"full","className":"bpch-hero","backgroundColor":"mist","style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|9"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-hero has-mist-background-color has-background" style="padding-top:var(--wp--preset--spacing--8);padding-bottom:var(--wp--preset--spacing--9)">
	<!-- wp:group {"className":"bpch-hero__inner","style":{"spacing":{"blockGap":"var:preset|spacing|8"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
	<div class="wp-block-group bpch-hero__inner">
		<!-- wp:group {"className":"bpch-hero__copy","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-hero__copy">
			<!-- wp:paragraph {"className":"bpch-eyebrow","fontSize":"x-small"} -->
			<p class="bpch-eyebrow has-x-small-font-size"><?php esc_html_e( 'About BluePeak', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":1,"className":"bpch-hero__title"} -->
			<h1 class="wp-block-heading bpch-hero__title"><?php esc_html_e( 'A Texas heating and air company that picks up the phone', 'blue-peak-climate' ); ?></h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"fontSize":"large"} -->
			<p class="has-large-font-size"><?php esc_html_e( 'BluePeak Climate works on homes. We install, repair, and maintain the systems that keep Texas families cool through summer and warm through the occasional hard freeze.', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Our technicians handle heating, cooling, and the electrical work that goes with them. One call covers the furnace, the condenser, and the breaker that keeps tripping.', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"bpch-hero__media","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-hero__media">
			<?php echo bpch_theme_photo_cover( 'tech-wide', 'wide' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helper. ?>
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"bpch-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-section" style="padding-top:var(--wp--preset--spacing--9);padding-bottom:var(--wp--preset--spacing--9)">
	<!-- wp:group {"className":"bpch-section__head","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
	<div class="wp-block-group bpch-section__head">
		<!-- wp:paragraph {"className":"bpch-eyebrow","fontSize":"x-small"} -->
		<p class="bpch-eyebrow has-x-small-font-size"><?php esc_html_e( 'How we work', 'blue-peak-climate' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:heading -->
		<h2 class="wp-block-heading"><?php esc_html_e( 'Three promises on every job', 'blue-peak-climate' ); ?></h2>
		<!-- /wp:heading -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"bpch-cards","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"stretch"}} -->
	<div class="wp-block-group bpch-cards">
		<?php foreach ( $bpch_promises as $bpch_promise ) : ?>
		<!-- wp:group {"className":"bpch-card","backgroundColor":"white","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-card has-white-background-color has-background">
			<!-- wp:group {"className":"bpch-step__top","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
			<div class="wp-block-group bpch-step__top">
				<!-- wp:paragraph {"className":"bpch-step__number"} -->
				<p class="bpch-step__number"><?php echo esc_html( $bpch_promise[0] ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:bpch/icon {"icon":"<?php echo esc_attr( $bpch_promise[1] ); ?>","tone":"light","lock":{"move":true,"remove":true}} /-->
			</div>
			<!-- /wp:group -->

			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php echo esc_html( $bpch_promise[2] ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p><?php echo esc_html( $bpch_promise[3] ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"bpch-section bpch-section--split","backgroundColor":"mist","style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-section bpch-section--split has-mist-background-color has-background" style="padding-top:var(--wp--preset--spacing--9);padding-bottom:var(--wp--preset--spacing--9)">
	<!-- wp:group {"className":"bpch-split","style":{"spacing":{"blockGap":"var:preset|spacing|8"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
	<div class="wp-block-group bpch-split">
		<!-- wp:group {"className":"bpch-split__copy","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-split__copy">
			<!-- wp:paragraph {"className":"bpch-eyebrow","fontSize":"x-small"} -->
			<p class="bpch-eyebrow has-x-small-font-size"><?php esc_html_e( 'Your visit', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:heading -->
			<h2 class="wp-block-heading"><?php esc_html_e( 'What to expect from your technician', 'blue-peak-climate' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:list {"className":"bpch-service-list bpch-expect-list","textColor":"ink"} -->
			<ul class="wp-block-list bpch-service-list bpch-expect-list has-ink-color has-text-color">
				<?php foreach ( $bpch_expect as $bpch_line ) : ?>
				<!-- wp:list-item -->
				<li><?php echo esc_html( $bpch_line ); ?></li>
				<!-- /wp:list-item -->
				<?php endforeach; ?>
			</ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:group -->

		<?php echo bpch_theme_photo_cover( 'family-home', 'wide' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helper. ?>
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"bpch-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"},"blockGap":"var:preset|spacing|5"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-section" style="padding-top:var(--wp--preset--spacing--9);padding-bottom:var(--wp--preset--spacing--9)">
	<!-- wp:group {"className":"bpch-card bpch-area","backgroundColor":"blue-soft","style":{"spacing":{"blockGap":"var:preset|spacing|6"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group bpch-card bpch-area has-blue-soft-background-color has-background">
		<!-- wp:group {"className":"bpch-area__copy","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-area__copy">
			<!-- wp:group {"className":"bpch-area__title","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
			<div class="wp-block-group bpch-area__title">
				<!-- wp:bpch/icon {"icon":"pin","tone":"navy","lock":{"move":true,"remove":true}} /-->

				<!-- wp:heading {"level":3} -->
				<h3 class="wp-block-heading"><?php esc_html_e( 'Serving homeowners across Texas', 'blue-peak-climate' ); ?></h3>
				<!-- /wp:heading -->
			</div>
			<!-- /wp:group -->

			<!-- wp:paragraph -->
			<p><?php esc_html_e( "Call to check that we cover your ZIP code. We'll tell you straight away.", 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:buttons -->
		<div class="wp-block-buttons">
			<!-- wp:button <?php echo $bpch_call_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
			<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $bpch_business['phone_href'] ); ?>"><?php echo esc_html( bpch_theme_call_label( $bpch_business['phone'] ) ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->

	<!-- wp:paragraph {"className":"bpch-placeholder-note","fontSize":"small"} -->
	<p class="bpch-placeholder-note has-small-font-size"><?php esc_html_e( '[Placeholder] License numbers and certifications (for example the state license and NATE) are added here before launch.', 'blue-peak-climate' ); ?></p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<?php
echo bpch_theme_cta_band( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helper.
	__( 'Put us to work on your system', 'blue-peak-climate' ),
	__( 'Repairs, tune-ups, and new installs for heating, cooling, and electrical.', 'blue-peak-climate' ),
	__( 'Book a service visit', 'blue-peak-climate' ),
	home_url( '/contact/' ),
	array(
		'label' => __( 'See all services', 'blue-peak-climate' ),
		'url'   => home_url( '/services/' ),
	)
);
