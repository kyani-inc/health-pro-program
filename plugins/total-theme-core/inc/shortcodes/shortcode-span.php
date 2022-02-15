<?php
namespace TotalThemeCore\Shortcodes;

defined( 'ABSPATH' ) || exit;

final class Shortcode_Span {

	public function __construct() {

		if ( ! shortcode_exists( 'span' ) ) {
			add_shortcode( 'span', __CLASS__ . '::output' );
		}

	}

	public static function output( $atts, $content = '' ) {

		if ( is_admin() && ! wp_doing_ajax() ) {
			return;
		}

		$atts = shortcode_atts( array(
			'class'  => '',
			'text'   => '',
		), $atts, 'span' );

		$class = 'wpex-span ' . $atts['class'];

		if ( ! empty( $atts['text'] ) ) {
			$content = $atts['text'];
		}

		return '<span class="' . esc_attr( $class ) . '">' . wp_kses_post( $content ) . '</span>';

	}

}