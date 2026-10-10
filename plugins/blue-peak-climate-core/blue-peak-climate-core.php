<?php
/**
 * Plugin Name:       Blue Peak Climate Core
 * Description:       Site-specific functionality for Blue Peak Climate: post types, blocks and integrations. Keeps features working if the theme changes.
 * Version:           0.1.0
 * Requires at least: 6.8
 * Requires PHP:      7.4
 * Author:            Your Name
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       blue-peak-climate-core
 * Domain Path:       /languages
 *
 * @package BluePeakClimate\Core
 */

defined( 'ABSPATH' ) || exit;

define( 'BPCH_CORE_VERSION', '0.1.0' );
define( 'BPCH_CORE_FILE', __FILE__ );
define( 'BPCH_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'BPCH_CORE_URL', plugin_dir_url( __FILE__ ) );

// PSR-4 autoloader for src/. Composer is only used for dev tools, so the live site never needs vendor/.
spl_autoload_register(
	static function ( $class_name ) {
		$prefix = 'BluePeakClimate\\Core\\';
		if ( 0 !== strpos( $class_name, $prefix ) ) {
			return;
		}
		$file = BPCH_CORE_DIR . 'src/' . str_replace( '\\', '/', substr( $class_name, strlen( $prefix ) ) ) . '.php';
		if ( is_readable( $file ) ) {
			require $file;
		}
	}
);

( new BluePeakClimate\Core\Plugin() )->register();
