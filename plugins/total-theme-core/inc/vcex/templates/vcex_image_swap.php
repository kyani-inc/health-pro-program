<?php
/**
 * vcex_image_swap shortcode output
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_image_swap', $atts ) ) {
	return;
}

// Fallbacks (old atts).
$link_title  = $atts['link_title'] ?? '';
$link_target = $atts['link_target'] ?? '';

// Get and extract shortcode attributes
$atts = vcex_shortcode_atts( 'vcex_image_swap', $atts, $this );
extract( $atts );

// Declare vars.
$output = '';

// Get images based on source.
switch ( $atts['source'] ) {
	case 'featured':
		$post_id = vcex_get_the_ID();
		$primary_image = get_post_thumbnail_id( $post_id );
		if ( function_exists( 'wpex_get_secondary_thumbnail' ) ) {
			$secondary_image = wpex_get_secondary_thumbnail( $post_id );
		}
		break;
	case 'custom_field':
		$post_id = vcex_get_the_ID();
		$primary_image = get_post_meta( $post_id, $atts['primary_image_custom_field'], true );
		$secondary_image = get_post_meta( $post_id, $atts['secondary_image_custom_field'], true );
		break;
	case 'media_library':
	default:
		$primary_image = $atts['primary_image'];
		$secondary_image = $atts['secondary_image'];
		break;
}

// Apply filters to images for advanced child theming.
$primary_image   = apply_filters( 'vcex_image_swap_primary_image', $primary_image );
$secondary_image = apply_filters( 'vcex_image_swap_secondary_image', $secondary_image );

// Primary and secondary imags required.
if ( empty( $primary_image ) || empty( $secondary_image ) ) {
	return;
}

// Add styles.
$wrapper_inline_style = vcex_inline_style( array(
	'width' => $container_width,
) );

$image_style = vcex_inline_style( array(
	'border_radius' => $border_radius,
), false );

// Add classes.
$shortcode_class = array(
	'vcex-module',
	'vcex-image-swap',
	'wpex-block',
	'wpex-relative',
	'wpex-mx-auto',
	'wpex-max-w-100',
	'wpex-overflow-hidden',
	'wpex-clr',
);

if ( $bottom_margin && empty( $css ) ) {
	$shortcode_class[] = vcex_sanitize_margin_class( $bottom_margin, 'wpex-mb-' );
}

if ( $atts['align'] && ( 'left' === $atts['align'] || 'right' === $atts['align'] ) ) {
	$shortcode_class[] = 'float' . sanitize_html_class( $atts['align'] );
}

if ( $classes ) {
	$shortcode_class[] = vcex_get_extra_class( $classes );
}

if ( empty( $css ) && $css_animation_class = vcex_get_css_animation( $css_animation ) ) {
	$shortcode_class[] = $css_animation_class;
}

$shortcode_class = vcex_parse_shortcode_classes( implode( ' ', $shortcode_class ), 'vcex_image_swap', $atts );

if ( $css ) {

	$css_wrap_class = vcex_vc_shortcode_custom_css_class( $css );

	$css_wrap_class .= ' wpex-mx-auto';

	if ( $bottom_margin ) {
		$css_wrap_class .= ' wpex-mb-' . absint( $bottom_margin );
	}

	if ( $css_animation_class = vcex_get_css_animation( $css_animation ) ) {
		$css_wrap_class .= ' ' . $css_animation_class;
	}

	$output .='<div class="' . esc_attr( $css_wrap_class )  . '"' . $wrapper_inline_style . '>';
}

$shortcode_style = vcex_inline_style( array(
	'animation_delay' => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
) );

$output .='<figure class="' . esc_attr( $shortcode_class ) . '"' . $wrapper_inline_style . vcex_get_unique_id( $unique_id ) . $shortcode_style . '>';

	// Get link data.
	$link_data = vcex_build_link( $link );

	// Output link.
	if ( ! empty( $link_data['url'] ) ) {

		// Define link attributes.
		$link_attrs = array(
			'href'  => '',
			'class' => 'vcex-image-swap-link',
		);

		// Link attributes.
		$link_attrs['href']   = isset( $link_data['url'] ) ? esc_url( $link_data['url'] ) : $link;
		$link_attrs['title']  = isset( $link_data['title'] ) ? esc_attr( $link_data['title'] ) : '';
		$link_attrs['rel']    = $link_data['rel'] ?? '';
		$link_attrs['target'] = $link_data['target'] ?? '';

		$output .='<a' . vcex_parse_html_attributes( $link_attrs ) . '>';

	}

	// Primary image.
	$transition_duration = ! empty( $hover_speed ) ? 'wpex-duration-' . absint( $hover_speed ) : 'wpex-duration-300';

	$output .= vcex_get_post_thumbnail( array(
		'attachment' => $primary_image,
		'size'       => $atts['img_size'],
		'crop'       => $atts['img_crop'],
		'width'      => $atts['img_width'],
		'height'     => $atts['img_height'],
		'class'      => 'vcex-image-swap-primary wpex-block wpex-relative wpex-z-5 wpex-w-100 wpex-overflow-hidden wpex-transition-opacity ' . $transition_duration,
		'style'      => $image_style,
	) );

	// Secondary image.
	$output .= vcex_get_post_thumbnail( array(
		'attachment' => $secondary_image,
		'size'       => $atts['img_size'],
		'crop'       => $atts['img_crop'],
		'width'      => $atts['img_width'],
		'height'     => $atts['img_height'],
		'class'      => 'vcex-image-swap-secondary wpex-block wpex-absolute wpex-inset-0 wpex-z-1 wpex-w-100 wpex-overflow-hidden',
		'style'      => $image_style,
	) );

	// Close link wrapper.
	if ( ! empty( $link_data['url'] ) ) {
		$output .='</a>';
	}

$output .='</figure>'; // Close main wrap

// Close CSS wrapper.
if ( $css ) {
	$output .='</div>';
}

if ( $atts['align'] && ( 'left' === $atts['align'] || 'right' === $atts['align'] ) ) {
	$output .= '<div class="vcex-image-swap-clear-align wpex-clear"></div>';
}

// @codingStandardsIgnoreLine
echo $output;