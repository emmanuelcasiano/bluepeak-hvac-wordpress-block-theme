<?php
/**
 * Plugin bootstrap.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Wires up the plugin's hooks. Each new feature class gets one line in register().
 */
final class Plugin {

	/**
	 * Registers all hooks. Called once from the main plugin file.
	 */
	public function register(): void {
		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'init', array( $this, 'register_blocks' ) );

		( new Settings\SettingsPage() )->register();
		( new Settings\Bindings() )->register();
		( new Services\PostTypes() )->register();
		( new Services\Installer() )->register();
		( new Services\EditScreen() )->register();
		( new Services\CategoryBindings() )->register();
		( new Reviews\PostTypes() )->register();
		( new Reviews\EditScreen() )->register();
		( new Reviews\Rating() )->register();
		( new Offers\PostTypes() )->register();
		( new Offers\Expiry() )->register();
		( new Offers\EditScreen() )->register();
		( new Offers\Bindings() )->register();
		( new Forms\ZipValidation() )->register();
		( new Forms\OfficeRouting() )->register();
		( new Support\ListingOnly(
			array(
				Reviews\PostTypes::POST_TYPE => '/reviews/',
				Offers\PostTypes::POST_TYPE  => '/offers/',
			)
		) )->register();
	}

	/**
	 * Loads translations from languages/. Must run on init or later (WP 6.7+ warns otherwise).
	 */
	public function load_textdomain(): void {
		load_plugin_textdomain( 'blue-peak-climate-core', false, dirname( plugin_basename( BPCH_CORE_FILE ) ) . '/languages' );
	}

	/**
	 * Registers every block compiled into build/ in one call, using the manifest that `npm run build` writes.
	 */
	public function register_blocks(): void {
		$manifest = BPCH_CORE_DIR . 'build/blocks-manifest.php';

		// Not built yet: run `npm install && npm run build` in this plugin.
		if ( ! file_exists( $manifest ) ) {
			return;
		}

		wp_register_block_types_from_metadata_collection( BPCH_CORE_DIR . 'build', $manifest );
	}
}
