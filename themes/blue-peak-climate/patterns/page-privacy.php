<?php
/**
 * Title: Privacy Policy page
 * Slug: blue-peak-climate/page-privacy
 * Categories: blue-peak-climate
 * Block Types: core/post-content
 * Description: Draft privacy policy for review by a lawyer: what the site collects, why, who sees it, and the visitor's choices.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

$bpch_cookie_url = bpch_theme_cookie_policy_url();
if ( '' === $bpch_cookie_url ) {
	$bpch_cookie_url = home_url( '/cookie-policy/' );
}

$bpch_sections = array(
	array(
		'id'      => 'who-we-are',
		'heading' => __( 'Who we are', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'We are a heating, cooling and electrical service company that serves homes across Texas. This policy covers this website and the information you share with us through it, by phone or by email.', 'blue-peak-climate' ) ),
			bpch_theme_legal_p( esc_html__( 'If you have a question about this policy, contact us using the details at the end.', 'blue-peak-climate' ) ),
		),
	),
	array(
		'id'      => 'what-we-collect',
		'heading' => __( 'Information we collect', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_heading( __( 'Information you give us', 'blue-peak-climate' ), 3 ),
			bpch_theme_legal_p( esc_html__( 'When you send a service request through the contact form, we ask for:', 'blue-peak-climate' ) ),
			bpch_theme_legal_list(
				array(
					esc_html__( 'Your name, phone number and email address', 'blue-peak-climate' ),
					esc_html__( 'Your ZIP code', 'blue-peak-climate' ),
					esc_html__( 'The kind of help you need and the type of system you have', 'blue-peak-climate' ),
					esc_html__( 'The best time to reach you', 'blue-peak-climate' ),
					esc_html__( 'Anything you write in the message box', 'blue-peak-climate' ),
				)
			),
			bpch_theme_legal_p( esc_html__( 'If you call or email us, we also keep the details you share, such as your address and what is wrong with your system, so we can help you.', 'blue-peak-climate' ) ),
			bpch_theme_legal_heading( __( 'Information collected as you browse', 'blue-peak-climate' ), 3 ),
			bpch_theme_legal_p( esc_html__( 'Like most websites, we receive some technical information when you visit, such as your IP address, your browser and device type, and the pages you view. Our web host also keeps basic server logs for security and troubleshooting.', 'blue-peak-climate' ) ),
			bpch_theme_legal_p( esc_html__( 'With your permission, we use Google Analytics to see which pages are useful. It records things like the pages you view, how you found the website, your device and approximate location, and whether you tap a phone number or send the contact form. We never send your name, phone number, email address, ZIP code or message to Google Analytics.', 'blue-peak-climate' ) ),
			bpch_theme_legal_heading( __( 'Cookies', 'blue-peak-climate' ), 3 ),
			bpch_theme_legal_p(
				sprintf(
					/* translators: %s: URL of the Cookie Policy page. */
					__( 'We use necessary cookies to make the website work. We set analytics cookies only if you accept them in the cookie banner, and you can change your choice at any time with the Manage cookies button at the bottom of every page. If you decline, we set no analytics cookies, although Google may still receive a basic signal that does not use cookies, such as the page address and your browser type. Our <a href="%s">Cookie Policy</a> lists the cookies we use.', 'blue-peak-climate' ),
					esc_url( $bpch_cookie_url )
				)
			),
		),
	),
	array(
		'id'      => 'how-we-use-it',
		'heading' => __( 'How we use your information', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'We use your information to:', 'blue-peak-climate' ) ),
			bpch_theme_legal_list(
				array(
					esc_html__( 'Answer your request and call you back to set up a visit', 'blue-peak-climate' ),
					esc_html__( 'Send your request to the office closest to you, using your ZIP code', 'blue-peak-climate' ),
					esc_html__( 'Give you a price and do the work you ask for', 'blue-peak-climate' ),
					esc_html__( 'Keep records of our work and of the service we gave you', 'blue-peak-climate' ),
					esc_html__( 'Keep the website working and secure', 'blue-peak-climate' ),
					esc_html__( 'Learn which pages are useful so we can improve them, if you accept analytics cookies', 'blue-peak-climate' ),
					esc_html__( 'Meet legal, tax and insurance requirements', 'blue-peak-climate' ),
				)
			),
			bpch_theme_legal_p( esc_html__( 'We do not sell your personal information, and we do not share it with advertisers.', 'blue-peak-climate' ) ),
		),
	),
	array(
		'id'      => 'who-we-share-it-with',
		'heading' => __( 'Who we share it with', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'We share your information only when we need to:', 'blue-peak-climate' ) ),
			bpch_theme_legal_list(
				array(
					esc_html__( 'Our staff. Your service request goes by email to the office closest to your ZIP code, and to the people who schedule and carry out the work.', 'blue-peak-climate' ),
					esc_html__( 'Companies that help us run this website, such as our web host and our email delivery service. They may use your information only to provide those services to us.', 'blue-peak-climate' ),
					esc_html__( 'Google, for website analytics, only if you accept analytics cookies.', 'blue-peak-climate' ),
					esc_html__( 'Authorities or other parties, when the law requires it or when we need to protect our rights or someone\'s safety.', 'blue-peak-climate' ),
					esc_html__( 'A new owner, if the business is sold or merged. Your information may be part of what transfers.', 'blue-peak-climate' ),
				)
			),
			bpch_theme_legal_note( __( 'Name the web host and the email delivery service here once the business has chosen them.', 'blue-peak-climate' ) ),
		),
	),
	array(
		'id'      => 'how-long-we-keep-it',
		'heading' => __( 'How long we keep it', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'We keep your information only as long as we need it to answer your request, do the work and keep our business records, or as long as the law requires. After that we delete it or remove what identifies you.', 'blue-peak-climate' ) ),
			bpch_theme_legal_note( __( 'State how long service requests are kept (for example, a number of years) once the business and its lawyer decide.', 'blue-peak-climate' ) ),
		),
	),
	array(
		'id'      => 'your-choices',
		'heading' => __( 'Your privacy choices', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'We offer these choices to everyone, including people in Texas, whether or not the law requires us to. You can ask us to:', 'blue-peak-climate' ) ),
			bpch_theme_legal_list(
				array(
					esc_html__( 'Tell you what personal information we hold about you', 'blue-peak-climate' ),
					esc_html__( 'Give you a copy of it', 'blue-peak-climate' ),
					esc_html__( 'Correct anything that is wrong', 'blue-peak-climate' ),
					esc_html__( 'Delete it', 'blue-peak-climate' ),
					esc_html__( 'Limit how we use it', 'blue-peak-climate' ),
				)
			),
			bpch_theme_legal_p( esc_html__( 'To make a request, email or call us using the details at the end of this policy. We may ask a few questions to confirm that the request is from you. We will answer within 45 days. If we need more time, we will tell you why. If we cannot do what you ask, we will explain the reason, and you can reply and ask for a second review.', 'blue-peak-climate' ) ),
			bpch_theme_legal_p( esc_html__( 'You can also choose which cookies you accept at any time with the Manage cookies button, and you can set your browser to block cookies.', 'blue-peak-climate' ) ),
			bpch_theme_legal_note( __( 'A lawyer should confirm whether the Texas Data Privacy and Security Act applies to the business, and adjust this section if it does.', 'blue-peak-climate' ) ),
		),
	),
	array(
		'id'      => 'how-we-protect-it',
		'heading' => __( 'How we protect it', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'We take reasonable steps to protect your information, including an encrypted connection (HTTPS) on this website and limits on who can see it. No website or email is completely secure, so please do not send sensitive details, such as payment card or bank numbers, through the contact form.', 'blue-peak-climate' ) ),
		),
	),
	array(
		'id'      => 'children',
		'heading' => __( 'Children', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'This website is for adults. It is not meant for children under 13, and we do not knowingly collect information from them. If you think a child has sent us information, contact us and we will delete it.', 'blue-peak-climate' ) ),
		),
	),
	array(
		'id'      => 'other-websites',
		'heading' => __( 'Links to other websites', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'This website may link to other sites, such as our social media pages. We do not control them and we are not responsible for how they handle your information. Please read their policies.', 'blue-peak-climate' ) ),
		),
	),
	array(
		'id'      => 'changes',
		'heading' => __( 'Changes to this policy', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'We may update this policy from time to time. The date at the top shows when it last changed. If we make a major change, we will say so on this page.', 'blue-peak-climate' ) ),
		),
	),
	array(
		'id'      => 'contact-us',
		'heading' => __( 'Contact us', 'blue-peak-climate' ),
		'blocks'  => array(
			bpch_theme_legal_p( esc_html__( 'Questions about this policy, or want to use one of your choices? Reach us here:', 'blue-peak-climate' ) ),
			bpch_theme_legal_contact(),
			bpch_theme_legal_note( __( 'Add the business\'s mailing address here once the client provides it.', 'blue-peak-climate' ) ),
		),
	),
);

echo bpch_theme_legal_page( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup escaped by the helpers in inc/legal.php.
	__( 'Legal', 'blue-peak-climate' ),
	__( 'Privacy Policy', 'blue-peak-climate' ),
	__( 'What we collect when you use this website, why we collect it, and the choices you have.', 'blue-peak-climate' ),
	__( 'This policy is a starting draft for the business owner and a lawyer to review. It is not legal advice. Remove this notice when the text is approved.', 'blue-peak-climate' ),
	$bpch_sections
);
