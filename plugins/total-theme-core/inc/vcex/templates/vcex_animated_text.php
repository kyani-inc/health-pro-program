<?php
/**
 * vcex_animated_text shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_animated_text', $atts ) ) {
	return;
}

$atts = vcex_shortcode_atts( 'vcex_animated_text', $atts, $this );

$strings = (array) vcex_vc_param_group_parse_atts( $atts['strings'] );

if ( ! $strings ) {
	return;
}

// Define shortcode output.
$output = '';

// Enqueue element scripts.
$this->enqueue_scripts(); // @todo move to main class?

// Define shortcode CSS classes.
$shortcode_class = array(
	'vcex-animated-text',
	'vcex-module',
	'wpex-m-0',
	'wpex-text-xl',
	'wpex-text-gray-900',
	'wpex-font-semibold',
	'wpex-leading-none',
	'vcex-typed-text-wrap',
);

// Responsive CSS.
$unique_classname = vcex_element_unique_classname();

$el_responsive_styles = array(
	'font_size' => $atts['font_size'],
);

$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

if ( $responsive_css ) {
	$shortcode_class[] = $unique_classname;
	$output .= '<style>' . $responsive_css . '</style>';
}

// Add extra classes.
if ( $extra_classes = vcex_get_shortcode_extra_classes( $atts, 'vcex_animated_text' ) ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

// Apply filters to shortcode class.
$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, 'vcex_animated_text', $atts );

// Animated text data attributes.
$data_attr = '';

$data = array();
foreach ( $strings as $string ) {
	if ( isset( $string['text'] ) ) {
		$data[] = esc_html( $string['text'] );
	}
}

// Define animation settings.
$settings = array(
	'typeSpeed'  => vcex_intval( $atts['speed'], 40 ),
	'loop'       => vcex_validate_boolean( $atts['loop'] ),
	'showCursor' => vcex_validate_boolean( $atts['type_cursor'] ),
	'backDelay'  => vcex_intval( $atts['back_delay'], 0 ),
	'backSpeed'  => vcex_intval( $atts['back_speed'], 0 ),
	'startDelay' => vcex_intval( $atts['start_delay'], 0 ),
);

// Inline styles.
$inline_style = vcex_inline_style( array(
	'background_color'   => $atts['background_color'],
	'border_color'       => $atts['border_color'],
	'color'              => $atts['color'],
	'font_size'          => $atts['font_size'],
	'font_weight'        => $atts['font_weight'],
	'font_style'         => $atts['font_style'],
	'font_family'        => $atts['font_family'],
	'text_align'         => $atts['text_align'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
) );

// Inner inline style.
$typed_inline_style = vcex_inline_style( array(
	'background'      => $atts['animated_background_color'],
	'color'           => $atts['animated_color'],
	'font_weight'     => $atts['animated_font_weight'],
	'font_style'      => $atts['animated_font_style'],
	'font_family'     => $atts['animated_font_family'],
	'line_height'     => $atts['animated_line_height'],
	'text_decoration' => $atts['animated_text_decoration'],
	'width'           => $atts['animated_span_width'],
	'text_align'      => $atts['animated_text_align'],
) );

// Escape element tag.
$tag_escaped = $atts['tag'] ? tag_escape( $atts['tag'] ) : 'div';

// Output Shortcode.
$output .= '<' . $tag_escaped . ' class="' . esc_attr( $shortcode_class ) . '"' . $inline_style . $data_attr . '>';

	if ( 'true' === $atts['static_text'] && $atts['static_before'] ) {
		$output .= '<span class="vcex-before">' . do_shortcode( wp_kses_post( $atts['static_before'] ) ) . '</span> ';
	}

	if ( $atts['animated_css'] || $atts['animated_padding'] || $typed_inline_style ) {

		$inner_class = 'vcex-typed-text-css wpex-inline-block wpex-max-w-100';

		if ( $atts['animated_css'] && $animated_css = vcex_vc_shortcode_custom_css_class( $atts['animated_css'] ) ) {
			$inner_class .= ' ' . $animated_css;
		}

		if ( $atts['animated_padding'] && $animated_padding_class = vcex_parse_padding_class( $atts['animated_padding'] ) ) {
			$inner_class .= ' ' . $animated_padding_class;
		}

		$output .= '<span class="' . esc_attr( $inner_class ) . '"' . $typed_inline_style . '>';

	}

	$tmp_data = array();
	foreach ( $data as $val ) {
		$tmp_data[] = do_shortcode( $val );
	}
	$data = $tmp_data;

	$output .= '<span class="screen-reader-text">';

		foreach ( $data as $string ) {
			$output .= '<span>' . esc_html( do_shortcode( $string ) ) . '</span>';
		}

	$output .= '</span>';

	$output .= '<span class="vcex-ph wpex-inline-block wpex-invisible"></span>'; // Add empty span 1px wide to prevent bounce.

	$output .= '<span class="vcex-typed-text" data-settings="' . htmlspecialchars( wp_json_encode( $settings ) ) . '" data-strings="' . htmlspecialchars( wp_json_encode( $data ) ) . '"></span>';

	if ( $atts['animated_css'] || $atts['animated_padding'] || $typed_inline_style ) {
		$output .= '</span>';
	}

	if ( 'true' === $atts['static_text'] && $atts['static_after'] ) {
		$output .= ' <span class="vcex-after">' . do_shortcode( wp_kses_post( $atts['static_after'] ) ) . '</span>';
	}

$output .= '</' . $tag_escaped . '>';

// @codingStandardsIgnoreLine.
echo $output;