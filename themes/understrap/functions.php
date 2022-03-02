<?php

/**
 * UnderStrap functions and definitions
 *
 * @package understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$understrap_includes = array(
	'/theme-settings.php', // Initialize theme default settings.
	'/setup.php', // Theme setup and custom theme supports.
	'/widgets.php', // Register widget area.
	'/enqueue.php', // Enqueue scripts and styles.
	'/template-tags.php', // Custom template tags for this theme.
	'/pagination.php', // Custom pagination for this theme.
	'/hooks.php', // Custom hooks.
	'/extras.php', // Custom functions that act independently of the theme templates.
	'/customizer.php', // Customizer additions.
	'/custom-comments.php', // Custom Comments file.
	'/jetpack.php', // Load Jetpack compatibility file.
	'/class-wp-bootstrap-navwalker.php', // Load custom WordPress nav walker. Trying to get deeper navigation? Check out: https://github.com/understrap/understrap/issues/567
	'/class-custom-navwalker.php',
	'/editor.php', // Load Editor functions.
	'/deprecated.php', // Load deprecated functions.
	'/woocommerce.php' // Woocommerce Functions
);

foreach ($understrap_includes as $file) {
	require_once get_template_directory() . '/inc' . $file;
}
