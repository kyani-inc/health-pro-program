<?php
/**
 * vcex_callout shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_callout', $atts ) ) {
	return;
}

// Get shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_callout', $atts, $this );

// Checks & sanitization.
$is_full    = '100-100' === $atts['layout'] ? true : false;
$has_button = ( $atts['button_url'] && $atts['button_text'] ) ? true : false;
$breakpoint = ( $atts['breakpoint'] && ! $is_full ) ? $atts['breakpoint'] : 'md';

// Get layout.
if ( $atts['layout'] && in_array( $atts['layout'], array( '75-25', '60-40', '50-50', '100-100' ) ) ) {
	$layout = explode( '-', $atts['layout'] );
	$content_width = $layout[0];
	$button_width  = $layout[1];
} else {
	$content_width = '75';
	$button_width  = '25';
}

// Shortcode classes.
$shortcode_class = array(
	'vcex-module',
	'vcex-callout',
);

if ( $atts['style'] && 'none' !== $atts['style'] ) {
	$shortcode_class[] = 'wpex-' . sanitize_html_class( $atts['style'] );
}

if ( $is_full ) {
	$shortcode_class[] = 'wpex-text-center';
}

if ( $atts['shadow'] && empty( $atts['padding_all'] ) ) {
	if ( empty( $atts['style'] ) || 'none' === $atts['style'] ) {
		$shortcode_class[] = 'wpex-p-20';
	}
}

if ( $has_button ) {
	$shortcode_class[] = 'with-button';
	if ( ! $is_full ) {
		$shortcode_class[] = 'wpex-text-center';
		$shortcode_class[] = 'wpex-' . sanitize_html_class( $breakpoint ) . '-text-initial';
		$shortcode_class[] = 'wpex-' . sanitize_html_class( $breakpoint ) . '-flex';
		$shortcode_class[] = 'wpex-' . sanitize_html_class( $breakpoint ) . '-items-center';
	}
}

$extra_classes = vcex_get_shortcode_extra_classes( $atts, 'vcex_callout' );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

$shortcode_class = vcex_parse_shortcode_classes( implode( ' ', $shortcode_class ), 'vcex_callout', $atts );

$shortcode_style = vcex_inline_style( array(
	'background'         => $atts['background'],
	'border_color'       => $atts['border_color'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
) );

$output = '';

$output .= '<div class="' . esc_attr( $shortcode_class ) . '"' . vcex_get_unique_id( $atts['unique_id'] ) . $shortcode_style . '>';

	// Display content.
	if ( $content ) {

		$content_classes = array(
			'vcex-callout-caption',
			'wpex-text-md',
			'wpex-last-mb-0',
		);

		if ( $has_button ) {

			$content_classes[] = 'wpex-mb-20';

			if ( ! $is_full ) {
				$content_classes[] = 'wpex-' . sanitize_html_class( $breakpoint ) . '-w-' . sanitize_html_class( $content_width );
				$content_classes[] = 'wpex-' . sanitize_html_class( $breakpoint ) . '-pr-20';
				$content_classes[] = 'wpex-' . sanitize_html_class( $breakpoint ) . '-mb-0';
			}

		}

		$content_classes[] = 'wpex-clr';

		$content_inline_style = vcex_inline_style( array(
			'color'          => $atts['content_color'],
			'font_size'      => $atts['content_font_size'],
			'letter_spacing' => $atts['content_letter_spacing'],
			'font_family'    => $atts['content_font_family'],
		) );

		$output .= '<div class="' . esc_attr( implode( ' ', $content_classes ) ) . '"' . $content_inline_style . '>';

			$output .= vcex_the_content( $content );

		$output .= '</div>';

	}

	// Display button.
	if ( $has_button ) {

		$button_wrap_classes = array(
			'vcex-callout-button',
		);

		if ( $is_full ) {
			$button_align = $atts['button_align'] ?: 'center';
			$button_wrap_classes[] = 'wpex-text-' . sanitize_html_class( $button_align );
		} else {
			$button_align = $atts['button_align'] ?: 'right';
			$button_wrap_classes[] = 'wpex-' . sanitize_html_class( $breakpoint ) . '-w-' . sanitize_html_class( $button_width );
			$button_wrap_classes[] = 'wpex-' . sanitize_html_class( $breakpoint ) . '-text-' . sanitize_html_class( $button_align );
		}

		$output .= '<div class="' . esc_attr( implode( ' ', $button_wrap_classes ) ) . '">';

			$button_inline_style = vcex_inline_style( array(
				'color'          => $atts['button_custom_color'],
				'background'     => $atts['button_custom_background'],
				'padding'        => $atts['button_padding'],
				'border_radius'  => $atts['button_border_radius'],
				'font_size'      => $atts['button_font_size'],
				'letter_spacing' => $atts['button_letter_spacing'],
				'font_family'    => $atts['button_font_family'],
				'font_weight'    => $atts['button_font_weight'],
			), false );

			$button_attrs = array(
				'href'   => esc_url( do_shortcode( $atts['button_url'] ) ),
				'title'  => esc_attr( do_shortcode( $atts['button_text'] ) ),
				'target' => $atts['button_target'],
				'rel'    => $atts['button_rel'],
				'style'  => $button_inline_style,
			);

			$button_classes = array( vcex_get_button_classes( $atts['button_style'], $atts['button_color'] ) );

			if ( 'local' === $atts['button_target'] ) {
				$button_classes[] = 'local-scroll-link';
			}

			if ( 'true' == $atts['button_full_width'] ) {
				$button_classes[] = 'full-width';
			}

			$button_hover_data = array();

			if ( $atts['button_custom_hover_background'] ) {
				$button_hover_data[ 'background' ] = esc_attr( vcex_parse_color( $atts['button_custom_hover_background'] ) );
			}

			if ( $atts['button_custom_hover_color'] ) {
				$button_hover_data[ 'color' ] = esc_attr( vcex_parse_color( $atts['button_custom_hover_color'] ) );
			}

			if ( $button_hover_data ) {
				$button_attrs[ 'data-wpex-hover' ] = htmlspecialchars( wp_json_encode( $button_hover_data ) );
			}

			$button_classes[] = 'wpex-text-center';
			$button_classes[] = 'wpex-text-base';

			$button_attrs['class'] = $button_classes;

			$output .= '<a' . vcex_parse_html_attributes( $button_attrs ) . '>';

				$icon_left  = vcex_get_icon_class( $atts, 'button_icon_left' );
				$icon_right = vcex_get_icon_class( $atts, 'button_icon_right' );

				if ( $icon_left ) {
					vcex_enqueue_icon_font( $atts['icon_type'], $icon_left );
					$output .= '<span class="theme-button-icon-left ' . esc_attr( $icon_left ) . '" aria-hidden="true"></span>';
				}

				$output .= do_shortcode( wp_kses_post( $atts['button_text'] ) );

				if ( $icon_right ) {
					vcex_enqueue_icon_font( $atts['icon_type'], $icon_right );
					$output .= '<span class="theme-button-icon-right ' . esc_attr( $icon_right ) . '" aria-hidden="true"></span>';
				}

			$output .= '</a>';

		$output .= '</div>';

	}

$output .= '</div>';

// @codingStandardsIgnoreLine.
echo $output;