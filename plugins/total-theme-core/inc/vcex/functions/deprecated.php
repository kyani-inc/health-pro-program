<?php
/**
 * Deprecated functions.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

function vcex_ilightbox_skins() {
	return array();
}

function vcex_dummy_image_url() {
	return;
}

function vcex_dummy_image() {
	return;
}

function vcex_image_rendering() {
	return;
}

function vcex_inline_js() {
	return;
}

function vcex_parse_old_design_js() {
	return;
}

function vcex_function_needed_notice() {
	return;
}

function vcex_enqueue_navbar_filter_scripts() {
	return;
}

function vcex_sanitize_data() {
	_deprecated_function( 'vcex_sanitize_data', '3.0.0', 'wpex_sanitize_data' );
}

function vcex_get_border_radius_class( $val ) {
	return vcex_parse_border_radius_class( $val );
}

function vcex_vc_map_get_attributes( $shortcode = '', $atts = '', $class = '' ) {
	return vcex_shortcode_atts( $shortcode, $atts, $class );
}