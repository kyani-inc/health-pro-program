<?php
namespace TotalThemeCore\Shortcodes;

defined( 'ABSPATH' ) || exit;

final class Shortcode_Line_Break {

	public function __construct() {

		if ( ! shortcode_exists( 'br' ) ) {
			add_shortcode( 'br', __CLASS__ . '::output' );
		}

	}

	public static function output( $atts, $content = '' ) {
		return '<br>';
	}

}