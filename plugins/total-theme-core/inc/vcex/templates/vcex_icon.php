<?php
/**
 * vcex_icon shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_icon', $atts ) ) {
	return;
}

// Get shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_icon', $atts, $this );

// Sanitize data & declare vars.
$output = $data_attributes = $link_url = $float = '';
$icon = vcex_get_icon_class( $atts );

// Shortcode class.
$shortcode_class = array(
	'vcex-module',
	'vcex-icon',
);

if ( $atts['style'] ) {
	$shortcode_class[] = 'vcex-icon-' . sanitize_html_class( $atts['style'] );
}

if ( $atts['size'] ) {
	$shortcode_class[] = 'vcex-icon-' . sanitize_html_class( $atts['size'] );
}

if ( $atts['float'] ) {

	// For RTL left is right and right is left (this is legacy as the icons always worked like this).
	$float = vcex_parse_direction( $atts['float'] );

	switch ( $float ) {
		case 'left':
			$shortcode_class[] = 'wpex-float-left';
			$shortcode_class[] = 'wpex-mr-20';
			break;
		case 'center':
			$shortcode_class[] = 'wpex-float-none';
			$shortcode_class[] = 'wpex-m-auto';
			if ( empty( $align ) ) {
				$shortcode_class[] = 'wpex-text-center';
			}
			break;
		case 'right':
			$shortcode_class[] = 'wpex-float-right';
			$shortcode_class[] = 'wpex-ml-20';
			break;
	}

} elseif ( $atts['align'] ) {
	$shortcode_class[] = 'wpex-text-' . sanitize_html_class( $atts['align'] );
}

if ( $css_animation_class = vcex_get_css_animation( $atts['css_animation'] ) ) {
	$shortcode_class[] = $css_animation_class;
}

if ( ! empty( $atts['bottom_margin'] ) ) {
	$shortcode_class[] = vcex_parse_margin_class( $atts['bottom_margin'], 'wpex-mb-' );
}

if ( $el_class = vcex_get_extra_class( $atts['el_class'] ) ) {
	$shortcode_class[] = $el_class;
}

// Parse shortcode classes.
$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, 'vcex_icon', $atts );

// Inline styles.
$shortcode_style = vcex_inline_style( array(
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
	'font_size'          => $atts['custom_size'],
) );

// Begin shortcode output.
$output .= '<div class="' . esc_attr( trim( $shortcode_class ) ) . '"' . vcex_get_unique_id( $atts['unique_id'] ) . $shortcode_style . '>';

	// Open custom link.
	if ( $atts['link_url'] ) {

		$link_data = vcex_build_link( $atts['link_url'] );
		$link_url = $link_data['url'] ?? $link_url;
		$link_url = esc_url( do_shortcode( $link_url ) );

		if ( $link_url ) {

			$link_attrs  = array(
				'href'  => $link_url,
				'class' => array(
					'vcex-icon-link',
					'wpex-no-underline'
				),
			);
			$link_attrs['title'] = $link_data['title'] ?? '';
			$link_attrs['target'] = $link_data['target'] ?? '';
			$link_attrs['rel'] = $link_data['rel'] ?? '';

			if ( 'true' === $atts['link_local_scroll'] ) {
				unset( $link_attrs['target'] );
				unset( $link_attrs['rel'] );
				$link_attrs['class'][] = 'local-scroll-link';
			}

			$output .= '<a' . vcex_parse_html_attributes( $link_attrs ) . '>';

		}

	}

	// Icon classes.
	$icon_wrap_attrs = array(
		'class' => array(
			'vcex-icon-wrap',
			'wpex-inline-flex',
			'wpex-items-center',
			'wpex-justify-center',
			'wpex-leading-none', // keep since it was always here, but not really needed.
		),
	);

	if ( 'true' === $atts['color_accent'] && ! $atts['color'] ) {
		$icon_wrap_attrs['class'][] = 'wpex-text-accent';
	}

	if ( 'true' === $atts['background_accent'] && ! $atts['background'] ) {
		$icon_wrap_attrs['class'][] = 'wpex-bg-accent';
	}

	if ( $atts['background'] || 'true' === $atts['background_accent'] ) {
		if ( empty( $atts['height'] ) && empty( $atts['width'] ) ) {
			$icon_wrap_attrs['class'][] = 'wpex-p-20';
		}
	}

	if ( $atts['hover_animation'] ) {
		$icon_wrap_attrs['class'][] = vcex_hover_animation_class( $atts['hover_animation'] );
		vcex_enque_style( 'hover-animations' );
	}

	if ( ! empty( $atts['border'] ) ) {
		$icon_wrap_attrs['class'][] = 'wpex-box-content'; // prevent issues when adding borders to icons.
	}

	if ( ! $atts['hover_animation'] && ( $atts['background_hover'] || $atts['color_hover'] ) ) {
		$icon_wrap_attrs['class'][] = 'wpex-transition-colors';
		$icon_wrap_attrs['class'][] = 'wpex-duration-200';
	}

	// Icon hovers.
	$hover_data = array();

	if ( $atts['background_hover'] ) {
		$hover_data['background'] = esc_attr( vcex_parse_color( $atts['background_hover'] ) );
	}

	if ( $atts['color_hover'] ) {
		$hover_data['color'] = esc_attr( vcex_parse_color( $atts['color_hover'] ) );
	}

	if ( $hover_data ) {
		$icon_wrap_attrs['data-wpex-hover'] = htmlspecialchars( wp_json_encode( $hover_data ) );
	}

	// Add Style.
	$icon_wrap_attrs['style'] = vcex_inline_style( array(
		'color'            => $atts['color'],
		'padding'          => $atts['padding'],
		'background_color' => $atts['background'],
		'border_radius'    => $atts['border_radius'],
		'height'           => $atts['height'],
		'width'            => $atts['width'],
		'border'           => $atts['border'],
	), false );

	// Open Icon div.
	$output .= '<div' . vcex_parse_html_attributes( $icon_wrap_attrs ) . '>';

		// Display alternative icon.
		if ( $atts['icon_alternative_classes'] ) {

			$output .= '<span class="' . esc_attr( do_shortcode( $atts['icon_alternative_classes'] ) ) . '"></span>';

		// Display theme supported icon.
		} else {

			vcex_enqueue_icon_font( $atts['icon_type'], $icon );

			$output .= '<span class="' . esc_attr( $icon ) . '"></span>';

		}

	// Close icon div.
	$output .= '</div>';

	// Close link tag.
	if ( $link_url ) {
		$output .= '</a>';
	}

$output .= '</div>';

if ( $float && vcex_vc_is_inline() ) {
	$output .= '<div class="wpex-clear"></div>';
}

// @codingStandardsIgnoreLine.
echo $output;