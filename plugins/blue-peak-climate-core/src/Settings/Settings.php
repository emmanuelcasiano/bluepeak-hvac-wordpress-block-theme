<?php
/**
 * Site Settings data: defaults, reading, sanitizing and the ZIP → office lookup.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Settings;

defined( 'ABSPATH' ) || exit;

/**
 * Everything is stored in one option, so one read covers the whole header and footer. The option is small and
 * needed on almost every request, so it stays autoloaded.
 *
 * The admin page (SettingsPage) writes it, the block bindings source (Bindings) and other features read it.
 */
final class Settings {

	/**
	 * Option name.
	 */
	public const OPTION = 'bpch_core_settings';

	/**
	 * Most ZIP rules kept on save, so a bad paste can't bloat the autoloaded option.
	 */
	public const MAX_OFFICES = 50;

	/**
	 * Social link fields, in the order they show on the admin page.
	 *
	 * @return string[]
	 */
	public static function social_keys(): array {
		return array( 'facebook', 'instagram', 'youtube', 'google_business' );
	}

	/**
	 * Values used until someone saves the page. They match the theme's earlier placeholders, so the site looks
	 * the same before the first save.
	 *
	 * @return array<string, mixed>
	 */
	public static function defaults(): array {
		return array(
			'legal_name'       => 'Blue Peak Climate',
			'phone'            => '(555) 123-4567',
			'email'            => 'hello@bluepeakclimate.com',
			'hours'            => __( 'Emergency line open 24/7', 'blue-peak-climate-core' ),
			'service_area'     => __( 'Homes across Texas', 'blue-peak-climate-core' ),
			'area_served'      => __( 'Texas', 'blue-peak-climate-core' ),
			'emergency_banner' => false,
			'demo_notice'      => '',
			'facebook'         => '',
			'instagram'        => '',
			'youtube'          => '',
			'google_business'  => '',
			'offices'          => array(),
		);
	}

	/**
	 * All settings, with defaults filling anything not saved yet.
	 *
	 * @return array<string, mixed>
	 */
	public static function all(): array {
		$stored = get_option( self::OPTION, array() );

		return array_merge( self::defaults(), is_array( $stored ) ? $stored : array() );
	}

	/**
	 * One setting. Unknown keys return an empty string.
	 *
	 * @param string $key Setting key, see defaults().
	 * @return mixed
	 */
	public static function get( string $key ) {
		$all = self::all();

		return $all[ $key ] ?? '';
	}

	/**
	 * Turns a display phone number into a tel: link target: "(555) 123-4567" becomes "tel:5551234567".
	 *
	 * @param string $phone Phone number as typed.
	 * @return string Empty when there are no digits.
	 */
	public static function phone_href( string $phone ): string {
		$dialable = preg_replace( '/[^\d+]/', '', $phone );

		return preg_match( '/\d/', $dialable ) ? 'tel:' . $dialable : '';
	}

	/**
	 * Picks the office email for a quote request: the rule with the longest matching ZIP prefix wins, and any ZIP
	 * with no rule goes to the main email.
	 *
	 * @param string $zip ZIP code as typed (a ZIP+4 or spaces are fine).
	 * @return string
	 */
	public static function office_email_for_zip( string $zip ): string {
		$all     = self::all();
		$default = (string) $all['email'];
		$zip     = substr( (string) preg_replace( '/\D/', '', $zip ), 0, 5 );

		if ( '' === $zip || ! is_array( $all['offices'] ) ) {
			return $default;
		}

		$best_length = 0;
		$best_email  = $default;
		foreach ( $all['offices'] as $office ) {
			$prefix = isset( $office['prefix'] ) ? (string) $office['prefix'] : '';
			$email  = isset( $office['email'] ) ? (string) $office['email'] : '';

			if ( '' !== $prefix && '' !== $email && 0 === strpos( $zip, $prefix ) && strlen( $prefix ) > $best_length ) {
				$best_length = strlen( $prefix );
				$best_email  = $email;
			}
		}

		return $best_email;
	}

	/**
	 * Cleans the settings form before it is saved. Phone and email are required: if they are missing or invalid
	 * the previous value is kept and an error notice is queued.
	 *
	 * @param mixed                $input    Form values, already unslashed by WordPress.
	 * @param array<string, mixed> $previous Currently saved values.
	 * @return array<string, mixed>
	 */
	public static function sanitize( $input, array $previous = array() ): array {
		$input    = is_array( $input ) ? $input : array();
		$fallback = array_merge( self::defaults(), $previous );
		$clean    = array();

		$clean['legal_name']   = self::text( $input, 'legal_name' );
		$clean['hours']        = self::text( $input, 'hours' );
		$clean['service_area'] = self::text( $input, 'service_area' );
		$clean['area_served']  = self::text( $input, 'area_served' );
		$clean['demo_notice']  = self::text( $input, 'demo_notice' );

		// Letters never belong in a phone number, so drop them rather than store something that can't be dialed.
		$phone = isset( $input['phone'] ) && is_string( $input['phone'] ) ? trim( preg_replace( '/[^0-9 ()+.\-]/', '', $input['phone'] ) ) : '';
		if ( '' === self::phone_href( $phone ) || strlen( preg_replace( '/\D/', '', $phone ) ) < 7 ) {
			add_settings_error( self::OPTION, 'bpch_invalid_phone', __( 'Enter a valid phone number. The previous one was kept.', 'blue-peak-climate-core' ), 'error' );
			$phone = $fallback['phone'];
		}
		$clean['phone'] = $phone;

		$email = isset( $input['email'] ) && is_string( $input['email'] ) ? sanitize_email( $input['email'] ) : '';
		if ( '' === $email ) {
			add_settings_error( self::OPTION, 'bpch_invalid_email', __( 'Enter a valid email address. The previous one was kept.', 'blue-peak-climate-core' ), 'error' );
			$email = $fallback['email'];
		}
		$clean['email'] = $email;

		$clean['emergency_banner'] = ! empty( $input['emergency_banner'] );

		foreach ( self::social_keys() as $key ) {
			$url           = isset( $input[ $key ] ) && is_string( $input[ $key ] ) ? trim( $input[ $key ] ) : '';
			$clean[ $key ] = '' === $url ? '' : esc_url_raw( $url, array( 'http', 'https' ) );
		}

		$clean['offices'] = self::sanitize_offices( $input['offices'] ?? array() );

		return $clean;
	}

	/**
	 * Cleans the ZIP → office rows. Blank rows are dropped, a prefix keeps its first row if repeated.
	 *
	 * @param mixed $rows Submitted rows, each with prefix, name and email.
	 * @return array<int, array{prefix: string, name: string, email: string}>
	 */
	private static function sanitize_offices( $rows ): array {
		if ( ! is_array( $rows ) ) {
			return array();
		}

		$offices = array();
		foreach ( $rows as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}

			$prefix = isset( $row['prefix'] ) && is_string( $row['prefix'] ) ? substr( (string) preg_replace( '/\D/', '', $row['prefix'] ), 0, 5 ) : '';
			$email  = isset( $row['email'] ) && is_string( $row['email'] ) ? sanitize_email( $row['email'] ) : '';

			// A rule needs both parts to be useful.
			if ( '' === $prefix || '' === $email || isset( $offices[ $prefix ] ) ) {
				continue;
			}

			$offices[ $prefix ] = array(
				'prefix' => $prefix,
				'name'   => self::text( $row, 'name' ),
				'email'  => $email,
			);

			if ( count( $offices ) >= self::MAX_OFFICES ) {
				break;
			}
		}

		return array_values( $offices );
	}

	/**
	 * Reads one plain-text field.
	 *
	 * @param array  $source Form values.
	 * @param string $key    Field key.
	 * @return string
	 */
	private static function text( array $source, string $key ): string {
		return isset( $source[ $key ] ) && is_string( $source[ $key ] ) ? sanitize_text_field( $source[ $key ] ) : '';
	}
}
