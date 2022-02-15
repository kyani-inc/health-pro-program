<?php
/**
 * vcex_newsletter_form shortcode output
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_newsletter_form', $atts ) ) {
	return;
}

// Define output var.
$output = '';

// Get and extract shortcode atts.
$atts = vcex_shortcode_atts( 'vcex_newsletter_form', $atts, $this );
extract( $atts );

// Wrapper classes.
$shortcode_class = array(
	'vcex-module',
	'vcex-newsletter-form',
	'wpex-max-w-100', // prevent issues with flex wraps.
	'wpex-clr', // prevent issues with alignment.
);

if ( $bottom_margin ) {
	$shortcode_class[] = vcex_sanitize_margin_class( $bottom_margin, 'wpex-mb-' );
}

if ( 'true' == $fullwidth_mobile && 'false' === $stack_fields ) {
	$shortcode_class[] = 'vcex-fullwidth-mobile';
}

if ( $classes ) {
	$shortcode_class[] = vcex_get_extra_class( $classes );
}

if ( $visibility ) {
	$shortcode_class[] = vcex_parse_visibility_class( $visibility );
}

if ( $css_animation_class = vcex_get_css_animation( $css_animation ) ) {
	$shortcode_class[] = $css_animation_class;
}

// Apply filters.
$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, 'vcex_newsletter_form', $atts );

// Inline Style.
$shortcode_style = vcex_inline_style( array(
	'animation_delay' => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
) );

// Begin output.
$output .= '<div class="' . esc_attr( $shortcode_class ) . '"' . vcex_get_unique_id( $atts['unique_id'] ) .  $shortcode_style . '>';

	$input_width = $input_width ? ' style="width:' . esc_attr( $input_width ) . '"' : '';
	$input_align = $input_align ? ' float' . sanitize_html_class( trim( $input_align ) ) : '';

	$output .= '<div class="vcex-newsletter-form-wrap wpex-max-w-100' . $input_align . '"' . $input_width . '>';

		$form_class = 'wpex-flex';

		if ( 'true' == $atts['stack_fields'] ) {
			$form_class .= ' wpex-flex-col';
			if ( empty( $atts['gap'] ) ) {
				$atts['gap'] = '10';
			}
		}

		if ( ! empty( $atts['gap'] ) ) {
			$form_class .= ' wpex-gap-' . sanitize_html_class( absint( $atts['gap'] ) );
		}

		$output .= '<form action="' . esc_url( $form_action ) . '" method="post" class="' . esc_attr( $form_class ) . '">';

			/** Input ***/
			$input_style = vcex_inline_style( array(
				'border'         => $atts['input_border'],
				'border_radius'  => $atts['input_border_radius'],
				'padding'        => $atts['input_padding'],
				'letter_spacing' => $atts['input_letter_spacing'],
				'height'         => $atts['input_height'],
				'background'     => $atts['input_bg'],
				'border_color'   => $atts['input_border_color'],
				'color'          => $atts['input_color'],
				'font_size'      => $atts['input_font_size'],
				'font_weight'    => $atts['input_weight'],
			) );

			$input_style = $input_style ? ' ' . $input_style : '';

			if ( ! empty( $atts['input_label'] ) ) {

			} else {
				$output .= '<label class="vcex-newsletter-form-label wpex-flex-grow">';
				$output .= '<span class="screen-reader-text">' . esc_html( $placeholder_text ) . '</span>';
			}

				$input_name = $input_name ?: 'EMAIL';

				$output .= '<input type="email" name="' . esc_attr( $input_name ) . '" placeholder="' . esc_attr( $placeholder_text ) . '" autocomplete="off"' . $input_style . '>';

			if ( empty( $atts['input_label'] ) ) {
				$output .= '</label>';
			}

			/** Hidden Fields **/
			if ( ! empty( $hidden_fields ) ) {
				$hidden_fields = explode( ',', $hidden_fields );
				if ( is_array( $hidden_fields ) ) {
					foreach( $hidden_fields as $field ) {
						$field_attrs = explode( '|', $field );
						if ( isset( $field_attrs[0] ) && isset( $field_attrs[1] ) ) {
							$output .= '<input type="hidden" name="' . esc_attr( $field_attrs[0] ) . '" value="' . esc_attr( $field_attrs[1] ) . '">';
						}
					}
				}
			}

			ob_start();
				do_action( 'vcex_newsletter_form_extras' );
			$output .= ob_get_clean();

			/** Submit Button ***/
			if ( $atts['submit_text'] ) {

				$button_styles = array(
					'height'         => $atts['submit_height'],
					'border'         => $atts['submit_border'],
					'letter_spacing' => $atts['submit_letter_spacing'],
					'padding'        => $atts['submit_padding'],
					'background'     => $atts['submit_bg'],
					'color'          => $atts['submit_color'],
					'font_size'      => $atts['submit_font_size'],
					'font_weight'    => $atts['submit_weight'],
					'border_radius'  => $atts['submit_border_radius'],
					'text_transform' => $atts['submit_text_transform'],
				);

				if ( 'true' == $atts['stack_fields'] ) {
					$button_styles['min_height'] = '45px';
				}

				$attrs = array(
					'type'  => 'submit',
					'value' => '',
					'class' => 'vcex-newsletter-form-button',
					'style' => vcex_inline_style( $button_styles, false ),
				);

				// Add hover data.
				$hover_data = array();

				if ( $atts['submit_hover_bg'] ) {
					$hover_data['background'] = esc_attr( vcex_parse_color( $atts['submit_hover_bg'] ) );
				}

				if ( $atts['submit_hover_color'] ) {
					$hover_data['color'] = esc_attr( vcex_parse_color( $atts['submit_hover_color'] ) );
				}

				if ( $hover_data ) {
					$attrs['data-wpex-hover'] = htmlspecialchars( wp_json_encode( $hover_data ) );
				}

				$output .= '<button' . vcex_parse_html_attributes( $attrs ) . '>';

					$output .= do_shortcode( wp_kses_post( $atts['submit_text'] ) );

				$output .= '</button>';

			}

		$output .= '</form>';

	$output .= '</div>';

$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;