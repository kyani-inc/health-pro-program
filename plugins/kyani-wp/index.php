<?php
/*
 * Plugin Name: Kyäni WP
 * Description: Gives the Ability to use reps as well as other commonly used functions and shortcodes on sites
 * Version: 1.0
 * Author: Gage Bateman
 * Author URI: https://kyani.com
 */

$kyani_plugin_includes = array(
	'/class-rep.php', //Defines and grabs rep info
	'/product-link-generator.php', //Generates replicated product links for sites that are selling on the BDT Shop
	'/replicated-display/display.php', //Creates the replicated display dropdown that shows rep information
	'/customizer.php', //Custom Configurations for site

);

foreach ($kyani_plugin_includes as $file) {
	require_once plugin_dir_path(__FILE__) . 'inc' . $file;
}
