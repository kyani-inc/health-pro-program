<?php
/**
 * vcex_post_comments shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.1
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_post_comments', $atts ) ) {
	return;
}

$atts = vcex_shortcode_atts( 'vcex_post_comments', $atts, $this );

$shortcode_class = array(
	'vcex-comments',
);

if ( ! empty( $atts['el_class'] ) ) {
	$shortcode_class[] = vcex_get_extra_class( $atts['el_class'] );
}

if ( $atts['bottom_margin'] ) {
	$shortcode_class[] = vcex_sanitize_margin_class( $atts['bottom_margin'], 'wpex-mb-' );
}

if ( ! empty( $atts['visibility'] ) ) {
	$shortcode_class[] = esc_attr( $atts['visibility'] );
}

if ( empty( $atts['show_heading'] ) || 'false' == $atts['show_heading'] ) {
	$shortcode_class[] = 'vcex-comments-hide-heading';
}

if ( ! empty( $atts['max_width'] ) ) {

	switch ( $atts['align'] ) {
		case 'left':
			$shortcode_class[] = 'wpex-mr-auto';
			break;
		case 'right':
			$shortcode_class[] = 'wpex-ml-auto';
			break;
		case 'center':
		default:
			$shortcode_class[] = 'wpex-mx-auto';
			break;
	}

}

$shortcode_class = vcex_parse_shortcode_classes( implode( ' ', $shortcode_class ), 'vcex_post_comments', $atts );

$shortcode_style = vcex_inline_style( array(
	'max_width' => $atts['max_width'],
) );

$output = '<div class="' . esc_attr( $shortcode_class ) . '"' . $shortcode_style . '>';

	ob_start();
		comments_template();
	$output .= ob_get_clean();

$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;