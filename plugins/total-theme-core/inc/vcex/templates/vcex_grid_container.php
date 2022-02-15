<?php
/**
 * Grid Container shortcode template.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3
 */

defined( 'ABSPATH' ) || exit;

$atts = vcex_shortcode_atts( 'vcex_grid_container', $atts, get_class() );

$output = '';
$breakpoints = array( 'sm', 'md', 'lg', 'xl' );

$classes = array(
	'vcex-grid-container',
	'vcex-module',
	'wpex-grid',
	'wpex-gap-20',
);

if ( ! empty( $atts['width'] ) ) {
	$classes[] = 'wpex-mx-auto';
}

$classes[] = 'wpex-grid-cols-' . sanitize_html_class( absint( $atts['columns'] ) );

foreach( $breakpoints as $bk ) {
	if ( ! empty( $atts['columns_' . $bk] ) && is_numeric( $atts['columns_' . $bk] ) ) {
		$classes[] = 'wpex-' . $bk . '-grid-cols-' . sanitize_html_class( absint( $atts['columns_' . $bk] ) );
	}
}

if ( $align_items_class = vcex_parse_align_items_class( $atts['align_items'] ) ) {
	$classes[] = $align_items_class;
}

if ( $atts['justify_items'] && is_string( $atts['justify_items'] ) ) {
	$classes[] = 'wpex-justify-items-' . sanitize_html_class( $atts['justify_items'] );
}

if ( $shadow_class = vcex_parse_shadow_class( $atts['shadow'] ) ) {
	$classes[] = $shadow_class;
}

if ( ! empty( $atts['el_class'] ) ) {
	$classes[] = vcex_get_extra_class( $atts['el_class'] );
}

if ( $css_class = vcex_vc_shortcode_custom_css_class( $atts['css'] ) ) {
	$classes[] = $css_class;
}

$style = '';

$inline_style = vcex_inline_style( array(
	'gap' => $atts['gap'],
	'max_width' => $atts['width'],
), true );

$output .= '<div class="' . esc_attr( implode( ' ', $classes ) ) . '"' . $inline_style . '>';

	$output .= do_shortcode( wp_kses_post( $content ) );

$output .= '</div>';

echo $output; // @codingStandardsIgnoreLine