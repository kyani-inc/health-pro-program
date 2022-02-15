<?php
namespace TotalThemeCore\Shortcodes;

defined( 'ABSPATH' ) || exit;

final class Shortcode_Home_Url {

	public function __construct() {

		if ( ! shortcode_exists( 'home_url' ) ) {
			add_shortcode( 'home_url', __CLASS__ . '::output' );
		}

	}

	public static function output( $atts ) {
		$atts = shortcode_atts( array(
			'path'   => '/',
			'scheme' => null,
		), $atts, 'home_url' );
		return esc_url( home_url( $atts['path'], $atts['scheme'] ) );
	}

}