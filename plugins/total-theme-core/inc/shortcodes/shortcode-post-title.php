<?php
namespace TotalThemeCore\Shortcodes;

defined( 'ABSPATH' ) || exit;

final class Shortcode_Post_Title {

	public function __construct() {

		if ( ! shortcode_exists( 'post_title' ) ) {
			add_shortcode( 'post_title', __CLASS__ . '::output' );
		}

	}

	public static function output( $atts, $content = '' ) {
		return get_the_title();
	}

}