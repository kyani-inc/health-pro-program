<?php
/**
 * vcex_form_shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_form_shortcode', $atts ) ) {
	return;
}

// Get and extract shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_form_shortcode', $atts, $this );

if ( ! empty( $atts['cf7_id'] ) ) {
	$content = '[contact-form-7 id="' . intval( $atts['cf7_id'] ) . '"]';
}

// Return if no content (shortcode needed).
if ( empty( $content ) ) {
	return;
}

// Add classes.
$shortcode_class = array(
	'vcex-module',
	'vcex-form-shortcode',
	'wpex-form',
	'wpex-m-auto',
	'wpex-max-w-100',
);

if ( ! empty( $atts['style'] ) ) {

	if ( 'white' == $atts['style'] ) {
		$shortcode_class[] = 'light-form';
	} else {
		$shortcode_class[] = 'wpex-form-' . sanitize_html_class( $atts['style'] );
	}

}

if ( vcex_validate_boolean( $atts['full_width'] ) ) {
	$shortcode_class[] = 'full-width-input';
}

$extra_classes = vcex_get_shortcode_extra_classes( $atts, 'vcex_form_shortcode' );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, 'vcex_form_shortcode', $atts );

// Inline CSS.
$shortcode_style = vcex_inline_style( array(
	'color'              => $atts['color'],
	'background_color'   => $atts['background_color'],
	'border_color'       => $atts['border_color'],
	'font_size'          => $atts['font_size'],
	'width'              => $atts['width'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
) );

// Output.
echo '<div class="' . esc_attr( $shortcode_class ) . '"'. $shortcode_style .'>' . do_shortcode( $content ) . '</div>';