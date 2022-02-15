<?php
/**
 * WPBakery disable updater.
 *
 * @package TotalTheme
 * @subpackage WPBakery
 * @version 5.1.3
 */

defined( 'ABSPATH' ) || exit;

// Set in theme mode & disable updater.
if ( function_exists( 'vc_set_as_theme' ) ) {
	vc_set_as_theme();
}

// Remove plugin license admin tab.
function wpex_vc_remove_plugin_license_submenu_page(){
	remove_submenu_page( VC_PAGE_MAIN_SLUG, 'vc-updater' );
}
add_action( 'admin_menu', 'wpex_vc_remove_plugin_license_submenu_page', 999 );

// Disable WP auto updates.
function wpex_vc_disable_auto_update_plugin ( $update, $item ) {
	if ( 'js_composer' == $item->slug ) {
		return false;
	}
	return $update;
}
add_filter( 'auto_update_plugin', 'wpex_vc_disable_auto_update_plugin', 10, 2 );

// Disable VC updater.
function wpex_disable_vc_updater() {

	if ( function_exists( 'vc_updater' ) ) {

		remove_filter( 'upgrader_pre_download', array( vc_updater(), 'preUpgradeFilter' ), 10);

		remove_filter( 'pre_set_site_transient_update_plugins', array(
			vc_updater()->updateManager(),
			'check_update'
		) );

		if ( function_exists( 'vc_plugin_name' ) ) {
			remove_action( 'in_plugin_update_message-' . vc_plugin_name(), array( vc_updater(), 'addUpgradeMessageLink' ) );
			// wpex_remove_class_filter( 'in_plugin_update_message-' . vc_plugin_name(), 'Vc_Updating_Manager', 'addUpgradeMessageLink', 10 );
		}

	}

	// Old method pre 5.0
	//wpex_remove_class_filter( 'upgrader_pre_download', 'Vc_Updater', 'preUpgradeFilter', 10, 4 );
	//wpex_remove_class_filter( 'plugins_api', 'Vc_Updating_Manager', 'check_info', 10, 3 );
	//wpex_remove_class_filter( 'pre_set_site_transient_update_plugins', 'Vc_Updating_Manager', 'check_update', 10 );

}
add_action( 'init', 'wpex_disable_vc_updater' );