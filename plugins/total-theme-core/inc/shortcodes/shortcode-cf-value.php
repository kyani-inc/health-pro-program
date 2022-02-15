<?php
namespace TotalThemeCore\Shortcodes;

defined( 'ABSPATH' ) || exit;

final class Shortcode_Cf_Value {

	public function __construct() {

		if ( ! shortcode_exists( 'cf_value' ) ) {
			add_shortcode( 'cf_value', __CLASS__ . '::output' );
		}

	}

	public static function output( $atts, $content = '' ) {

		if ( is_admin() && ! wp_doing_ajax() ) {
			return;
		}

		$atts = shortcode_atts( array(
			'name'        => '',
			'in_template' => false,
		), $atts );

		if ( ! empty( $atts['name'] ) ) {
			$post_id = empty( $atts['in_template'] ) ? get_the_ID() : vcex_get_the_ID();
			return get_post_meta( $post_id, $atts['name'], true );
		}

	}

}