<?php
/**
 * Title: Header
 * Slug: blue-peak-climate/header
 * Inserter: no
 *
 * Topbar, logo, main menu, phone block and buttons. Template parts are plain HTML and can't be translated
 * or build URLs, so this lives in a pattern. Layout details (sticky, breakpoints, icons) are in style.css.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

$business = bpc_theme_placeholder_business();

$menu_items = array(
	array(
		'label' => __( 'Home', 'blue-peak-climate' ),
		'url'   => home_url( '/' ),
	),
	array(
		'label' => __( 'Services', 'blue-peak-climate' ),
		'url'   => home_url( '/services/' ),
	),
	array(
		'label' => __( 'Gallery', 'blue-peak-climate' ),
		'url'   => home_url( '/gallery/' ),
	),
	array(
		'label' => __( 'About', 'blue-peak-climate' ),
		'url'   => home_url( '/about/' ),
	),
	array(
		'label' => __( 'Contact', 'blue-peak-climate' ),
		'url'   => home_url( '/contact/' ),
	),
);

$contact_url = home_url( '/contact/' );

// Two menus built from the same links, so keyboard order matches the screen at every width: an inline menu
// (wider than 1024px) and a menu button with an overlay (1024px and narrower). style.css shows one of them.
$nav_base = array(
	'ariaLabel' => __( 'Main', 'blue-peak-climate' ),
	'textColor' => 'ink',
	'fontSize'  => 'small',
	'style'     => array( 'spacing' => array( 'blockGap' => 'var:preset|spacing|1' ) ),
	'layout'    => array(
		'type'                   => 'flex',
		'setCascadingProperties' => true,
		'justifyContent'         => 'left',
	),
);

$inline_nav_attributes = serialize_block_attributes(
	array_merge(
		$nav_base,
		array(
			'overlayMenu' => 'never',
			'className'   => 'bpc-nav bpc-nav--inline',
		)
	)
);

$overlay_nav_attributes = serialize_block_attributes(
	array_merge(
		$nav_base,
		array(
			'overlayMenu'            => 'always',
			'icon'                   => 'menu',
			'overlayBackgroundColor' => 'white',
			'overlayTextColor'       => 'ink',
			'className'              => 'bpc-nav bpc-nav--overlay',
		)
	)
);

$menu_markup = bpc_theme_nav_links_markup( $menu_items );

$call_label = sprintf(
	/* translators: %s: phone number. */
	__( 'Call %s', 'blue-peak-climate' ),
	$business['phone']
);

$emergency_line = sprintf(
	/* translators: %s: emergency phone number as a link. */
	esc_html__( '24/7 emergency line: %s', 'blue-peak-climate' ),
	'<a class="bpc-topbar__link" href="' . esc_url( $business['phone_href'] ) . '">' . esc_html( $business['phone'] ) . '</a>'
);
?>
<!-- wp:group {"align":"full","className":"bpc-topbar","backgroundColor":"navy","textColor":"on-navy","fontSize":"x-small","style":{"spacing":{"padding":{"top":"var:preset|spacing|2","bottom":"var:preset|spacing|2"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpc-topbar has-on-navy-color has-navy-background-color has-text-color has-background has-x-small-font-size" style="padding-top:var(--wp--preset--spacing--2);padding-bottom:var(--wp--preset--spacing--2)">
	<!-- wp:group {"className":"bpc-topbar__row","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group bpc-topbar__row">
		<!-- wp:paragraph {"className":"bpc-topbar__note"} -->
		<p class="bpc-topbar__note"><?php esc_html_e( 'Heating, cooling and electrical service for homes across Texas', 'blue-peak-climate' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p><?php echo wp_kses_post( $emergency_line ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"bpc-header","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpc-header">
	<!-- wp:group {"className":"bpc-header__row","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group bpc-header__row">
		<!-- wp:image {"sizeSlug":"full","linkDestination":"custom","className":"bpc-logo"} -->
		<figure class="wp-block-image size-full bpc-logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo.png' ) ); ?>" alt="<?php esc_attr_e( 'Blue Peak Climate home', 'blue-peak-climate' ); ?>"/></a></figure>
		<!-- /wp:image -->

		<!-- wp:navigation <?php echo $inline_nav_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
		<?php echo $menu_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup built by get_comment_delimited_block_content(). ?>
		<!-- /wp:navigation -->

		<!-- wp:group {"className":"bpc-header__actions","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
		<div class="wp-block-group bpc-header__actions">
			<!-- wp:group {"className":"bpc-header__phone","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical"}} -->
			<div class="wp-block-group bpc-header__phone">
				<!-- wp:paragraph {"className":"bpc-header__phone-label","fontSize":"x-small"} -->
				<p class="bpc-header__phone-label has-x-small-font-size"><?php esc_html_e( 'Call any hour', 'blue-peak-climate' ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"bpc-header__phone-number"} -->
				<p class="bpc-header__phone-number"><a href="<?php echo esc_url( $business['phone_href'] ); ?>"><?php echo esc_html( $business['phone'] ); ?></a></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

			<!-- wp:buttons {"className":"bpc-header__buttons"} -->
			<div class="wp-block-buttons bpc-header__buttons">
				<!-- wp:button {"className":"bpc-header__book"} -->
				<div class="wp-block-button bpc-header__book"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $contact_url ); ?>"><?php esc_html_e( 'Book service', 'blue-peak-climate' ); ?></a></div>
				<!-- /wp:button -->

				<!-- wp:button {"className":"bpc-header__call"} -->
				<div class="wp-block-button bpc-header__call"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $business['phone_href'] ); ?>"><?php esc_html_e( 'Call now', 'blue-peak-climate' ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->

			<!-- wp:navigation <?php echo $overlay_nav_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
			<?php echo $menu_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup built by get_comment_delimited_block_content(). ?>

			<!-- wp:buttons {"className":"bpc-nav__extra","layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
			<div class="wp-block-buttons bpc-nav__extra">
				<!-- wp:button {"width":100} -->
				<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $contact_url ); ?>"><?php esc_html_e( 'Book a service visit', 'blue-peak-climate' ); ?></a></div>
				<!-- /wp:button -->

				<!-- wp:button {"width":100,"className":"is-style-outline bpc-nav__call"} -->
				<div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline bpc-nav__call"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $business['phone_href'] ); ?>"><?php echo esc_html( $call_label ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
			<!-- /wp:navigation -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
