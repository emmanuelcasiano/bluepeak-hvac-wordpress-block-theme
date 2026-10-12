<?php
/**
 * LocalBusiness schema built from Site Settings.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Seo;

use BluePeakClimate\Core\Settings\Settings;

defined( 'ABSPATH' ) || exit;

/**
 * Rank Math always prints one `publisher` node (the organization every page, post and service points to). This class
 * rewrites that node as an HVACBusiness from Site Settings, so the phone, email, hours and social links are typed in
 * one place and the markup never disagrees with the page. Nothing is added when Rank Math is off.
 *
 * There is no street address in Site Settings: the business is described as a service-area business. Google's local
 * business results need an address, so that is a later addition if the client has a public one.
 */
final class BusinessSchema {

	/**
	 * Schema.org type. HVACBusiness is a LocalBusiness.
	 */
	public const TYPE = 'HVACBusiness';

	/**
	 * Hooks into Rank Math's graph. Priority 20 runs after Rank Math builds the organization node.
	 */
	public function register(): void {
		add_filter( 'rank_math/schema/add_global_entities', array( $this, 'keep_global_entities' ) );
		add_filter( 'rank_math/json_ld', array( $this, 'filter' ), 20 );
	}

	/**
	 * Rank Math leaves the organization, website and page nodes off a page or service whose default schema type is
	 * "off", and this site turns that off (no Article on pages, no Rank Math service schema). Without those nodes
	 * the Service and FAQ nodes would point at nothing, so single pages always get them.
	 *
	 * @param mixed $can_add Rank Math's own answer.
	 * @return mixed
	 */
	public function keep_global_entities( $can_add ) {
		return is_singular() ? true : $can_add;
	}

	/**
	 * Rewrites the organization node.
	 *
	 * @param mixed $data Rank Math's graph, keyed by entity.
	 * @return mixed
	 */
	public function filter( $data ) {
		if ( ! is_array( $data ) || empty( $data['publisher'] ) || ! is_array( $data['publisher'] ) ) {
			return $data;
		}

		$data['publisher'] = $this->business( $data['publisher'], Settings::all(), home_url( '/' ) );

		return $data;
	}

	/**
	 * Builds the business node. Anything Rank Math already put on the node (id, logo) is kept.
	 *
	 * @param array<string, mixed> $node     The node Rank Math built.
	 * @param array<string, mixed> $settings Site Settings.
	 * @param string               $home_url Home page URL.
	 * @return array<string, mixed>
	 */
	public function business( array $node, array $settings, string $home_url ): array {
		$node['@type'] = self::TYPE;
		$node['url']   = $home_url;

		// A local business is expected to have an image. The logo from Rank Math's settings stands in for it.
		if ( ! empty( $node['logo']['@id'] ) && empty( $node['image'] ) ) {
			$node['image'] = array( '@id' => $node['logo']['@id'] );
		}

		$name = trim( (string) ( $settings['legal_name'] ?? '' ) );
		if ( '' !== $name ) {
			$node['name'] = $name;
		}

		$phone = self::telephone( (string) ( $settings['phone'] ?? '' ) );
		if ( '' !== $phone ) {
			$node['telephone'] = $phone;
		}

		$email = trim( (string) ( $settings['email'] ?? '' ) );
		if ( '' !== $email ) {
			$node['email'] = $email;
		}

		$area = self::area_served( (string) ( $settings['area_served'] ?? '' ) );
		if ( array() !== $area ) {
			$node['areaServed'] = $area;
		}

		$hours = self::opening_hours( (string) ( $settings['hours'] ?? '' ) );
		if ( array() !== $hours ) {
			$node['openingHoursSpecification'] = $hours;
		}

		// Rank Math's own social list comes from its settings, which this site does not use.
		unset( $node['sameAs'] );
		$same_as = self::same_as( $settings );
		if ( array() !== $same_as ) {
			$node['sameAs'] = $same_as;
		}

		return $node;
	}

	/**
	 * The area as a schema.org place. AdministrativeArea covers a state, a region or a city.
	 *
	 * @param string $name Area name, for example "Texas".
	 * @return array<string, string> Empty when no name is set.
	 */
	public static function area_served( string $name ): array {
		$name = trim( $name );

		return '' === $name ? array() : array(
			'@type' => 'AdministrativeArea',
			'name'  => $name,
		);
	}

	/**
	 * A phone number in international form when it is a US number, otherwise as typed.
	 * "(555) 123-4567" becomes "+15551234567".
	 *
	 * @param string $phone Phone as typed in Site Settings.
	 * @return string
	 */
	public static function telephone( string $phone ): string {
		$phone  = trim( $phone );
		$digits = (string) preg_replace( '/\D/', '', $phone );

		if ( 10 === strlen( $digits ) ) {
			return '+1' . $digits;
		}

		if ( 11 === strlen( $digits ) && '1' === $digits[0] ) {
			return '+' . $digits;
		}

		return $phone;
	}

	/**
	 * Opening hours, only when the hours line clearly says the business is always open ("24/7", "24 hours", "around
	 * the clock"). The hours field is free text, so any other wording is left out rather than guessed.
	 *
	 * @param string $hours Hours line from Site Settings.
	 * @return array<int, array<string, mixed>>
	 */
	public static function opening_hours( string $hours ): array {
		if ( ! preg_match( '#24\s*/\s*7|24[\s-]*hours?|around the clock#i', $hours ) ) {
			return array();
		}

		return array(
			array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ),
				'opens'     => '00:00',
				'closes'    => '23:59',
			),
		);
	}

	/**
	 * The social profile links that are filled in.
	 *
	 * @param array<string, mixed> $settings Site Settings.
	 * @return string[]
	 */
	private static function same_as( array $settings ): array {
		$links = array();
		foreach ( Settings::social_keys() as $key ) {
			$url = isset( $settings[ $key ] ) && is_string( $settings[ $key ] ) ? trim( $settings[ $key ] ) : '';
			if ( '' !== $url ) {
				$links[] = $url;
			}
		}

		return $links;
	}
}
