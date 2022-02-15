<?php
/**
 * vcex_page_title shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_page_title', $atts ) ) {
	return;
}

$atts = vcex_shortcode_atts( 'vcex_page_title', $atts, $this );

$title = vcex_get_the_title();

if ( empty( $title ) ) {
	return;
}

$shortcode_class = array(
	'vcex-module',
	'vcex-page-title',
);

if ( $atts['width'] ) {

	$shortcode_class[] = 'wpex-max-w-100';

	switch ( $atts['float'] ) {
		case 'left':
			$shortcode_class[] = 'wpex-float-left';
			break;
		case 'right':
			$shortcode_class[] = 'wpex-float-right';
			break;
		case 'center':
		default:
			$shortcode_class[] = 'wpex-mx-auto';
			break;
	}

}

// Custom user classes.
$extra_classes = vcex_get_shortcode_extra_classes( $atts, 'vcex_page_title' );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

// Filters shortcode classes.
$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, 'vcex_page_title', $atts );

// Inline styles.
$shortcode_style = vcex_inline_style( array(
	'width'              => $atts['width'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
), true );

// Begin output.
$output = '<div class="' . esc_attr( trim( $shortcode_class ) ) . '"' . $shortcode_style . '>';

	// Inner heading classes.
	$heading_classes = array(
		'wpex-heading',
		'vcex-page-title__heading',
		'wpex-text-3xl',
	);

	// Sanitize custom html_tag.
	$tag_escaped = tag_escape( $atts['html_tag'] );

	// Inline heading style.
	$heading_style = vcex_inline_style( array(
		'color'       => $atts['color'],
		'font_family' => $atts['font_family'],
		'font_size'   => $atts['font_size'],
		'line_height' => $atts['line_height'],
		'font_weight' => $atts['font_weight'],
	), true );

	// Responsive heading styles.
	$unique_classname = vcex_element_unique_classname();

	$el_responsive_styles = array(
		'font_size' => $atts['font_size'],
	);

	$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

	if ( $responsive_css ) {
		$heading_classes[] = $unique_classname;
		$output .= '<style>' . $responsive_css . '</style>';
	}

	// Display the heading.
	$output .= '<' . $tag_escaped . ' class="' . implode( ' ', $heading_classes ) . '"' . $heading_style . '>';

		// Before text.
		if ( $atts['before_text'] ) {
			$output .= '<span class="vcex-page-title__before">' . do_shortcode( esc_html( $atts['before_text'] ) ) . '</span> ';
		}

		// The page title.
		$output .= '<span class="vcex-page-title__text">' .  do_shortcode( wp_kses_post( $title ) ) . '</span>';

		// After text.
		if ( $atts['after_text'] ) {
			$output .= ' <span class="vcex-page-title__after">' . do_shortcode( esc_html( $atts['after_text'] ) ) . '</span>';
		}

	$output .= '</' . $tag_escaped . '>';

$output .= '</div>';

if ( $atts['width'] && 'center' !== $atts['float'] ) {
	$output .= '<div class="vcex-clear--page_title wpex-clear"></div>';
}

// @codingStandardsIgnoreLine
echo $output;