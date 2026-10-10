<?php
/**
 * The starting list of services.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Services;

defined( 'ABSPATH' ) || exit;

/**
 * Plain data: the 22 services from the design prototype, in the order they appear on the Services page.
 * The Installer turns these into posts once. After that the client edits the posts, not this file.
 */
final class Catalog {

	/**
	 * Service categories: slug => name.
	 *
	 * @return array<string, string>
	 */
	public static function categories(): array {
		return array(
			'heating'    => 'Heating',
			'cooling'    => 'Cooling',
			'electrical' => 'Electrical',
		);
	}

	/**
	 * Service types, the small tag on each card: slug => name.
	 *
	 * @return array<string, string>
	 */
	public static function types(): array {
		return array(
			'repair'      => 'Repair',
			'replace'     => 'Replace',
			'install'     => 'Install',
			'maintain'    => 'Maintain',
			'air-quality' => 'Air quality',
			'electrical'  => 'Electrical',
		);
	}

	/**
	 * Every service. `excerpt` is the one-line card text.
	 *
	 * @return array<int, array{title: string, slug: string, category: string, type: string, excerpt: string}>
	 */
	public static function services(): array {
		return array(
			array(
				'title'    => 'Boiler Repair',
				'slug'     => 'boiler-repair',
				'category' => 'heating',
				'type'     => 'repair',
				'excerpt'  => 'No heat, banging pipes, or a pilot that won\'t stay lit. We find the fault and fix it, usually on the first visit.',
			),
			array(
				'title'    => 'Boiler Replacement',
				'slug'     => 'boiler-replacement',
				'category' => 'heating',
				'type'     => 'replace',
				'excerpt'  => 'When repairs stop making sense, we size and install a new boiler and haul the old one away.',
			),
			array(
				'title'    => 'Boiler Maintenance',
				'slug'     => 'boiler-maintenance',
				'category' => 'heating',
				'type'     => 'maintain',
				'excerpt'  => 'A yearly check of pressure, valves, and burners so the boiler starts on the first cold night.',
			),
			array(
				'title'    => 'Furnace Repair',
				'slug'     => 'furnace-repair',
				'category' => 'heating',
				'type'     => 'repair',
				'excerpt'  => 'Cold air from the vents, short cycling, or a burning smell. We test the igniter, flame sensor, and blower.',
			),
			array(
				'title'    => 'Furnace Replacement',
				'slug'     => 'furnace-replacement',
				'category' => 'heating',
				'type'     => 'replace',
				'excerpt'  => 'Old furnace on its last legs? We quote a replacement matched to your ductwork and the size of your home.',
			),
			array(
				'title'    => 'Furnace Installation',
				'slug'     => 'furnace-installation',
				'category' => 'heating',
				'type'     => 'install',
				'excerpt'  => 'New construction or an addition. We install gas and electric furnaces to code and show you the controls.',
			),
			array(
				'title'    => 'Furnace Maintenance',
				'slug'     => 'furnace-maintenance',
				'category' => 'heating',
				'type'     => 'maintain',
				'excerpt'  => 'We clean the burners, check the heat exchanger for cracks, and test for carbon monoxide before winter.',
			),
			array(
				'title'    => 'Heat Pump Repair',
				'slug'     => 'heat-pump-repair',
				'category' => 'heating',
				'type'     => 'repair',
				'excerpt'  => 'Ice on the outdoor unit or no heat in defrost mode. We check reversing valves, sensors, and refrigerant.',
			),
			array(
				'title'    => 'Heat Pump Installation',
				'slug'     => 'heat-pump-installation',
				'category' => 'heating',
				'type'     => 'install',
				'excerpt'  => 'One system that heats and cools. Heat pumps suit mild Texas winters and can lower your electric bill.',
			),
			array(
				'title'    => 'Heat Pump Maintenance',
				'slug'     => 'heat-pump-maintenance',
				'category' => 'heating',
				'type'     => 'maintain',
				'excerpt'  => 'Spring and fall visits to clean coils, check refrigerant, and test both heating and cooling modes.',
			),
			array(
				'title'    => 'AC Repair',
				'slug'     => 'ac-repair',
				'category' => 'cooling',
				'type'     => 'repair',
				'excerpt'  => 'Warm air, water by the air handler, or a unit that won\'t start. Common parts are on the truck, so most repairs happen same day.',
			),
			array(
				'title'    => 'AC Replacement',
				'slug'     => 'ac-replacement',
				'category' => 'cooling',
				'type'     => 'replace',
				'excerpt'  => 'If your system is 12 to 15 years old and needs a major part, we\'ll show you repair and replace prices side by side.',
			),
			array(
				'title'    => 'AC Installation',
				'slug'     => 'ac-installation',
				'category' => 'cooling',
				'type'     => 'install',
				'excerpt'  => 'Load calculation first, then the right size system. An oversized unit cools fast and leaves the house clammy.',
			),
			array(
				'title'    => 'AC Maintenance',
				'slug'     => 'ac-maintenance',
				'category' => 'cooling',
				'type'     => 'maintain',
				'excerpt'  => 'We clean the condenser coil, clear the drain line, and check refrigerant and capacitors before summer.',
			),
			array(
				'title'    => 'Ductless AC Repair',
				'slug'     => 'ductless-ac-repair',
				'category' => 'cooling',
				'type'     => 'repair',
				'excerpt'  => 'Mini-split not cooling or flashing an error code. We service wall-mounted and ceiling cassette units.',
			),
			array(
				'title'    => 'Ductless AC Installation',
				'slug'     => 'ductless-ac-installation',
				'category' => 'cooling',
				'type'     => 'install',
				'excerpt'  => 'Cooling for a garage, converted attic, or new room without running new ductwork.',
			),
			array(
				'title'    => 'Air Filtration',
				'slug'     => 'air-filtration',
				'category' => 'cooling',
				'type'     => 'air-quality',
				'excerpt'  => 'Media filter cabinets that catch dust, pollen, and pet hair before they reach your vents.',
			),
			array(
				'title'    => 'Air Purification',
				'slug'     => 'air-purification',
				'category' => 'cooling',
				'type'     => 'air-quality',
				'excerpt'  => 'In-duct purifiers that cut down on odors, smoke, and airborne germs in every room.',
			),
			array(
				'title'    => 'Whole House Fans',
				'slug'     => 'whole-house-fans',
				'category' => 'cooling',
				'type'     => 'air-quality',
				'excerpt'  => 'On cool spring and fall evenings, pull fresh air through the house and push hot attic air out with the AC off.',
			),
			array(
				'title'    => 'Whole Home Humidifiers',
				'slug'     => 'whole-home-humidifiers',
				'category' => 'cooling',
				'type'     => 'air-quality',
				'excerpt'  => 'Dry winter air cracks wood floors and irritates sinuses. A humidifier on your furnace keeps levels steady.',
			),
			array(
				'title'    => 'Circuit Breakers',
				'slug'     => 'circuit-breakers',
				'category' => 'electrical',
				'type'     => 'electrical',
				'excerpt'  => 'Breakers that trip when the AC kicks on, or worn breakers in an old panel. We replace and test them.',
			),
			array(
				'title'    => 'Electrical Installation',
				'slug'     => 'electrical-installation',
				'category' => 'electrical',
				'type'     => 'electrical',
				'excerpt'  => 'Dedicated circuits, disconnects, and wiring for new HVAC equipment, done as part of your installation.',
			),
		);
	}
}
