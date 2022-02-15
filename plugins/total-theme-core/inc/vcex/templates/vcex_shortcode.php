<?php
/**
 * vcex_shortcode shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_shortcode', $atts ) ) {
	return;
}

if ( empty( $content ) ) {
	return;
}

$atts = vcex_shortcode_atts( 'vcex_shortcode', $atts, $this );

$shortcode_class = array(
	'vcex-shortcode',
	'wpex-clr',
);

$extra_classes = vcex_get_shortcode_extra_classes( $atts, 'vcex_shortcode' );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, 'vcex_shortcode', $atts );

$shortcode_style = vcex_inline_style( array(
	'animation_delay' => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
) );

// Echo shortcode
echo '<div class="' . esc_attr( $shortcode_class ) . '"' . $shortcode_style . '>' . do_shortcode( wp_kses_post( $content ) ) . '</div>';