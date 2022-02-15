<?php
namespace TotalThemeCore\Shortcodes;

defined( 'ABSPATH' ) || exit;

final class Shortcode_Post_Date_Modified {

	public function __construct() {

		if ( ! shortcode_exists( 'post_modified_date' ) ) {
			add_shortcode( 'post_modified_date', __CLASS__ . '::output' );
		}

	}

	public static function output( $atts, $content = '' ) {
		return get_the_modified_date();
	}

}