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

$bpch_business = bpch_theme_business();

$bpch_menu_items = array(
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
		'label' => __( 'Blog', 'blue-peak-climate' ),
		'url'   => home_url( '/blog/' ),
	),
	array(
		'label' => __( 'Contact', 'blue-peak-climate' ),
		'url'   => home_url( '/contact/' ),
	),
);

$bpch_contact_url = home_url( '/contact/' );

// Two menus built from the same links, so keyboard order matches the screen at every width: an inline menu
// (wider than 1200px) and a menu button with an overlay (1200px and narrower). style.css shows one of them.
$bpch_nav_base = array(
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

$bpch_inline_nav_attributes = serialize_block_attributes(
	array_merge(
		$bpch_nav_base,
		array(
			'overlayMenu' => 'never',
			'className'   => 'bpch-nav bpch-nav--inline',
		)
	)
);

$bpch_overlay_nav_attributes = serialize_block_attributes(
	array_merge(
		$bpch_nav_base,
		array(
			'overlayMenu'            => 'always',
			'icon'                   => 'menu',
			'overlayBackgroundColor' => 'white',
			'overlayTextColor'       => 'ink',
			'className'              => 'bpch-nav bpch-nav--overlay',
		)
	)
);

$bpch_menu_markup = bpch_theme_nav_links_markup( $bpch_menu_items );

// Starting content of the blocks bound to the Site Settings (see bpch_theme_business()).
$bpch_call_label              = bpch_theme_call_label( $bpch_business['phone'] );
$bpch_overlay_call_attributes = bpch_theme_call_button_attributes(
	array(
		'width'     => 100,
		'className' => 'is-style-outline bpch-nav__call',
	)
);
$bpch_phone_link              = '<a href="' . esc_url( $bpch_business['phone_href'] ) . '">' . esc_html( $bpch_business['phone'] ) . '</a>';
?>
<!-- wp:group {"align":"full","className":"bpch-topbar","backgroundColor":"navy","textColor":"on-navy","fontSize":"x-small","style":{"spacing":{"padding":{"top":"var:preset|spacing|2","bottom":"var:preset|spacing|2"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-topbar has-on-navy-color has-navy-background-color has-text-color has-background has-x-small-font-size" style="padding-top:var(--wp--preset--spacing--2);padding-bottom:var(--wp--preset--spacing--2)">
	<!-- wp:group {"className":"bpch-topbar__row","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group bpch-topbar__row">
		<!-- wp:paragraph {"className":"bpch-topbar__note"} -->
		<p class="bpch-topbar__note"><?php esc_html_e( 'Heating, cooling and electrical service for homes across Texas', 'blue-peak-climate' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:group {"className":"bpch-topbar__emergency","style":{"spacing":{"blockGap":"var:preset|spacing|1"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group bpch-topbar__emergency">
			<!-- wp:paragraph -->
			<p><?php esc_html_e( '24/7 emergency line:', 'blue-peak-climate' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"bpch/site-settings","args":{"key":"phone","format":"link"}}}}} -->
			<p><?php echo wp_kses_post( $bpch_phone_link ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"bpch-header","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bpch-header">
	<!-- wp:group {"className":"bpch-header__row","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group bpch-header__row">
		<!-- wp:image {"sizeSlug":"full","linkDestination":"custom","className":"bpch-logo"} -->
		<figure class="wp-block-image size-full bpch-logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo.png' ) ); ?>" alt="<?php esc_attr_e( 'Blue Peak Climate home', 'blue-peak-climate' ); ?>"/></a></figure>
		<!-- /wp:image -->

		<!-- wp:navigation <?php echo $bpch_inline_nav_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
		<?php echo $bpch_menu_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup built by get_comment_delimited_block_content(). ?>
		<!-- /wp:navigation -->

		<!-- wp:group {"className":"bpch-header__actions","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
		<div class="wp-block-group bpch-header__actions">
			<!-- wp:group {"className":"bpch-header__phone","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical"}} -->
			<div class="wp-block-group bpch-header__phone">
				<!-- wp:paragraph {"className":"bpch-header__phone-label","fontSize":"x-small"} -->
				<p class="bpch-header__phone-label has-x-small-font-size"><?php esc_html_e( 'Call any hour', 'blue-peak-climate' ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"className":"bpch-header__phone-number","metadata":{"bindings":{"content":{"source":"bpch/site-settings","args":{"key":"phone","format":"link"}}}}} -->
				<p class="bpch-header__phone-number"><?php echo wp_kses_post( $bpch_phone_link ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

			<!-- wp:buttons {"className":"bpch-header__buttons"} -->
			<div class="wp-block-buttons bpch-header__buttons">
				<!-- wp:button {"className":"bpch-header__book"} -->
				<div class="wp-block-button bpch-header__book"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $bpch_contact_url ); ?>"><?php esc_html_e( 'Book service', 'blue-peak-climate' ); ?></a></div>
				<!-- /wp:button -->

				<!-- wp:button {"className":"bpch-header__call","metadata":{"bindings":{"url":{"source":"bpch/site-settings","args":{"key":"phone"}}}}} -->
				<div class="wp-block-button bpch-header__call"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $bpch_business['phone_href'] ); ?>"><?php esc_html_e( 'Call now', 'blue-peak-climate' ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->

			<!-- wp:navigation <?php echo $bpch_overlay_nav_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
			<?php echo $bpch_menu_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup built by get_comment_delimited_block_content(). ?>

			<!-- wp:buttons {"className":"bpch-nav__extra","layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
			<div class="wp-block-buttons bpch-nav__extra">
				<!-- wp:button {"width":100} -->
				<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $bpch_contact_url ); ?>"><?php esc_html_e( 'Book a service visit', 'blue-peak-climate' ); ?></a></div>
				<!-- /wp:button -->

				<!-- wp:button <?php echo $bpch_overlay_call_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block comment JSON from serialize_block_attributes(). ?> -->
				<div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline bpch-nav__call"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $bpch_business['phone_href'] ); ?>"><?php echo esc_html( $bpch_call_label ); ?></a></div>
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
