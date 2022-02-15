<?php
/**
 * vcex_list_item shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_list_item', $atts ) ) {
	return;
}

// Get and extract shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_list_item', $atts, $this );
extract( $atts );

// Sanitize content/text.
switch ( $text_source ) {
	case 'custom_field':
		$content = $text_custom_field ? get_post_meta( vcex_get_the_ID(), $text_custom_field, true ) : '';
		break;
	case 'callback_function':
		$content = ( $text_callback_function && function_exists( $text_callback_function ) ) ? call_user_func( $text_callback_function ) : '';
		break;
}

// Content is required.
if ( empty( $content ) ) {
	return;
}

// Output var.
$output = '';

// Get link.
$url = $atts['url'] ?? '';
if ( $link ) {
	$link_url_temp = $link;
	$link_url = vcex_get_link_data( 'url', $link_url_temp );
	if ( $link_url ) {
		$url = $link_url;
		$link_title = $atts['link_title'] ?? '';
		$link_target = $atts['link_target'] ?? '';
	}
}

// Classes & data.
$wrap_attrs = array(
	'id'    => vcex_get_unique_id( $unique_id ),
	'class' => '', // so that class is added before inline style
);

// Wrap classes.
$wrap_class = array(
	'vcex-module',
	'vcex-list_item',
	'wpex-max-w-100',
	'wpex-clr',
);

if ( $bottom_margin ) {
	$wrap_class[] = vcex_sanitize_margin_class( $bottom_margin, 'wpex-mb-' );
} else {
	$wrap_class[] = 'wpex-mb-5';
}

if ( $css_animation_class = vcex_get_css_animation( $css_animation ) ) {
	$wrap_class[] = $css_animation_class;
}

if ( $visibility ) {
	$wrap_class[] = vcex_parse_visibility_class( $visibility );
}

if ( $css ) {
	$wrap_class[] = vcex_vc_shortcode_custom_css_class( $css );
}

if ( $text_align ) {
	$wrap_class[] = 'text' . sanitize_html_class( $text_align );
}

if ( 'true' == $responsive_font_size ) {

	if ( $font_size && $min_font_size ) {

		// Convert em font size to pixels
		if ( strpos( $font_size, 'em' ) !== false ) {
			$font_size = str_replace( 'em', '', $font_size );
			$font_size = $font_size * vcex_get_body_font_size();
		}

		// Convert em min-font size to pixels
		if ( strpos( $min_font_size, 'em' ) !== false ) {
			$min_font_size = str_replace( 'em', '', $min_font_size );
			$min_font_size = $min_font_size * vcex_get_body_font_size();
		}

		// Add wrap classes and data.
		$wrap_class[] = 'wpex-responsive-txt';
		$wrap_attrs['data-max-font-size'] = absint( $font_size );
		$wrap_attrs['data-min-font-size'] = absint( $min_font_size );

		// Enqueue scripts.
		wp_enqueue_script( 'vcex-responsive-text' );

	}

} else {

	// Responsive styles.
	$unique_classname = vcex_element_unique_classname();

	$el_responsive_styles = array(
		'font_size' => $font_size,
	);

	$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

	if ( $responsive_css ) {
		$wrap_class[] = $unique_classname;
		$output .= '<style>' . $responsive_css . '</style>';
	}

}

// Add wrapper styles.
$wrap_attrs['style'] = vcex_inline_style( array(
	'font_family'        => $atts['font_family'],
	'font_size'          => $font_size,
	'color'              => $atts['font_color'],
	'font_weight'        => $atts['font_weight'],
	'font_style'         => $atts['font_style'],
	'line_height'        => $atts['line_height'],
	'background_color'   => $atts['background_color'],
	'border_color'       => $atts['border_color'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
) );

$extra_classes = vcex_get_shortcode_extra_classes( $atts, 'vcex_list_item' );

if ( $extra_classes ) {
	$wrap_class = array_merge( $wrap_class, $extra_classes );
}

// Turn classes into string, apply filters and sanitize.
$wrap_attrs['class'] = esc_attr( vcex_parse_shortcode_classes( $wrap_class, 'vcex_list_item', $atts ) );

// Begin output.
$output .= '<div' . vcex_parse_html_attributes( $wrap_attrs ) . '>';

	if ( $url ) {

		$link_attrs = array(
			'href'   => esc_url( do_shortcode( $url ) ),
			'title'  => do_shortcode( vcex_get_link_data( 'title', $link_url_temp, $link_title ) ),
			'target' => vcex_get_link_data( 'target', $link_url_temp, $link_target ),
			'rel'    => vcex_get_link_data( 'rel', $link_url_temp ),
			'class'  => 'vcex-list-item-link wpex-no-underline',
		);

		if ( $font_color ) {
			$link_attrs['class'] .= ' wpex-inherit-color';
		}

		$output .= '<a '. vcex_parse_html_attributes( $link_attrs ) . '>';

	}

	$inner_classes = apply_filters( 'vcex_list_item_inner_class', array(
		'vcex-list-item-inner',
		'wpex-inline-flex',
		'wpex-flex-no-wrap',
	) );

	if ( $atts['flex_align'] && 'start' !== $atts['flex_align'] ) {
		$inner_classes[] = 'wpex-items-' . sanitize_html_class( $atts['flex_align'] );
	}

	$output .= '<div class="' . esc_attr( implode( ' ', $inner_classes ) ) . '">';

		if ( $icon || $icon_alternative_classes ) {

			$icon_classes = array(
				'vcex-list-item-icon',
			);

			if ( $icon_spacing ) {
				$icon_classes[] = 'wpex-mr-' . sanitize_html_class( absint( $icon_spacing ) );
			} else {
				$icon_classes[] = 'wpex-mr-10';
			}

			$style_args = array();

			if ( $atts['margin_right'] ) {
				if ( is_rtl() ) {
					$style_args['margin_left'] = $atts['margin_right'];
				} else {
					$style_args['margin_right'] = $atts['margin_right'];
				}
			}

			// List item icon.
			$output .= '<div class="' . esc_attr( implode( ' ', $icon_classes ) ) . '"' . vcex_inline_style( $style_args ) . '>';

				// List item icon wrap.
				if ( ! $icon_height && $icon_size ) {
					$icon_height = $icon_size;
				}

				$style_args = array(
					'background'    => $atts['icon_background'],
					'width'         => $atts['icon_width'],
					'border_radius' => $atts['icon_border_radius'],
					'height'        => $atts['icon_height'],
					'font_size'     => $atts['icon_size'],
					'color'         => $atts['color'],
				);

				$output .= '<div class="vcex-icon-wrap wpex-inline-flex wpex-justify-center wpex-items-center"' . vcex_inline_style( $style_args ) . '>';

				if ( $icon_alternative_classes ) {

					$output .= '<span class="' . esc_attr( do_shortcode( $icon_alternative_classes ) ) . '"></span>';

				} else {

					$icon_class = vcex_get_icon_class( $atts, 'icon' );

					if ( $icon_class ) {

						vcex_enqueue_icon_font( $icon_type, $icon_class ); // load font icon CSS

						$output .= '<span class="' . esc_attr( $icon_class ) . '"></span>';

					}

				}

				$output .= '</div>';

			$output .= '</div>';

		}

		$output .= '<div class="vcex-list-item-text vcex-content wpex-flex-grow">';

			$output .= wp_kses_post( do_shortcode( $content ) );

		$output .= '</div>';

	$output .= '</div>';

	if ( $url ) {

		$output .= '</a>';

	}

$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;