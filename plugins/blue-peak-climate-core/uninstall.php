<?php
/**
 * Runs when the plugin is deleted from WP Admin (not on deactivation).
 * Remove everything the plugin stored: options, transients, custom tables, scheduled events.
 *
 * @package BluePeakClimate\Core
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

/*
 * Add cleanup here as features store data, e.g.:
 *   delete_option( 'bpc_core_settings' );
 *   wp_clear_scheduled_hook( 'bpc_core_daily_sync' );
 */
