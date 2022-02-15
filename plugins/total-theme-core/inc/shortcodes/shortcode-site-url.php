<?php
namespace TotalThemeCore\Shortcodes;

defined( 'ABSPATH' ) || exit;

final class Shortcode_Site_Url {

	public function __construct() {

		if ( ! shortcode_exists( 'site_url' ) ) {
			add_shortcode( 'site_url', __CLASS__ . '::output' );
		}

	}

	public static function output( $atts ) {
		$atts = shortcode_atts( array(
			'path'   => '',
			'scheme' => null,
		), $atts, 'site_url' );
		return esc_url( site_url( $atts['path'], $atts['scheme'] ) );
	}

}