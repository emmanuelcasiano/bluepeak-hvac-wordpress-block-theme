<?php
/**
 * The five symptoms of the "What's your system doing?" picker.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Symptoms;

defined( 'ABSPATH' ) || exit;

/**
 * The copy comes from the prototype. It lives in code, not in the database: it is fixed marketing text that changes
 * rarely, it is translatable, and a client cannot break the tab structure by editing it. To change it, edit this file.
 */
final class Catalog {

	/**
	 * The symptoms in tab order. Each has:
	 * id (used in element ids and the store), icon (see Support\Icons), label (the tab), badge, title, causes (list),
	 * plan ("What we'll do"), tip ("Before we arrive"), and cta (the primary button's text).
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public static function all(): array {
		return array(
			array(
				'id'     => 'warm-air',
				'icon'   => 'thermometer-sun',
				'label'  => __( 'Warm air from the vents', 'blue-peak-climate-core' ),
				'badge'  => __( 'Usually same-day', 'blue-peak-climate-core' ),
				'title'  => __( "Your AC runs, but the air isn't cold", 'blue-peak-climate-core' ),
				'causes' => array(
					__( 'A failed capacitor or contactor in the outdoor unit', 'blue-peak-climate-core' ),
					__( 'Low refrigerant from a leak', 'blue-peak-climate-core' ),
					__( 'A condenser coil caked with dirt and cottonwood', 'blue-peak-climate-core' ),
				),
				'plan'   => __( "We test the capacitor and contactor, check refrigerant pressures, and clean the coil if it's blocked. Most of these parts are already on the truck.", 'blue-peak-climate-core' ),
				'tip'    => __( "Before we arrive: check that the thermostat is set to Cool and the filter isn't clogged.", 'blue-peak-climate-core' ),
				'cta'    => __( 'Book AC repair', 'blue-peak-climate-core' ),
			),
			array(
				'id'     => 'no-heat',
				'icon'   => 'thermometer-snowflake',
				'label'  => __( 'No heat', 'blue-peak-climate-core' ),
				'badge'  => __( 'Priority in a freeze', 'blue-peak-climate-core' ),
				'title'  => __( "The furnace or heat pump won't heat", 'blue-peak-climate-core' ),
				'causes' => array(
					__( 'A worn igniter or dirty flame sensor', 'blue-peak-climate-core' ),
					__( 'A tripped limit switch from a clogged filter', 'blue-peak-climate-core' ),
					__( 'A heat pump stuck in defrost or a failed reversing valve', 'blue-peak-climate-core' ),
				),
				'plan'   => __( 'We check the ignition sequence, clean or replace the flame sensor, and test the safety switches before anything else.', 'blue-peak-climate-core' ),
				'tip'    => __( 'Before we arrive: swap the filter and make sure the switch on the side of the furnace is on.', 'blue-peak-climate-core' ),
				'cta'    => __( 'Book heating repair', 'blue-peak-climate-core' ),
			),
			array(
				'id'     => 'breaker-trips',
				'icon'   => 'zap',
				'label'  => __( 'Breaker trips', 'blue-peak-climate-core' ),
				'badge'  => __( 'Electrical', 'blue-peak-climate-core' ),
				'title'  => __( 'The breaker trips when the AC kicks on', 'blue-peak-climate-core' ),
				'causes' => array(
					__( "A worn breaker that can't hold the load", 'blue-peak-climate-core' ),
					__( 'A compressor pulling too many amps on startup', 'blue-peak-climate-core' ),
					__( 'Loose or burnt wiring at the disconnect', 'blue-peak-climate-core' ),
				),
				'plan'   => __( 'We measure the startup draw, inspect the wiring and disconnect, and replace the breaker or add a hard-start kit if the compressor needs help.', 'blue-peak-climate-core' ),
				'tip'    => __( "Before we arrive: don't keep resetting it. Leave the AC off until we look at it.", 'blue-peak-climate-core' ),
				'cta'    => __( 'Book electrical service', 'blue-peak-climate-core' ),
			),
			array(
				'id'     => 'water-leak',
				'icon'   => 'droplets',
				'label'  => __( 'Water leaking inside', 'blue-peak-climate-core' ),
				'badge'  => __( 'Stop the damage', 'blue-peak-climate-core' ),
				'title'  => __( "There's water near the indoor unit", 'blue-peak-climate-core' ),
				'causes' => array(
					__( 'A clogged condensate drain line', 'blue-peak-climate-core' ),
					__( 'A cracked or rusted drain pan', 'blue-peak-climate-core' ),
					__( 'A frozen evaporator coil that is melting', 'blue-peak-climate-core' ),
				),
				'plan'   => __( 'We clear the drain line, check the pan and float switch, and find out why the coil froze if it did.', 'blue-peak-climate-core' ),
				'tip'    => __( 'Before we arrive: turn the system off at the thermostat and put a towel or pan under the leak.', 'blue-peak-climate-core' ),
				'cta'    => __( 'Book AC repair', 'blue-peak-climate-core' ),
			),
			array(
				'id'     => 'noises',
				'icon'   => 'volume-2',
				'label'  => __( 'Loud or strange noises', 'blue-peak-climate-core' ),
				'badge'  => __( 'Catch it early', 'blue-peak-climate-core' ),
				'title'  => __( 'The system bangs, squeals, or rattles', 'blue-peak-climate-core' ),
				'causes' => array(
					__( 'A blower motor with worn bearings', 'blue-peak-climate-core' ),
					__( 'A loose panel or fan blade', 'blue-peak-climate-core' ),
					__( 'Delayed ignition in the furnace', 'blue-peak-climate-core' ),
				),
				'plan'   => __( "We find the source, tighten or replace what's loose, and tell you whether the motor has years left or weeks.", 'blue-peak-climate-core' ),
				'tip'    => __( 'Before we arrive: if the furnace makes a loud bang when it lights, shut it off and call us.', 'blue-peak-climate-core' ),
				'cta'    => __( 'Book a diagnostic visit', 'blue-peak-climate-core' ),
			),
		);
	}
}
