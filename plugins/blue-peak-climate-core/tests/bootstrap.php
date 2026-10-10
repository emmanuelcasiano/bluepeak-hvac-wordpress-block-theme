<?php
/**
 * PHPUnit bootstrap. WordPress is NOT loaded: WP functions are mocked per test with Brain Monkey,
 * so tests run from any terminal without Local's database.
 *
 * @package BluePeakClimate\Core
 */

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// Plugin files exit early without ABSPATH.
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

// Constants WordPress defines that the plugin code uses.
if ( ! defined( 'OBJECT' ) ) {
	define( 'OBJECT', 'OBJECT' );
}
