<?php
/**
 * vcex_term_description shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_term_description', $atts ) ) {
	return;
}

$atts = vcex_shortcode_atts( 'vcex_term_description', $atts, $this );

if ( vcex_vc_is_inline() ) {
	$term_description = esc_html( 'Term description placeholder for the live builder.', 'total' );
} else {
	$term_description = term_description();
}

if ( empty( $term_description ) ) {
	return;
}

// Define output.
$output = '';

// Default shortcode classes.
$shortcode_class = array(
	'vcex-module',
	'vcex-term-description',
	'wpex-last-mb-0',
);

// Custom user classes.
$extra_classes = vcex_get_shortcode_extra_classes( $atts, 'vcex_term_description' );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

// Responsive styles.
$unique_classname = vcex_element_unique_classname();

$el_responsive_styles = array(
	'font_size' => $atts['font_size'],
);

$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

if ( $responsive_css ) {
	$shortcode_class[] = $unique_classname;
	$output .= '<style>' . $responsive_css . '</style>';
}

// Parses shortcode classes to apply filters.
$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, 'vcex_term_description', $atts );

// Inline shortcode styles.
$shortcode_style = vcex_inline_style( array(
	'color'              => $atts['color'],
	'font_family'        => $atts['font_family'],
	'font_size'          => $atts['font_size'],
	'font_weight'        => $atts['font_weight'],
	'line_height'        => $atts['line_height'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
), true );

// Begin output
$output .= '<div class="' . esc_attr( trim( $shortcode_class ) ) . '"' . $shortcode_style . '>';
	$output .= do_shortcode( wp_kses_post( $term_description ) );
$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;