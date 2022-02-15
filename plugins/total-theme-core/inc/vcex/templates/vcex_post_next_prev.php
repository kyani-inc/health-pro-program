<?php
/**
 * vcex_post_next_prev shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_post_next_prev', $atts ) ) {
	return;
}

// Get and extract shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_post_next_prev', $atts, $this );
extract( $atts );

$prev = $next = $icon_left = $icon_right = $prev_format = $next_format = '';

// Sanitize atts.
$in_same_term  = ( 'true' == $atts['in_same_term'] ) ? true : false;
$same_term_tax = $same_term_tax ?: 'category';

$shortcode_class = array(
	'vcex-post-next-prev',
);

if ( 'true' === $expand ) {
	$shortcode_class[] = 'wpex-flex';
	$shortcode_class[] = 'wpex-justify-between';
}

if ( $align ) {
	$shortcode_class[] = 'text' . sanitize_html_class( $align );
}

if ( 'icon' === $link_format ) {
	$shortcode_class[] = 'vcex-icon-only';
}

if ( $bottom_margin ) {
	$shortcode_class[] = vcex_sanitize_margin_class( $bottom_margin, 'wpex-mb-' );
}

if ( ! empty( $atts['max_width'] ) ) {

	switch ( $atts['float'] ) {
		case 'left':
			$shortcode_class[] = 'wpex-mr-auto';
			break;
		case 'right':
			$shortcode_class[] = 'wpex-ml-auto';
			break;
		case 'center':
		default:
			$shortcode_class[] = 'wpex-mx-auto';
			break;
	}

}

if ( $atts['el_class'] ) {
	$shortcode_class[] = vcex_get_extra_class( $el_class );
}

if ( $atts['css_animation'] ) {
	$shortcode_class[] = vcex_get_css_animation( $atts['css_animation'] );
}

$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, 'vcex_post_next_prev', $atts );

// Inline CSS
$shortcode_style = vcex_inline_style( array(
	'font_size'          => $atts['font_size'],
	'line_height'        => $atts['line_height'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
	'max_width'          => $atts['max_width'],
) );

// Begin output.
$output = '<div class="' . esc_attr( $shortcode_class ) . '"' . $shortcode_style . '>';

	// Define icon HTML.
	if ( ! empty( $icon_style ) ) {

		// Sanitize icon spacing.
		$icon_margin_escaped = $icon_margin ? absint( $icon_margin ) : 10;

		// Define left/right directions for RTL.
		$left_dir = vcex_parse_direction( 'left' );
		$right_dir = vcex_parse_direction( 'right' );

		// Sanitize icon style.
		$icon_style_escaped = sanitize_html_class( $icon_style );

		// Left Icon.
		$icon_left_class = array(
			'ticon',
			'ticon-' . sanitize_html_class( $icon_style ) . '-' . vcex_parse_direction( 'left' ),
		);

		if ( 'icon' !== $link_format ) {
			$icon_left_class[] = 'wpex-mr-' . $icon_margin_escaped;
		}

		$icon_left = '<span class="' . esc_attr( implode( ' ', $icon_left_class ) ) . '"></span>';

		// Right Icon.
		$icon_right_class = array(
			'ticon',
			'ticon-' . sanitize_html_class( $icon_style ) . '-' . vcex_parse_direction( 'right' ),
		);

		if ( 'icon' !== $link_format ) {
			$icon_right_class[] = 'wpex-ml-' . $icon_margin_escaped;
		}

		$icon_right = '<span class="' . esc_attr( implode( ' ', $icon_right_class ) ) . '"></span>';

	}

	// Get correct button class.
	$button_class = vcex_get_button_classes( $atts['button_style'], $atts['button_color'] );

	if ( $atts['line_height'] ) {
		$button_class .= ' wpex-inherit-leading';
	}

	// Get button inline style.
	$button_style_escaped = vcex_inline_style( array(
		'min_width' => $button_min_width,
	) );

	// Display previous link.
	if ( 'true' == $previous_link ) {

		$get_prev = get_previous_post( $in_same_term, '', $same_term_tax );

		if ( $get_prev ) {

			switch ( $link_format ) {
				case 'icon':
					$prev_format_escaped = ( 'true' == $reverse_order ) ? $icon_right : $icon_left;
					break;
				case 'title':
					$title = get_the_title( $get_prev->ID );
					$prev_format_escaped = ( 'true' == $reverse_order ) ? $title . $icon_right : $icon_left . $title;
					break;
				case 'custom':

					$prev_text = esc_html( $atts['previous_link_custom_text'] );

					if ( ! $prev_text ) {
						$prev_text = esc_html__( 'Previous', 'total-theme-core' );
					}

					$prev_format_escaped = ( 'true' == $reverse_order ) ? $prev_text . $icon_right : $icon_left . $prev_text;
					break;
				default :
					$prev_format_escaped = '';
					break;
			}

			if ( $prev_format_escaped ) {

				$prev = '<a href="' . esc_url( get_permalink( $get_prev->ID ) ) . '" class="' . esc_attr( $button_class ) . ' wpex-text-center wpex-max-w-100"' . $button_style_escaped . '>' . $prev_format_escaped . '</a>';

				$prev = apply_filters( 'vcex_post_next_prev_link_next_html', $prev, $get_prev, $prev_format_escaped, $atts );

			}

		}

	}

	if ( 'true' == $next_link ) {

		$get_next = get_next_post( $in_same_term, '', $same_term_tax );

		if ( $get_next ) {

			switch ( $link_format ) {

				case 'icon':
					$next_format_escaped = ( 'true' == $reverse_order ) ? $icon_left : $icon_right;
					break;
				case 'title':
					$title = get_the_title( $get_next->ID );
					$next_format_escaped = ( 'true' == $reverse_order ) ? $icon_left . $title : $title . $icon_right;
					break;
				case 'custom':

					$next_text = esc_html( $atts['next_link_custom_text'] );

					if ( ! $next_text ) {
						$next_text = esc_html__( 'Next', 'total-theme-core' );
					}

					$next_format_escaped = ( 'true' == $reverse_order ) ? $icon_left . $next_text : $next_text . $icon_right;
					break;
				default:
					$next_format_escaped = '';
					break;

			}

			if ( $next_format_escaped ) {

				$next = '<a href="' . esc_url( get_permalink( $get_next->ID ) ) . '" class="' . esc_attr( $button_class ) . ' wpex-text-center wpex-max-w-100"' . $button_style_escaped . '>' . $next_format_escaped . '</a>';

				$next = apply_filters( 'vcex_post_next_prev_link_prev_html', $next, $get_next, $next_format_escaped, $atts );

			}

		}

	}

	// Sanitize col spacing.
	$col_spacing = $spacing ? absint( $spacing ) : 5;

	// Col Classes.
	$first_col_class = 'vcex-col wpex-inline-block wpex-mr-' . sanitize_html_class( $col_spacing );
	$second_col_class = 'vcex-col wpex-inline-block wpex-ml-' . sanitize_html_class( $col_spacing );

	if ( 'true' == $reverse_order ) {
		$output .= '<div class="' . esc_attr( $first_col_class ) . '">' . $next .'</div>';
		$output .= '<div class="' . esc_attr( $second_col_class ) . '">' . $prev .'</div>';
	} else {
		$output .= '<div class="' . esc_attr( $first_col_class ) . '">' . $prev .'</div>';
		$output .= '<div class="' . esc_attr( $second_col_class ) . '">' . $next .'</div>';
	}

$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;