<?php
/*
 * Plugin Name: Kyani Customization
 * Description: This plugin adds functionality for Replicated Sites and creates the Country Switcher in the navigation menus for each country.
 * Version: 1.0
 * Author: Victor Santiago
 * Author URI: http://kyani.com
 */

$kyani_plugin_includes = array(
	'/class-rep.php',
	'/replicatedDisplay/replicatedDisplay.php',
	'/link-generator.php',
	'/custom-config.php',
	'/rep-url.php'
);

foreach ($kyani_plugin_includes as $file) {
	require_once plugin_dir_path(__FILE__) . 'includes' . $file;
}
