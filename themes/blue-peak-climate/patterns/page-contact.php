<?php
/**
 * Title: Contact page
 * Slug: blue-peak-climate/page-contact
 * Categories: blue-peak-climate
 * Block Types: core/post-content
 * Description: Page intro, the emergency, email and service-area cards, and the place for the request form.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

$bpch_business = bpch_theme_business();

/**
 * A Button block whose link and text both come from one Site Setting, used as the big value on a contact card.
 *
 * @param string $key  Setting: phone or email.
 * @param string $href Link before the binding replaces it.
 * @param string $text Text before the binding replaces it.
 * @return string Block markup.
 */
$bpch_value_button = static function ( $key, $href, $text ) {
	$source     = array(
		'source' => 'bpch/site-settings',
		'args'   => array( 'key' => $key ),
	);
	$attributes = serialize_block_attributes(
		array(
			'className' => 'bpch-contact-link',
			'metadata'  => array(
				'bindings' => array(
					'url'  => $source,
					'text' => $source,
				),
			),
		)
	);

	return '<!-- wp:button ' . $attributes . ' -->' . "\n"
		. '<div class="wp-block-button bpch-contact-link"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $href ) . '">' . esc_html( $text ) . '</a></div>' . "\n"
		. '<!-- /wp:button -->';
};

$bpch_area_attributes = serialize_block_attributes(
	array(
		'className' => 'bpch-contact-value',
		'fontSize'  => 'large',
		'metadata'  => array(
			'bindings' => array(
				'content' => array(
					'source' => 'bpch/site-settings',
					'args'   => array( 'key' => 'service_area' ),
				),
			),
		),
	)
);

echo bpch_theme_page_intro( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helper.
	__( 'Contact', 'blue-peak-climate' ),
	__( 'Book a visit or ask a question', 'blue-peak-climate' ),
	__( "Call for anything urgent. For everything else, send a request and we'll call you back to set a time.", 'blue-peak-climate' ),
	'',
	'family-home'
);
?>

<!-- wp:group {"align":"full","className":"bpch-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|9","bottom":"var:preset|spacing|9"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-section" style="padding-top:var(--wp--preset--spacing--9);padding-bottom:var(--wp--preset--spacing--9)">
	<!-- wp:group {"className":"bpch-contact","style":{"spacing":{"blockGap":"var:preset|spacing|7"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
	<div class="wp-block-group bpch-contact">
		<!-- wp:group {"className":"bpch-contact__info","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-contact__info">
			<!-- wp:group {"className":"bpch-card bpch-contact-card bpch-contact-card--urgent","backgroundColor":"navy","textColor":"white","style":{"spacing":{"blockGap":"var:preset|spacing|2"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
			<div class="wp-block-group bpch-card bpch-contact-card bpch-contact-card--urgent has-white-color has-navy-background-color has-text-color has-background">
				<!-- wp:bpch/icon {"icon":"phone","tone":"orange","lock":{"move":true,"remove":true}} /-->

				<!-- wp:paragraph {"className":"bpch-eyebrow bpch-eyebrow--light","fontSize":"x-small"} -->
				<p class="bpch-eyebrow bpch-eyebrow--light has-x-small-font-size"><?php esc_html_e( '24/7 emergency line', 'blue-peak-climate' ); ?></p>
				<!-- /wp:paragraph -->

				<?php echo $bpch_value_button( 'phone', $bpch_business['phone_href'], $bpch_business['phone'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the closure. ?>

				<!-- wp:paragraph {"textColor":"on-navy"} -->
				<p class="has-on-navy-color has-text-color"><?php esc_html_e( 'Nights, weekends, and holidays. A technician answers.', 'blue-peak-climate' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

			<!-- wp:group {"className":"bpch-card bpch-contact-card","backgroundColor":"white","style":{"spacing":{"blockGap":"var:preset|spacing|2"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
			<div class="wp-block-group bpch-card bpch-contact-card has-white-background-color has-background">
				<!-- wp:bpch/icon {"icon":"mail","tone":"light","lock":{"move":true,"remove":true}} /-->

				<!-- wp:paragraph {"className":"bpch-eyebrow","fontSize":"x-small"} -->
				<p class="bpch-eyebrow has-x-small-font-size"><?php esc_html_e( 'Email', 'blue-peak-climate' ); ?></p>
				<!-- /wp:paragraph -->

				<?php echo $bpch_value_button( 'email', 'mailto:' . $bpch_business['email'], $bpch_business['email'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the closure. ?>

				<!-- wp:paragraph -->
				<p><?php esc_html_e( 'Quotes, scheduling, and billing questions', 'blue-peak-climate' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

			<!-- wp:group {"className":"bpch-card bpch-contact-card","backgroundColor":"white","style":{"spacing":{"blockGap":"var:preset|spacing|2"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
			<div class="wp-block-group bpch-card bpch-contact-card has-white-background-color has-background">
				<!-- wp:bpch/icon {"icon":"pin","tone":"light","lock":{"move":true,"remove":true}} /-->

				<!-- wp:paragraph {"className":"bpch-eyebrow","fontSize":"x-small"} -->
				<p class="bpch-eyebrow has-x-small-font-size"><?php esc_html_e( 'Service area', 'blue-peak-climate' ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph <?php echo $bpch_area_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
				<p class="bpch-contact-value has-large-font-size"><?php echo esc_html( $bpch_business['service_area'] ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph -->
				<p><?php esc_html_e( 'Ask us about your ZIP code', 'blue-peak-climate' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

		<?php
		// The request form is a Fluent Forms form (see inc/forms.php). If the plugin is off or the form has not been created
		// yet, the card points people to the phone and email instead of showing an empty box.
		$bpch_form_id = bpch_theme_service_request_form_id();
		?>
		<!-- wp:group {"className":"bpch-card bpch-contact__form","backgroundColor":"white","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group bpch-card bpch-contact__form has-white-background-color has-background">
			<!-- wp:heading -->
			<h2 class="wp-block-heading"><?php esc_html_e( 'Request service', 'blue-peak-climate' ); ?></h2>
			<!-- /wp:heading -->
			<?php if ( $bpch_form_id ) : ?>

			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Takes about a minute. Fields marked with an asterisk are required.', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:shortcode -->
			[fluentform id="<?php echo (int) $bpch_form_id; ?>"]
			<!-- /wp:shortcode -->
			<?php else : ?>

			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'The online request form is not available right now. Call or email us and we will set a time.', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->
			<?php endif; ?>
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
