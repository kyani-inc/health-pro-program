<?php
namespace TotalThemeCore\Shortcodes;

defined( 'ABSPATH' ) || exit;

final class Shortcode_Searchform {

	public function __construct() {

		if ( ! shortcode_exists( 'searchform' ) ) {
			add_shortcode( 'searchform', __CLASS__ . '::output' );
		}

	}

	public static function output( $atts, $content = '' ) {

		if ( is_admin() && ! wp_doing_ajax() ) {
			return;
		}

		ob_start();
			get_search_form();
		return ob_get_clean();

	}

}