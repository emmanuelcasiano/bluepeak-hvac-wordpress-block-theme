<?php
/**
 * Title: Footer
 * Slug: blue-peak-climate/footer
 * Inserter: no
 *
 * Brand column, three link columns and the copyright bar. Template parts are plain HTML and can't be
 * translated or build URLs, so this lives in a pattern. Layout details are in style.css.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

$bpch_business = bpch_theme_business();

// Starting content of the blocks bound to the Site Settings (see bpch_theme_business()).
$bpch_call_label      = bpch_theme_call_label( $bpch_business['phone'] );
$bpch_call_attributes = bpch_theme_call_button_attributes();
$bpch_phone_link      = '<a href="' . esc_url( $bpch_business['phone_href'] ) . '">' . esc_html( $bpch_business['phone'] ) . '</a>';
$bpch_email_link      = '<a href="mailto:' . esc_attr( antispambot( $bpch_business['email'] ) ) . '">' . esc_html( antispambot( $bpch_business['email'] ) ) . '</a>';

$bpch_copyright = sprintf(
	/* translators: 1: current year, 2: site name. */
	__( '© %1$s %2$s. All rights reserved.', 'blue-peak-climate' ),
	gmdate( 'Y' ),
	get_bloginfo( 'name' )
);

$bpch_services_items = array(
	array(
		'label' => __( 'Heating', 'blue-peak-climate' ),
		'url'   => home_url( '/services/#heating' ),
	),
	array(
		'label' => __( 'Cooling', 'blue-peak-climate' ),
		'url'   => home_url( '/services/#cooling' ),
	),
	array(
		'label' => __( 'Electrical', 'blue-peak-climate' ),
		'url'   => home_url( '/services/#electrical' ),
	),
	array(
		'label' => __( 'Emergency repair', 'blue-peak-climate' ),
		'url'   => home_url( '/contact/' ),
	),
);

$bpch_company_items = array(
	array(
		'label' => __( 'Home', 'blue-peak-climate' ),
		'url'   => home_url( '/' ),
	),
	array(
		'label' => __( 'About', 'blue-peak-climate' ),
		'url'   => home_url( '/about/' ),
	),
	array(
		'label' => __( 'Gallery', 'blue-peak-climate' ),
		'url'   => home_url( '/gallery/' ),
	),
	array(
		'label' => __( 'Reviews', 'blue-peak-climate' ),
		'url'   => home_url( '/reviews/' ),
	),
	array(
		'label' => __( 'Offers', 'blue-peak-climate' ),
		'url'   => home_url( '/offers/' ),
	),
	array(
		'label' => __( 'Blog', 'blue-peak-climate' ),
		'url'   => home_url( '/blog/' ),
	),
	array(
		'label' => __( 'Contact', 'blue-peak-climate' ),
		'url'   => home_url( '/contact/' ),
	),
);

$bpch_nav_defaults = array(
	'overlayMenu' => 'never',
	'textColor'   => 'on-navy',
	'className'   => 'bpch-footer__nav',
	'style'       => array( 'spacing' => array( 'blockGap' => 'var:preset|spacing|2' ) ),
	'layout'      => array(
		'type'        => 'flex',
		'orientation' => 'vertical',
	),
);

$bpch_services_nav = serialize_block_attributes( array_merge( $bpch_nav_defaults, array( 'ariaLabel' => __( 'Footer services', 'blue-peak-climate' ) ) ) );
$bpch_company_nav  = serialize_block_attributes( array_merge( $bpch_nav_defaults, array( 'ariaLabel' => __( 'Footer company', 'blue-peak-climate' ) ) ) );
?>
<!-- wp:group {"align":"full","className":"bpch-footer","backgroundColor":"navy-deep","textColor":"on-navy","fontSize":"small","style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|6"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-footer has-on-navy-color has-navy-deep-background-color has-text-color has-background has-small-font-size" style="padding-top:var(--wp--preset--spacing--8);padding-bottom:var(--wp--preset--spacing--6)">
	<!-- wp:group {"className":"bpch-footer__top","style":{"spacing":{"blockGap":"var:preset|spacing|7"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"top"}} -->
	<div class="wp-block-group bpch-footer__top">
		<!-- wp:group {"className":"bpch-footer__brand","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
		<div class="wp-block-group bpch-footer__brand">
			<!-- wp:group {"className":"bpch-footer__plate","backgroundColor":"white","style":{"spacing":{"padding":{"top":"var:preset|spacing|3","bottom":"var:preset|spacing|3","left":"var:preset|spacing|4","right":"var:preset|spacing|4"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group bpch-footer__plate has-white-background-color has-background" style="padding-top:var(--wp--preset--spacing--3);padding-right:var(--wp--preset--spacing--4);padding-bottom:var(--wp--preset--spacing--3);padding-left:var(--wp--preset--spacing--4)">
				<!-- wp:image {"sizeSlug":"full","linkDestination":"custom","className":"bpch-logo bpch-logo--footer"} -->
				<figure class="wp-block-image size-full bpch-logo bpch-logo--footer"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo.png' ) ); ?>" alt="<?php esc_attr_e( 'Blue Peak Climate home', 'blue-peak-climate' ); ?>"/></a></figure>
				<!-- /wp:image -->
			</div>
			<!-- /wp:group -->

			<!-- wp:paragraph {"className":"bpch-footer__text"} -->
			<p class="bpch-footer__text"><?php esc_html_e( 'Heating, cooling and electrical service for homeowners across Texas. Emergency repairs 24 hours a day, every day of the year.', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons {"className":"bpch-footer__call"} -->
			<div class="wp-block-buttons bpch-footer__call">
				<!-- wp:button <?php echo $bpch_call_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $bpch_business['phone_href'] ); ?>"><?php echo esc_html( $bpch_call_label ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"bpch-footer__col","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
		<div class="wp-block-group bpch-footer__col">
			<!-- wp:heading {"level":2,"className":"bpch-footer__heading","textColor":"white","fontSize":"x-small"} -->
			<h2 class="wp-block-heading bpch-footer__heading has-white-color has-text-color has-x-small-font-size"><?php esc_html_e( 'Services', 'blue-peak-climate' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:navigation <?php echo $bpch_services_nav; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
			<?php echo bpch_theme_nav_links_markup( $bpch_services_items ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup built by get_comment_delimited_block_content(). ?>
			<!-- /wp:navigation -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"bpch-footer__col","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
		<div class="wp-block-group bpch-footer__col">
			<!-- wp:heading {"level":2,"className":"bpch-footer__heading","textColor":"white","fontSize":"x-small"} -->
			<h2 class="wp-block-heading bpch-footer__heading has-white-color has-text-color has-x-small-font-size"><?php esc_html_e( 'Company', 'blue-peak-climate' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:navigation <?php echo $bpch_company_nav; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
			<?php echo bpch_theme_nav_links_markup( $bpch_company_items ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup built by get_comment_delimited_block_content(). ?>
			<!-- /wp:navigation -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"bpch-footer__col bpch-footer__col--contact","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
		<div class="wp-block-group bpch-footer__col bpch-footer__col--contact">
			<!-- wp:heading {"level":2,"className":"bpch-footer__heading","textColor":"white","fontSize":"x-small"} -->
			<h2 class="wp-block-heading bpch-footer__heading has-white-color has-text-color has-x-small-font-size"><?php esc_html_e( 'Reach us', 'blue-peak-climate' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"className":"bpch-footer__item bpch-icon bpch-icon--phone","metadata":{"bindings":{"content":{"source":"bpch/site-settings","args":{"key":"phone","format":"link"}}}}} -->
			<p class="bpch-footer__item bpch-icon bpch-icon--phone"><?php echo wp_kses_post( $bpch_phone_link ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"className":"bpch-footer__item bpch-icon bpch-icon--mail","metadata":{"bindings":{"content":{"source":"bpch/site-settings","args":{"key":"email","format":"link"}}}}} -->
			<p class="bpch-footer__item bpch-icon bpch-icon--mail"><?php echo wp_kses_post( $bpch_email_link ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"className":"bpch-footer__item bpch-icon bpch-icon--clock","metadata":{"bindings":{"content":{"source":"bpch/site-settings","args":{"key":"hours"}}}}} -->
			<p class="bpch-footer__item bpch-icon bpch-icon--clock"><?php echo esc_html( $bpch_business['hours'] ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"className":"bpch-footer__item bpch-icon bpch-icon--pin","metadata":{"bindings":{"content":{"source":"bpch/site-settings","args":{"key":"service_area"}}}}} -->
			<p class="bpch-footer__item bpch-icon bpch-icon--pin"><?php echo esc_html( $bpch_business['service_area'] ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"bpch-footer__bottom","fontSize":"x-small","style":{"spacing":{"padding":{"top":"var:preset|spacing|5"},"margin":{"top":"var:preset|spacing|7"}}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group bpch-footer__bottom has-x-small-font-size" style="margin-top:var(--wp--preset--spacing--7);padding-top:var(--wp--preset--spacing--5)">
		<!-- wp:paragraph -->
		<p><?php echo esc_html( $bpch_copyright ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p><?php esc_html_e( 'Heating / Cooling / Electrical', 'blue-peak-climate' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
