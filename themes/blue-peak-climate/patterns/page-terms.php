<?php
/**
 * Title: Terms of Service page
 * Slug: blue-peak-climate/page-terms
 * Categories: blue-peak-climate
 * Block Types: core/post-content
 * Description: Draft terms for review by a lawyer: using the website, quotes and prices, offers, emergency service and limits.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

$bpch_sections = array(
	array(
		'id'      => 'using-this-website',
		'heading' => __( 'Using this website', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'These terms cover your use of this website. By using it, you agree to them. If you do not agree, please do not use the website.', 'blue-peak-climate' ) ),
			bpch_theme_legal_p( esc_html__( 'Please use the website only for lawful purposes. Do not try to break into it, overload it, copy it in bulk with automated tools, or send us anything harmful or false.', 'blue-peak-climate' ) ),
			bpch_theme_legal_p( esc_html__( 'These terms are about the website. The work we do in your home is covered by the written estimate or work order we give you. If the two disagree, the written estimate or work order controls for that work.', 'blue-peak-climate' ) ),
			bpch_theme_legal_p(
				sprintf(
					/* translators: %s: URL of the Privacy Policy page. */
					__( 'How we handle your information is explained in our <a href="%s">Privacy Policy</a>.', 'blue-peak-climate' ),
					esc_url( home_url( '/privacy-policy/' ) )
				)
			),
		),
	),
	array(
		'id'      => 'requests-and-prices',
		'heading' => __( 'Service requests and prices', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'Sending a request through the contact form, or calling us, is a request for service. It is not a booking, and it does not create a contract until we confirm a visit or you accept a written estimate.', 'blue-peak-climate' ) ),
			bpch_theme_legal_p( esc_html__( 'Prices on this website, including "starting at" prices, are starting points. The final price depends on what we find at your home. We explain the price to you before we start the work.', 'blue-peak-climate' ) ),
			bpch_theme_legal_note( __( 'Add the business\'s state license numbers and any notice Texas requires on contracts and advertising once the business provides them.', 'blue-peak-climate' ) ),
		),
	),
	array(
		'id'      => 'offers-and-coupons',
		'heading' => __( 'Offers and coupons', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p(
				sprintf(
					/* translators: %s: URL of the Offers page. */
					__( 'Each offer on our <a href="%s">Offers page</a> has its own end date and fine print. An offer ends at the end of the day shown. Unless an offer says otherwise, it cannot be combined with other offers. We may change or withdraw an offer at any time.', 'blue-peak-climate' ),
					esc_url( home_url( '/offers/' ) )
				)
			),
		),
	),
	array(
		'id'      => 'emergency-service',
		'heading' => __( 'Emergency service', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'Our emergency line is for urgent heating, cooling and electrical problems. We work to reach you as fast as we can, but any arrival time we give is an estimate and not a guarantee. Weather, traffic and demand can change it.', 'blue-peak-climate' ) ),
			bpch_theme_legal_p( '<strong>' . esc_html__( 'Safety first.', 'blue-peak-climate' ) . '</strong> ' . esc_html__( 'If you smell gas, see smoke or fire, or think anyone is in danger, leave the building right away and call 911 or your gas utility from outside.', 'blue-peak-climate' ) ),
		),
	),
	array(
		'id'      => 'information-on-this-website',
		'heading' => __( 'Information on this website', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'The articles, tips and descriptions on this website are general information. Every home is different, so they are not advice for your own system. Please do not work on gas, electrical or other equipment based only on what you read here. Call a licensed professional.', 'blue-peak-climate' ) ),
			bpch_theme_legal_p( esc_html__( 'Photos, reviews and examples are for illustration. They may not show the exact equipment or work in your home, and results vary from one home to the next.', 'blue-peak-climate' ) ),
		),
	),
	array(
		'id'      => 'our-content',
		'heading' => __( 'Our content', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'The text, logo, photos and design on this website belong to us or are used with permission. You may view the website and share links to it for your own non-commercial use. Please do not copy or reuse our content for other purposes without our written permission.', 'blue-peak-climate' ) ),
		),
	),
	array(
		'id'      => 'other-websites',
		'heading' => __( 'Links to other websites', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'This website may link to other websites. We do not control them, and we are not responsible for their content or practices.', 'blue-peak-climate' ) ),
		),
	),
	array(
		'id'      => 'no-promises-about-the-website',
		'heading' => __( 'No promises about the website', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'We work to keep this website accurate and available, but we provide it as it is. We do not promise that it will always be available, free of errors or up to date. Nothing on it is a warranty on any work. Warranties on our work, if any, are in the written estimate or work order.', 'blue-peak-climate' ) ),
		),
	),
	array(
		'id'      => 'limits-on-our-responsibility',
		'heading' => __( 'Limits on our responsibility', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'To the extent the law allows, we are not responsible for any indirect, incidental or consequential loss that comes from using this website or relying on what it says. This does not limit any responsibility we have for our work under a written agreement, and it does not limit anything the law does not allow us to limit.', 'blue-peak-climate' ) ),
			bpch_theme_legal_note( __( 'A lawyer should review this section and decide whether to add a limit on the amount of liability.', 'blue-peak-climate' ) ),
		),
	),
	array(
		'id'      => 'governing-law',
		'heading' => __( 'Governing law', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'These terms are governed by the laws of the State of Texas. Any dispute about them will be brought in the courts located in Texas.', 'blue-peak-climate' ) ),
			bpch_theme_legal_note( __( 'Add the county where disputes will be heard once the business and its lawyer decide.', 'blue-peak-climate' ) ),
		),
	),
	array(
		'id'      => 'changes',
		'heading' => __( 'Changes to these terms', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'We may update these terms from time to time. The date at the top shows when they last changed. Using the website after a change means you accept the new terms.', 'blue-peak-climate' ) ),
		),
	),
	array(
		'id'      => 'contact-us',
		'heading' => __( 'Contact us', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'Questions about these terms? Reach us here:', 'blue-peak-climate' ) ),
			bpch_theme_legal_contact(),
			bpch_theme_legal_note( __( 'Add the business\'s mailing address here once the client provides it.', 'blue-peak-climate' ) ),
		),
	),
);

echo bpch_theme_legal_page( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helpers in inc/legal.php.
	__( 'Legal', 'blue-peak-climate' ),
	__( 'Terms of Service', 'blue-peak-climate' ),
	__( 'The rules for using this website, and how quotes, offers and emergency calls work.', 'blue-peak-climate' ),
	__( 'These terms are a starting draft for the business owner and a lawyer to review. They are not legal advice. Remove this notice when the text is approved.', 'blue-peak-climate' ),
	$bpch_sections
);
