<?php
/**
 * vcex_image_ba shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_image_ba', $atts ) ) {
	return;
}

// Get and extract shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_image_ba', $atts, $this );

// Define output.
$output = '';

// Get image based on source.
switch ( $atts['source'] ) {
	case 'custom_field':
		$before_img = get_post_meta( vcex_get_the_ID(), $atts['before_img_custom_field'], true );
		$after_img  = get_post_meta( vcex_get_the_ID(), $atts['after_img_custom_field'], true );
		break;
	case 'media_library':
	default:
		$before_img = $atts['before_img'];
		$after_img  = $atts['after_img'];
		break;
}

// Primary and secondary imags required.
if ( ! $before_img || ! $after_img ) {
	return;
}

// Sanitize offset.
if ( ! isset( $atts['default_offset_pct'] ) ) {
	$default_offset_pct = '0.5';
} else {

	$default_offset_pct = str_replace( '%', '', $atts['default_offset_pct'] );

	if ( ! is_numeric( $default_offset_pct ) ) {
		$default_offset_pct = '0.5';
	} else {
		if ( $default_offset_pct > 1 ) {
			$default_offset_pct = $default_offset_pct / 100;
		}
		if ( $default_offset_pct <= 1 ) {
			$default_offset_pct = floatval( $default_offset_pct );
		}
	}

}

// Load scripts.
self::enqueue_scripts();

$wrap_attrs = array(
	'class' => 'vcex-image-ba-wrap',
);

if ( $bottom_margin = vcex_sanitize_margin_class( $atts['bottom_margin'], 'wpex-mb-' ) ) {
	$wrap_attrs['class'] .= ' ' . $bottom_margin;
}

if ( $css = vcex_vc_shortcode_custom_css_class( $atts['css'] ) ) {
	$wrap_attrs['class'] .= ' ' . $css;
}

$wrap_attrs['style'] = vcex_inline_style( array(
	'width' => $atts['width'],
), false );

if ( $atts['align'] ) {
	$wrap_attrs['class'] .= ' align' . sanitize_html_class( $atts['align'] );
}

// Begin html output.
$output .= '<div' . vcex_parse_html_attributes( $wrap_attrs ) . '>';

	// Figure classes.
	$figure_classes = array(
		'vcex-module',
		'vcex-image-ba',
		'twentytwenty-container' // add before JS to prevent potential rendering issues.
	);

	if ( $atts['el_class'] ) {
		$figure_classes[] = vcex_get_extra_class( $atts['el_class'] );
	}

	if ( $atts['css_animation'] && 'none' != $atts['css_animation'] ) {
		$figure_classes[] = vcex_get_css_animation( $atts['css_animation'] );
	}

	$figure_classes = vcex_parse_shortcode_classes( $figure_classes, 'vcex_image_swap', $atts );

	// Data attributes.
	$data = htmlspecialchars( wp_json_encode( array(
		'orientation'        => $atts['orientation'],
		'default_offset_pct' => $default_offset_pct,
		'no_overlay'         => ( 'false' == $atts['overlay'] ) ? true : null,
		'before_label'       => ! empty( $atts['before_label'] ) ? esc_attr( $atts['before_label'] ) : esc_attr__( 'Before', 'total' ),
		'after_label'        => ! empty( $atts['after_label'] ) ? esc_attr( $atts['after_label'] ) : esc_attr__( 'After', 'total' ),
	) ) );

	$figure_attrs = array(
		'class'        => esc_attr( $figure_classes ),
		'data-options' => $data,
	);

	$figure_style = vcex_inline_style( array(
			'animation_delay'    => $atts['animation_delay'],
			'animation_duration' => $atts['animation_duration'],
	) );

	$output .= '<figure' . vcex_parse_html_attributes( $figure_attrs ) . $figure_style . '>';

		// Before image.
		$output .= vcex_get_post_thumbnail( array(
			'attachment' => $before_img,
			'size'       => $atts['img_size'],
			'crop'       => $atts['img_crop'],
			'width'      => $atts['img_width'],
			'height'     => $atts['img_height'],
			'class'      => 'vcex-before',
			'lazy'       => false,
		) );

		// After image.
		$output .= vcex_get_post_thumbnail( array(
			'attachment' => $after_img,
			'size'       => $atts['img_size'],
			'crop'       => $atts['img_crop'],
			'width'      => $atts['img_width'],
			'height'     => $atts['img_height'],
			'class'      => 'vcex-after',
			'lazy'       => false,
		) );

	$output .= '</figure>';

$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;