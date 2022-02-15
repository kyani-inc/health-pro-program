<?php
/**
 * vcex_post_media shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_post_media', $atts ) ) {
	return;
}

$atts = vcex_shortcode_atts( 'vcex_post_media', $atts, $this );

if ( $atts['supported_media'] && is_string( $atts['supported_media'] ) ) {
	$atts['supported_media'] = wp_parse_list( $atts['supported_media'] );
}

if ( ! is_array( $atts['supported_media'] ) ) {
	$atts['supported_media'] = array(); // must be an array to prevent debug errors.
}

$post_id = vcex_get_the_ID();

$shortcode_class = array(
	'vcex-post-media',
	'wpex-clr'
);

if ( ! empty( $atts['width'] ) ) {
	$shortcode_class[] = 'wpex-mx-auto';
	$shortcode_class[] = 'wpex-max-w-100';
}

$extra_classes = vcex_get_shortcode_extra_classes( $atts, 'vcex_post_media' );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

$shortcode_class = vcex_parse_shortcode_classes( implode( ' ', $shortcode_class ), 'vcex_post_media', $atts );

$shortcode_style = vcex_inline_style( array(
	'width' => $atts['width'],
	'animation_delay' => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
), true );

$output = '<div class="' . esc_attr( $shortcode_class ) . '"' . $shortcode_style . '>';

	if ( function_exists( 'wpex_get_post_media' ) ) {

		$output .= wpex_get_post_media( $post_id, array(
			'thumbnail_args'  => array(
				'attachment' => get_post_thumbnail_id( $post_id ),
				'size'       => $atts['img_size'],
				'crop'       => $atts['img_crop'],
				'width'      => $atts['img_width'],
				'height'     => $atts['img_height'],
			),
			'lightbox'        => vcex_validate_boolean( $atts['lightbox'] ),
			'supported_media' => $atts['supported_media'],
		) );

	} else {

		$output .= get_the_post_thumbnail();

	}

$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;