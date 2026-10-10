<?php
/**
 * Block Bindings source that lets core blocks read the Site Settings.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Settings;

defined( 'ABSPATH' ) || exit;

/**
 * Registers `bpch/site-settings`. A block opts in through its metadata, for example a paragraph:
 *
 *   <!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"bpch/site-settings","args":{"key":"phone","format":"link"}}}}} -->
 *
 * Args: `key` is the setting and `format` is how to print it:
 *   text - the value itself (default for text attributes such as `content` and `text`)
 *   url  - a link target: tel:/mailto: for phone and email, the URL for social links (default for `url`)
 *   link - a ready-made <a> link, for phone and email inside a paragraph or heading
 *
 * If the value is empty the block keeps the content saved in the pattern.
 */
final class Bindings {

	/**
	 * Settings that blocks may read. The ZIP table and the banner toggle are for PHP code, not for blocks.
	 */
	private const KEYS = array( 'legal_name', 'phone', 'email', 'hours', 'service_area', 'facebook', 'instagram', 'youtube', 'google_business' );

	/**
	 * Attributes that hold HTML, so text printed there must be escaped. Other attributes (alt, title) are
	 * escaped by WordPress when it writes them into the tag.
	 */
	private const HTML_ATTRIBUTES = array( 'content', 'text', 'caption' );

	/**
	 * Hooks the source registration.
	 */
	public function register(): void {
		add_action( 'init', array( $this, 'register_source' ) );
	}

	/**
	 * Registers the source with WordPress.
	 */
	public function register_source(): void {
		if ( ! function_exists( 'register_block_bindings_source' ) ) {
			return;
		}

		register_block_bindings_source(
			'bpch/site-settings',
			array(
				'label'              => __( 'Site Settings', 'blue-peak-climate-core' ),
				'get_value_callback' => array( $this, 'get_value' ),
			)
		);
	}

	/**
	 * Returns the value for one bound attribute, or null to keep the block's saved content.
	 *
	 * @param array  $args           Binding args: key and optional format.
	 * @param mixed  $block_instance Block being rendered (unused).
	 * @param string $attribute_name Bound attribute, e.g. content, text, url.
	 * @return string|null
	 */
	public function get_value( array $args, $block_instance, string $attribute_name ): ?string {
		unset( $block_instance );

		$key = isset( $args['key'] ) && is_string( $args['key'] ) ? $args['key'] : '';
		if ( ! in_array( $key, self::KEYS, true ) ) {
			return null;
		}

		$value = Settings::get( $key );
		if ( ! is_string( $value ) || '' === $value ) {
			return null;
		}

		$format = isset( $args['format'] ) && is_string( $args['format'] ) ? $args['format'] : ( 'url' === $attribute_name ? 'url' : 'text' );

		switch ( $format ) {
			case 'url':
				return $this->url_for( $key, $value );

			case 'link':
				return $this->link_for( $key, $value );

			case 'text':
				return in_array( $attribute_name, self::HTML_ATTRIBUTES, true ) ? $this->html_text( $key, $value ) : $value;

			default:
				return null;
		}
	}

	/**
	 * A link target: tel: for the phone, mailto: for the email, the saved URL for social links.
	 *
	 * @param string $key   Setting key.
	 * @param string $value Setting value.
	 * @return string|null
	 */
	private function url_for( string $key, string $value ): ?string {
		if ( 'phone' === $key ) {
			$href = Settings::phone_href( $value );
			return '' === $href ? null : $href;
		}

		if ( 'email' === $key ) {
			return 'mailto:' . $value;
		}

		return in_array( $key, Settings::social_keys(), true ) ? esc_url_raw( $value ) : null;
	}

	/**
	 * A whole link, so a paragraph can show a clickable phone number or email.
	 *
	 * @param string $key   Setting key.
	 * @param string $value Setting value.
	 * @return string|null Null for settings that have no link form.
	 */
	private function link_for( string $key, string $value ): ?string {
		if ( 'phone' === $key ) {
			$href = Settings::phone_href( $value );
			return '' === $href ? null : '<a href="' . esc_url( $href ) . '">' . esc_html( $value ) . '</a>';
		}

		if ( 'email' === $key ) {
			// antispambot() hides the address from simple scrapers by writing some characters as HTML entities.
			return '<a href="mailto:' . esc_attr( antispambot( $value ) ) . '">' . esc_html( antispambot( $value ) ) . '</a>';
		}

		return null;
	}

	/**
	 * Text for an HTML attribute (paragraph content, button text), escaped.
	 *
	 * @param string $key   Setting key.
	 * @param string $value Setting value.
	 * @return string
	 */
	private function html_text( string $key, string $value ): string {
		return esc_html( 'email' === $key ? antispambot( $value ) : $value );
	}
}
