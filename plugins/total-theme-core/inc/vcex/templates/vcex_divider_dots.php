<?php
/**
 * vcex_divider_dots shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_divider_dots', $atts ) ) {
	return;
}

// Get and extract shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_divider_dots', $atts, $this );
extract( $atts );

// Set defaults.
$count   = $count ?: '3';
$align   = $align ?: 'center';
$spacing = $spacing ?: '10';

// Wrap classes.
$shortcode_class = array(
	'vcex-module',
	'vcex-divider-dots',
	'wpex-mr-auto',
	'wpex-ml-auto',
	'wpex-last-mr-0',
);

if ( $bottom_margin_class = vcex_sanitize_margin_class( $bottom_margin, 'wpex-mb-' ) ) {
	$shortcode_class[] = $bottom_margin_class;
}

if ( $css_animation_class = vcex_get_css_animation( $css_animation ) ) {
	$shortcode_class[] = $css_animation_class;
}

if ( $align ) {
	$shortcode_class[] = 'text' . sanitize_html_class( $align );
}

if ( $visibility_class = vcex_parse_visibility_class( $visibility ) ) {
	$shortcode_class[] = $visibility_class;
}

if ( $el_class = vcex_get_extra_class( $el_class ) ) {
	$shortcode_class[] = $el_class;
}

$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, 'vcex_divider_dots', $atts );

// Define vars.
$output = $shortcode_style = $span_style = '';

// Wrap style.
$shortcode_style = vcex_inline_style( array(
	'padding'            => $margin,
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
) );

// Span class.
$span_class = array(
	'wpex-inline-block',
	'wpex-round',
	'wpex-bg-accent',
);
$span_class[] = 'wpex-mr-' . sanitize_html_class( absint( $spacing ) );
$span_class_escaped = esc_attr( implode( ' ', $span_class ) );

// Span style
$span_style = vcex_inline_style( array(
	'height'     => $size,
	'width'      => $size,
	'background' => $color,
) );

// Return output.
$output .= '<div class="' . esc_attr( $shortcode_class ) . '"' . $shortcode_style . '>';
	for ( $k = 0; $k < $count; $k++ ) {
		$output .= '<span class="' . $span_class_escaped . '"' . $span_style . '></span>';
	}
$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;