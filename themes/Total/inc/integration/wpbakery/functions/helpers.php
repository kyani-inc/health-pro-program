<?php
/**
 * WPBakery helper functions.
 *
 * @package TotalTheme
 * @subpackage WPBakery
 * @version 5.1.2
 */

defined( 'ABSPATH' ) || exit;

/**
 * Helper function runs the VCEX_Parse_Row_Atts class.
 */
function vcex_parse_row_atts( $atts ) {
	$parse = new VCEX_Parse_Row_Atts( $atts );
	return $parse->return_atts();
}

/**
 * Conditional check to see if we should be parsing deprecated css options.
 */
function wpex_vc_maybe_parse_deprecated_css_options( $element = '' ) {
	return (bool) apply_filters( 'wpex_vc_parse_deprecated_css_options', true, $element );
}

/**
 * Fallback fix to prevent JS errors in the editor.
 *
 * @todo is this still needed?
 */
if ( ! function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
	function vc_icon_element_fonts_enqueue( $font ) {
		switch ( $font ) {
			case 'openiconic':
				wp_enqueue_style( 'vc_openiconic' );
				break;
			case 'typicons':
				wp_enqueue_style( 'vc_typicons' );
				break;
			case 'entypo':
				wp_enqueue_style( 'vc_entypo' );
				break;
			case 'linecons':
				wp_enqueue_style( 'vc_linecons' );
				break;
			case 'monosocial':
				wp_enqueue_style( 'vc_monosocialiconsfont' );
				break;
			default:
				do_action( 'vc_enqueue_font_icon_element', $font ); // hook to custom do enqueue style
				break;
		}

	}

}