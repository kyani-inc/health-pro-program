<?php
/**
 * vcex_custom_field shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_custom_field', $atts ) ) {
	return;
}

// Get shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_custom_field', $atts, $this );

// Name required.
if ( empty( $atts['name'] ) ) {
	return;
}

$output = '';
$cf_value = '';
$custom_field_name = $atts['name'];

// Get value from ACF.
if ( shortcode_exists( 'acf' ) ) {
	$cf_value = do_shortcode( '[acf field="' . $custom_field_name . '" post_id="' . vcex_get_the_ID() . '"]' );
}

// Get value using core WP functions.
if ( empty( $cf_value ) && 0 !== $cf_value ) {
	$cf_value = get_post_meta( vcex_get_the_ID(), $custom_field_name, true );
	if ( $cf_value && is_string( $cf_value ) ) {
		$cf_value = wp_kses_post( $cf_value );
	}
}

// Parses the value based on user callback.
if ( ! empty( $atts['parse_callback'] ) && is_callable( $atts['parse_callback'] ) ) {
	$cf_value = call_user_func( $atts['parse_callback'], $cf_value );
}

// Fallback value.
if ( empty( $cf_value ) && 0 !== $cf_value && ! empty( $atts['fallback'] ) ) {
	$cf_value = $atts['fallback'];
}

// No need to show anything if value is empty.
if ( empty( $cf_value ) || ! is_string( $cf_value ) ) {
	return;
}

// Define classes.
$shortcode_class = array(
	'vcex-custom-field',
	'vcex-module',
	'wpex-clr',
);

if ( $atts['align'] ) {
	$shortcode_class[] = 'text' . sanitize_html_class( $atts['align'] );
}

if ( 'true' == $atts['italic'] ) {
	$shortcode_class[] = 'wpex-italic';
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

// Add extra shortcode classes.
$extra_classes = vcex_get_shortcode_extra_classes( $atts, 'vcex_custom_field' );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

// Parse shortcode classes.
$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, 'vcex_custom_field', $atts );

// Inline style.
$shortcode_style = vcex_inline_style( array(
	'background_color'   => $atts['background_color'],
	'border_color'       => $atts['border_color'],
	'color'              => $atts['color'],
	'font_family'        => $atts['font_family'],
	'font_size'          => $atts['font_size'],
	'line_height'        => $atts['line_height'],
	'letter_spacing'     => $atts['letter_spacing'],
	'font_weight'        => $atts['font_weight'],
	'text_transform'     => $atts['text_transform'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
), false );

// Shortcode attributes.
$shortcode_attrs = array(
	'class' => esc_attr( $shortcode_class ),
	'style' => $shortcode_style,
);

// Shortcode Output.
$output .= '<div' . vcex_parse_html_attributes( $shortcode_attrs ) . '>';

	$icon = vcex_get_icon_class( $atts, 'icon' );

	if ( $icon ) {

		$icon_style = vcex_inline_style( array(
			'color' => $atts['icon_color'],
		) );

		$icon_class = $icon; // can't use sanitize_html_class because it's multiple classes

		if ( ! $atts['icon_side_margin'] ) {
			$atts['icon_side_margin'] = '5';
		}

		$icon_class .= ' wpex-mr-' . absint( $atts['icon_side_margin'] );

		vcex_enqueue_icon_font( $atts['icon_type'], $icon );

		$output .= '<span class="' . esc_attr( $icon_class ) . '" aria-hidden="true"' . $icon_style . '></span> ';

	}

	if ( $atts['before'] ) {
		$output .= '<span class="vcex-custom-field-before wpex-font-bold">' . esc_html( $atts['before'] ) . '</span> ';
	}

	$output .= apply_filters( 'vcex_custom_field_value_output', $cf_value, $atts );

$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;