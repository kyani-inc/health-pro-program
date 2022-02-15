<?php
/**
 * Toggle Group.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.1
 */

defined( 'ABSPATH' ) || exit;

if ( ! $content ) {
	return;
}

$atts = vcex_shortcode_atts( 'vcex_toggle_group', $atts, get_class() );

$output = '';

$shortcode_class = array(
	'vcex-toggle-group',
	'vcex-module',
	'wpex-mx-auto',
);

if ( $atts['style'] && 'none' !== $atts['style'] ) {
	$shortcode_class[] = 'vcex-toggle-group--' . sanitize_html_class( $atts['style'] );
}

$extra_classes = vcex_get_shortcode_extra_classes( $atts, 'vcex_toggle_group' );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

$shortcode_style = vcex_inline_style( array(
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
	'max_width'          => $atts['max_width'],
) );

$inline_css = '';
$unique_class = vcex_element_unique_classname();

if ( 'w-borders' === $atts['style'] ) {

	if ( ! empty( $atts['border_color'] ) ) {
		$inline_css .= '.' . $unique_class . ' .vcex-toggle{border-color:' . esc_attr( vcex_parse_color( $atts['border_color'] ) ) . ' !important;}';
	}

	if ( ! empty( $atts['border_spacing'] ) ) {
		$border_spacing_escaped = esc_attr( absint( $atts['border_spacing'] ) ) . 'px';
		$inline_css .= '.' . $unique_class . ' .vcex-toggle__trigger{padding-top:' . $border_spacing_escaped . ' !important;padding-bottom:' . $border_spacing_escaped . ' !important;}';
		$inline_css .= '.' . $unique_class . ' .vcex-toggle__content{margin-bottom:' . esc_attr( absint( $atts['border_spacing'] ) + 10 ) . 'px;}';
	}

}

if ( $inline_css ) {
	$shortcode_class[] = $unique_class;
	$output .= '<style>' . esc_attr( $inline_css ) . '</style>';
}

// Parse shortcode class.
$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, 'vcex_toggle_group', $atts );

// Output shortcode html.
$output .= '<div' . vcex_get_unique_id( $atts['unique_id'] ) . ' class="' . esc_attr( $shortcode_class ) . '"' . $shortcode_style . '>';

	$output .= do_shortcode( wp_kses_post( $content ) ); // wp_kses_post doesn't allow SVG's so parse before do_shortcode.

$output .= '</div>';

echo $output; // @codingStandardsIgnoreLine