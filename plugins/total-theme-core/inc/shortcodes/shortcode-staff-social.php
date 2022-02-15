<?php
namespace TotalThemeCore\Shortcodes;

defined( 'ABSPATH' ) || exit;

final class Shortcode_Staff_Social {

	public function __construct() {

		if ( ! shortcode_exists( 'staff_social' ) ) {
			add_shortcode( 'staff_social', __CLASS__ . '::output' );
		}

	}

	public static function output( $atts = array() ) {
		if ( function_exists( 'wpex_get_staff_social' ) ) {
			return wpex_get_staff_social( $atts );
		}
	}

}