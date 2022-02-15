<?php
/**
 * vcex_searchbar shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_searchbar', $atts ) ) {
	return;
}

// Get and extract shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_searchbar', $atts, $this );

// Define output var.
$output = '';

// Sanitize.
$placeholder = $atts['placeholder'] ?: esc_html__( 'Keywords...', 'total' );
$button_text = $atts['button_text'] ?: esc_html__( 'Search', 'total' );

// Autofocus.
$autofocus = ( 'true' == $atts['autofocus'] ) ? 'autofocus' : '';

// Wrap Classes.
$wrap_classes = array(
	'vcex-module',
	'vcex-searchbar',
	'wpex-relative',
	'wpex-max-w-100',
	'wpex-text-lg',
	'wpex-clr',
);

if ( 'true' == $atts['fullwidth_mobile'] ) {
	$wrap_classes[] = 'vcex-fullwidth-mobile';
}

if ( $bottom_margin_class = vcex_sanitize_margin_class( $atts['bottom_margin'], 'wpex-mb-' ) ) {
	$wrap_classes[] = $bottom_margin_class;
}

if ( $atts['visibility'] ) {
	$wrap_classes[] = vcex_parse_visibility_class( $atts['visibility'] );
}

if ( ! empty( $atts['wrap_width'] ) && $atts['wrap_float'] ) {
	$wrap_classes[] = 'wpex-float-' . sanitize_html_class( $atts['wrap_float'] );
}

if ( $classes = vcex_get_extra_class( $atts['classes'] ) ) {
	$wrap_classes[] = $classes;
}

if ( $css_animation_class = vcex_get_css_animation( $atts['css_animation'] ) ) {
	$wrap_classes[] = $css_animation_class;
}

// Input classes.
$input_classes = 'vcex-searchbar-input';

if ( $atts['css'] ) {
	$input_classes .= ' ' . vcex_vc_shortcode_custom_css_class( $atts['css'] );
}

// Wrap style.
$wrap_style = vcex_inline_style( array(
	'width'              => $atts['wrap_width'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
) );

// Form style.
$form_style = vcex_inline_style( array(
	'color'          => $atts['input_color'],
	'font_size'      => $atts['input_font_size'],
	'text_transform' => $atts['input_text_transform'],
	'letter_spacing' => $atts['input_letter_spacing'],
	'font_weight'    => $atts['input_font_weight'],
) );

// Parse classes.
$wrap_classes = vcex_parse_shortcode_classes( $wrap_classes, 'vcex_searchbar', $atts );

// Begin output.
$output .= '<div class="' . esc_attr( $wrap_classes ) . '"' . vcex_get_unique_id( $atts['unique_id'] ) . $wrap_style . '>';

	$output .= '<form method="get" class="vcex-searchbar-form" action="' . esc_url( home_url( '/' ) ) . '"' . $form_style . '>';

		$output .= '<label>';

			$output .= '<span class="screen-reader-text">' . esc_html( $placeholder ) . '</span>';

			$input_style = vcex_inline_style( array(
				'width'         => $atts['input_width'],
				'border_color'  => $atts['input_border_color'],
				'border_width'  => $atts['input_border_width'],
				'border_radius' => $atts['input_border_radius'],
				'padding'       => $atts['input_padding'],
			) );

			$output .= '<input type="search" class="' . esc_attr( $input_classes ) . '" name="s" placeholder="' . esc_attr( $placeholder ) . '"' . $input_style . $autofocus . '>';

		$output .= '</label>';

		if ( ! empty( $atts['advanced_query'] ) ) :

			// Sanitize.
			$advanced_query = trim( $atts['advanced_query'] );
			$advanced_query = html_entity_decode( $advanced_query );

			// Convert to array.
			parse_str( $advanced_query, $advanced_query_array );

			// If array is valid loop through params.
			if ( $advanced_query_array ) :

				foreach( $advanced_query_array as $key => $val ) :

					switch ( $val ) {
						case 'current_term':
							if ( is_tax() ) {
								$tax_obj = get_queried_object();
								if ( is_object( $tax_obj ) && ! empty( $tax_obj->taxonomy ) ) {
									$val = $tax_obj->slug;
								}
							}
							break;
						case 'current_author':
							if ( is_author() ) {
								$val = get_the_author_meta( 'ID' );
							}
							break;
					}

					if ( 'current_term' === $val || 'current_author' === $val ) {
						continue;
					}

					$output .= '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '">';

				endforeach;

			endif;

		endif;

		/*
		 * Button
		 */
		$button_attrs = array(
			'class' => 'vcex-searchbar-button',
		);

		// Button hover data.
		$hover_data = array();
		if ( $atts['button_bg_hover'] ) {
			$hover_data['background'] = esc_attr( vcex_parse_color( $atts['button_bg_hover'] ) );
		}
		if ( $atts['button_color_hover'] ) {
			$hover_data['color'] = esc_attr( vcex_parse_color( $atts['button_color_hover'] ) );
		}
		if ( $hover_data ) {
			$button_attrs['data-wpex-hover'] = htmlspecialchars( wp_json_encode( $hover_data ) );
		}

		// Button style.
		$button_attrs['style'] = vcex_inline_style( array(
			'width'          => $atts['button_width'],
			'background'     => $atts['button_bg'],
			'color'          => $atts['button_color'],
			'font_size'      => $atts['button_font_size'],
			'text_transform' => $atts['button_text_transform'],
			'letter_spacing' => $atts['button_letter_spacing'],
			'font_weight'    => $atts['button_font_weight'],
			'border_radius'  => $atts['button_border_radius'],
		), false );

		$output .= '<button' . vcex_parse_html_attributes( $button_attrs ) . '>';

			$output .= do_shortcode( wp_kses_post( str_replace( '``', '"', $button_text ) ) );

		$output .= '</button>';

	$output .= '</form>';

$output .= '</div>';

if ( ! empty( $atts['wrap_width'] ) && $atts['wrap_float'] && 'center' !== $atts['wrap_float'] ) {
	$output .= '<div class="vcex-clear--searchbar wpex-clear"></div>';
}

// @codingStandardsIgnoreLine
echo $output;