<?php
/**
 * Runs when the plugin is deleted from WP Admin (not on deactivation).
 * Remove everything the plugin stored: options, transients, custom tables, scheduled events.
 *
 * @package BluePeakClimate\Core
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

// Site Settings (phone, email, hours, social links, ZIP → office table).
delete_option( 'bpch_core_settings' );

/*
 * Add cleanup here as features store data, e.g.:
 *   wp_clear_scheduled_hook( 'bpch_core_daily_sync' );
 */
