<?php
namespace TotalThemeCore\Shortcodes;

defined( 'ABSPATH' ) || exit;

final class Shortcode_Post_Author {

	public function __construct() {

		if ( ! shortcode_exists( 'post_author' ) ) {
			add_shortcode( 'post_author', __CLASS__ . '::output' );
		}

	}

	public static function output( $atts, $content = '' ) {
		global $post;
		if ( $post ) {
			return get_the_author_meta( 'nicename', $post->post_author );
		}
	}

}