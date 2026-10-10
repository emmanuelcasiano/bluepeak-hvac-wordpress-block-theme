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

$business = bpc_theme_placeholder_business();

$call_label = sprintf(
	/* translators: %s: phone number. */
	__( 'Call %s', 'blue-peak-climate' ),
	$business['phone']
);

$copyright = sprintf(
	/* translators: 1: current year, 2: site name. */
	__( '© %1$s %2$s. All rights reserved.', 'blue-peak-climate' ),
	gmdate( 'Y' ),
	get_bloginfo( 'name' )
);

$services_items = array(
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

$company_items = array(
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
		'label' => __( 'Contact', 'blue-peak-climate' ),
		'url'   => home_url( '/contact/' ),
	),
);

$nav_defaults = array(
	'overlayMenu' => 'never',
	'textColor'   => 'on-navy',
	'className'   => 'bpc-footer__nav',
	'style'       => array( 'spacing' => array( 'blockGap' => 'var:preset|spacing|2' ) ),
	'layout'      => array(
		'type'        => 'flex',
		'orientation' => 'vertical',
	),
);

$services_nav = serialize_block_attributes( array_merge( $nav_defaults, array( 'ariaLabel' => __( 'Footer services', 'blue-peak-climate' ) ) ) );
$company_nav  = serialize_block_attributes( array_merge( $nav_defaults, array( 'ariaLabel' => __( 'Footer company', 'blue-peak-climate' ) ) ) );
?>
<!-- wp:group {"align":"full","className":"bpc-footer","backgroundColor":"navy-deep","textColor":"on-navy","fontSize":"small","style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|6"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpc-footer has-on-navy-color has-navy-deep-background-color has-text-color has-background has-small-font-size" style="padding-top:var(--wp--preset--spacing--8);padding-bottom:var(--wp--preset--spacing--6)">
	<!-- wp:group {"className":"bpc-footer__top","style":{"spacing":{"blockGap":"var:preset|spacing|7"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"top"}} -->
	<div class="wp-block-group bpc-footer__top">
		<!-- wp:group {"className":"bpc-footer__brand","style":{"spacing":{"blockGap":"var:preset|spacing|4"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
		<div class="wp-block-group bpc-footer__brand">
			<!-- wp:group {"className":"bpc-footer__plate","backgroundColor":"white","style":{"spacing":{"padding":{"top":"var:preset|spacing|3","bottom":"var:preset|spacing|3","left":"var:preset|spacing|4","right":"var:preset|spacing|4"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group bpc-footer__plate has-white-background-color has-background" style="padding-top:var(--wp--preset--spacing--3);padding-right:var(--wp--preset--spacing--4);padding-bottom:var(--wp--preset--spacing--3);padding-left:var(--wp--preset--spacing--4)">
				<!-- wp:image {"sizeSlug":"full","linkDestination":"custom","className":"bpc-logo bpc-logo--footer"} -->
				<figure class="wp-block-image size-full bpc-logo bpc-logo--footer"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo.png' ) ); ?>" alt="<?php esc_attr_e( 'Blue Peak Climate home', 'blue-peak-climate' ); ?>"/></a></figure>
				<!-- /wp:image -->
			</div>
			<!-- /wp:group -->

			<!-- wp:paragraph {"className":"bpc-footer__text"} -->
			<p class="bpc-footer__text"><?php esc_html_e( 'Heating, cooling and electrical service for homeowners across Texas. Emergency repairs 24 hours a day, every day of the year.', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons {"className":"bpc-footer__call"} -->
			<div class="wp-block-buttons bpc-footer__call">
				<!-- wp:button -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $business['phone_href'] ); ?>"><?php echo esc_html( $call_label ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"bpc-footer__col","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
		<div class="wp-block-group bpc-footer__col">
			<!-- wp:heading {"level":2,"className":"bpc-footer__heading","textColor":"white","fontSize":"x-small"} -->
			<h2 class="wp-block-heading bpc-footer__heading has-white-color has-text-color has-x-small-font-size"><?php esc_html_e( 'Services', 'blue-peak-climate' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:navigation <?php echo $services_nav; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
			<?php echo bpc_theme_nav_links_markup( $services_items ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup built by get_comment_delimited_block_content(). ?>
			<!-- /wp:navigation -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"bpc-footer__col","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
		<div class="wp-block-group bpc-footer__col">
			<!-- wp:heading {"level":2,"className":"bpc-footer__heading","textColor":"white","fontSize":"x-small"} -->
			<h2 class="wp-block-heading bpc-footer__heading has-white-color has-text-color has-x-small-font-size"><?php esc_html_e( 'Company', 'blue-peak-climate' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:navigation <?php echo $company_nav; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
			<?php echo bpc_theme_nav_links_markup( $company_items ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup built by get_comment_delimited_block_content(). ?>
			<!-- /wp:navigation -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"bpc-footer__col bpc-footer__col--contact","style":{"spacing":{"blockGap":"var:preset|spacing|3"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
		<div class="wp-block-group bpc-footer__col bpc-footer__col--contact">
			<!-- wp:heading {"level":2,"className":"bpc-footer__heading","textColor":"white","fontSize":"x-small"} -->
			<h2 class="wp-block-heading bpc-footer__heading has-white-color has-text-color has-x-small-font-size"><?php esc_html_e( 'Reach us', 'blue-peak-climate' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"className":"bpc-footer__item bpc-icon bpc-icon--phone"} -->
			<p class="bpc-footer__item bpc-icon bpc-icon--phone"><a href="<?php echo esc_url( $business['phone_href'] ); ?>"><?php echo esc_html( $business['phone'] ); ?></a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"className":"bpc-footer__item bpc-icon bpc-icon--mail"} -->
			<p class="bpc-footer__item bpc-icon bpc-icon--mail"><a href="mailto:<?php echo esc_attr( antispambot( $business['email'] ) ); ?>"><?php echo esc_html( antispambot( $business['email'] ) ); ?></a></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"className":"bpc-footer__item bpc-icon bpc-icon--clock"} -->
			<p class="bpc-footer__item bpc-icon bpc-icon--clock"><?php esc_html_e( 'Emergency line open 24/7', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"className":"bpc-footer__item bpc-icon bpc-icon--pin"} -->
			<p class="bpc-footer__item bpc-icon bpc-icon--pin"><?php esc_html_e( 'Homes across Texas', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"bpc-footer__bottom","fontSize":"x-small","style":{"spacing":{"padding":{"top":"var:preset|spacing|5"},"margin":{"top":"var:preset|spacing|7"}}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group bpc-footer__bottom has-x-small-font-size" style="margin-top:var(--wp--preset--spacing--7);padding-top:var(--wp--preset--spacing--5)">
		<!-- wp:paragraph -->
		<p><?php echo esc_html( $copyright ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p><?php esc_html_e( 'Heating / Cooling / Electrical', 'blue-peak-climate' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
