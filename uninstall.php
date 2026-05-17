<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * When the plugin is deleted from the WordPress admin dashboard, this file
 * is executed to clean up any data stored in the database.
 *
 * @since   1.0.6
 * @package ANPC_Display
 */

// If uninstall not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

// Delete plugin options from the wp_options table.
delete_option('anpc_display_option_name');