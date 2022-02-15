<?php
namespace TotalThemeCore\Shortcodes;

defined( 'ABSPATH' ) || exit;

final class Shortcode_Polylang_Switcher {

	public function __construct() {

		if ( ! shortcode_exists( 'polylang_switcher' ) ) {
			add_shortcode( 'polylang_switcher', __CLASS__ . '::output' );
		}

	}

	public static function output( $atts, $content = '' ) {

		if ( ! function_exists( 'pll_the_languages' ) ) {
			return;
		}

		extract( shortcode_atts( array(
			'dropdown'               => false,
			'show_flags'             => true,
			'show_names'             => false,
			'classes'                => '',
			'hide_if_empty'          => true,
			'force_home'             => false,
			'hide_if_no_translation' => false,
			'hide_current'           => false,
			'post_id'                => null,
			'raw'                    => false,
			'echo'                   => 0
		), $atts ) );

		$output = '';

		$dropdown   = 'true' == $dropdown ? true : false;
		$show_flags = 'true' == $show_flags ? true : false;
		$show_names = 'true' == $show_names ? true : false;

		if ( $dropdown ) {
			$show_flags = $show_names = false;
		}

		$classes = 'polylang-switcher-shortcode wpex-clr';
		if ( $show_names && ! $dropdown ) {
			$classes .= ' flags-and-names';
		}

		if ( ! $dropdown ) {
			$output .= '<ul class="' . esc_attr( $classes ) . '">';
		}

		$output .= pll_the_languages( array(
			'dropdown'               => $dropdown,
			'show_flags'             => $show_flags,
			'show_names'             => $show_names,
			'hide_if_empty'          => $hide_if_empty,
			'force_home'             => $force_home,
			'hide_if_no_translation' => $hide_if_no_translation,
			'hide_current'           => $hide_current,
			'post_id'                => $post_id,
			'raw'                    => $raw,
			'echo'                   => $echo,
		) );

		if ( ! $dropdown ) {
			$output .= '</ul>';
		}

		return $output;

	}

}