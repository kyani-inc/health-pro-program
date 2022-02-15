<?php
/**
 * vcex_spacing shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_spacing', $atts ) ) {
	return;
}

// Get and extract shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_spacing', $atts, $this );

// Check if spacing is responsive.
$is_responsive = ( ! empty( $atts['responsive'] ) && 'true' === $atts['responsive'] );

// Core class.
$classes = array(
	'vcex-spacing',
	'wpex-w-100',
	'wpex-clear',
);

// Custom Class.
if ( ! empty( $atts['class']  ) ) {
    $classes[] = vcex_get_extra_class( $atts['class'] );
}

// Visiblity Class.
if ( ! empty( $atts['visibility'] ) ) {
    $classes[] = vcex_parse_visibility_class( $atts['visibility'] );
}

// Front-end composer class.
if ( vcex_vc_is_inline() ) {
    $classes[] = 'vc-spacing-shortcode';
}

// Add unique classname.
if ( $is_responsive ) {
	$unique_class = vcex_element_unique_classname();
	$classes[] = sanitize_html_class( $unique_class );
}

// Apply filters.
$classes = vcex_parse_shortcode_classes( implode( ' ', $classes ), 'vcex_spacing', $atts );

if ( $is_responsive ) {
	$shortcode_style = ''; // not used for responsive size.

	$inline_css = '<style>';
		$inline_css .= vcex_responsive_attribute_css( $atts['size_responsive'], $unique_class, 'height' );
	$inline_css .= '</style>';

	echo $inline_css;
} else {

	$size = ! empty( $atts['size'] ) ? $atts['size'] : '30px';

	// Sanitize size.
	if ( is_numeric( $size ) ) {
		$size = floatval( $size ) . 'px';
	} elseif ( false !== strpos( $size, 'px' ) ) {
		$size = floatval( $size ) . 'px';
	} elseif ( false !== strpos( $size, '%' )
		|| false !== strpos( $size, 'em' )
		|| false !== strpos( $size, 'rem' )
		|| false !== strpos( $size, 'vh' )
		|| false !== strpos( $size, 'vmin' )
		|| false !== strpos( $size, 'vmax' )
	) {
		$size = wp_strip_all_tags( $size );
	} elseif ( $size = floatval( $size ) ) {
		$size = wp_strip_all_tags( $size ) . 'px';
	}

	$shortcode_style = ' style="height:' . esc_attr( trim( $size ) ) . ';"';
}

// Echo output.
echo '<div class="' . esc_attr( $classes ) . '"' . $shortcode_style . '></div>';