<?php
namespace TotalThemeCore\Shortcodes;

defined( 'ABSPATH' ) || exit;

final class Shortcode_Highlight {

	public function __construct() {

		if ( ! shortcode_exists( 'highlight' ) ) {
			add_shortcode( 'highlight', __CLASS__ . '::output' );
		}

	}

	public static function output( $atts, $content = '' ) {

		$atts = shortcode_atts( array(
			'color'  => '',
			'height' => '',
		), $atts, 'highlight' );

		$inline_style = vcex_inline_style( array(
			'background' => $atts['color'],
			'height'     => $atts['height'],
		), true );

		return '<span class="wpex-highlight">' . wp_kses_post( $content ) . '<span class="wpex-after wpex-bg-accent"' . $inline_style . '></span></span>';

	}

}