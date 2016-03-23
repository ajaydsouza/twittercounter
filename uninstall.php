<?php
/**
 * Fired when the plugin is uninstalled
 *
 * @package TwitterCounter
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option( 'ald_tc_settings' );

