<?php
/**
 * vcex_button shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_button', $atts ) ) {
	return;
}

// Define output.
$output = '';

// Get shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_button', $atts, $this );
extract( $atts );

// Sanitize & declare vars.
$button_data = array();

// Define URL.
$url = $url ?: '#';

// Internal links.
if ( 'internal_link' === $atts['onclick'] ) {
	$internal_link = vcex_build_link( $internal_link );
	if ( ! empty( $internal_link['url'] ) ) {
		$url = $internal_link[ 'url' ];
	} else {
		$url = '#';
	}
}

// Sanitize content.
if( 'custom_field' === $text_source ) {
	$content = $text_custom_field ? get_post_meta( vcex_get_the_ID(), $text_custom_field, true ) : '';
} elseif( 'callback_function' === $text_source ) {
	$content = ( $text_callback_function && function_exists( $text_callback_function ) ) ? call_user_func( $text_callback_function ) : '';
} else {
	$content = ! empty( $content ) ? $content : esc_html__( 'Button Text', 'total' );
}

// Don't show button if content is empty.
if ( empty( $content ) ) {
	return;
}

// Button Classes.
$button_classes = array(
	'vcex-button'
);

$button_classes[] = vcex_get_button_classes( $style, $color, $size, $align );

if ( $state ) {
	$button_classes[] = sanitize_html_class( $state );
}

if ( $bottom_margin ) {
	$button_classes[] = vcex_sanitize_margin_class( $bottom_margin, 'wpex-mb-' );
}

if ( $layout ) {
	$button_classes[] = $layout;
}

if ( $classes ) {
	$button_classes[] = vcex_get_extra_class( $classes );
}

if ( $hover_animation ) {
	$button_classes[] = vcex_hover_animation_class( $hover_animation );
	vcex_enque_style( 'hover-animations' );
}

if ( $css_animation && 'none' != $css_animation && ! $css_wrap ) {
	$button_classes[] = vcex_get_css_animation( $css_animation );
}

if ( $shadow_class = vcex_parse_shadow_class( $atts['shadow'] ) ) {
	$button_classes[] = $shadow_class;
}

if ( $width ) {
	$button_classes[] = 'wpex-flex-shrink-0';
}

if ( $visibility ) {
	$button_classes[] = vcex_parse_visibility_class( $visibility );
}

// LocalScroll
if ( 'local_scroll' === $atts['onclick'] ) {
	$button_classes[] = 'local-scroll-link';
}

// Go back link
elseif ( 'go_back' === $atts['onclick'] ) {
	$url = '#';
	$button_classes[] = 'wpex-go-back';
}

// Toggle Element
elseif ( 'toggle_element' === $atts['onclick'] ) {
	$button_classes[] = 'wpex-toggle-element-trigger';
}

// Custom field link.
elseif ( 'custom_field' === $atts['onclick'] ) {
	if ( ! empty( $url_custom_field ) && is_string( $url_custom_field ) ) {
		$url = get_post_meta( vcex_get_the_ID(), $url_custom_field, true );
	}
	if ( empty( $url ) ) {
		return; // Lets not show any button if the custom field is empty
	}
}

// Callback function link.
elseif ( 'callback_function' === $atts['onclick'] && function_exists( $url_callback_function ) ) {
	$url = call_user_func( $url_callback_function );
	if ( ! $url ) {
		return; // Lets not show any button if the callback is empty
	}
}

// Image link.
elseif ( 'image' === $atts['onclick'] || 'lightbox' === $atts['onclick'] ) {
	$url = $image_attachment ? wp_get_attachment_url( $image_attachment ) : $url;
}

// Lightbox classes and data.
if ( 'lightbox' === $atts['onclick'] || 'image' === $atts['onclick'] ) {

	// Enqueue lightbox scripts
	vcex_enqueue_lightbox_scripts();

	// Lightbox gallery.
	if ( 'true' == $lightbox_post_gallery && $gallery_ids = vcex_get_post_gallery_ids() ) {
		$lightbox_gallery = $gallery_ids;
	}

	if ( $lightbox_gallery ) {
		$button_classes[] = 'wpex-lightbox-gallery';
		$gallery_ids = is_array( $lightbox_gallery ) ? $lightbox_gallery : explode( ',', $lightbox_gallery );
		if ( $gallery_ids && is_array( $gallery_ids ) ) {
			$button_data[] = 'data-gallery="' . vcex_parse_inline_lightbox_gallery( $gallery_ids ) . '"';
		}
	}

	// Iframe lightbox.
	elseif ( 'iframe' === $lightbox_type ) {
		$button_classes[] = 'wpex-lightbox';
		$button_data[]    = 'data-type="iframe"';
	}

	// Image lightbox.
	elseif ( 'image' === $lightbox_type ) {
		$button_classes[] = 'wpex-lightbox';
		if ( $image_attachment ) {
			$url = wp_get_attachment_url( $image_attachment );
		}
	}

	// Video embed lightbox.
	elseif ( 'video_embed' === $lightbox_type ) {
		$url = vcex_get_video_embed_url( $url );
		$button_classes[] = 'wpex-lightbox';
	}

	// Html5 lightbox.
	elseif ( 'html5' === $lightbox_type ) {
		$lightbox_video_html5_webm = $lightbox_video_html5_webm ?: $url;
		$button_classes[] = 'wpex-lightbox';
		if ( $lightbox_video_html5_webm ) {
			$url = $lightbox_video_html5_webm;
		}
	}

	// Auto-detect lightbox ($url can't be empty).
	elseif ( $url ) {
		$button_classes[] = 'wpex-lightbox';
	}

	// Disable title.
	if ( 'false' == $lightbox_title ) {
		$button_data[] = 'data-show_title="false"';
	}

	// Add lightbox dimensions.
	if ( in_array( $lightbox_type, array( 'video', 'video_embed', 'url', 'html5', 'iframe', 'inline' ) ) ) {
		$lightbox_dims = vcex_parse_lightbox_dims( $lightbox_dimensions, 'array' );
		if ( $lightbox_dims ) {
			$button_data[] = 'data-width="' . esc_attr( $lightbox_dims['width'] ) . '"';
			$button_data[] = 'data-height="' . esc_attr( $lightbox_dims['height'] ) . '"';
		}
	}

}

// Custom data attributes.
if ( $data_attributes ) {
	$data_attributes = explode( ',', $data_attributes );
	if ( is_array( $data_attributes ) ) {
		foreach( $data_attributes as $attribute ) {
			if ( false !== strpos( $attribute, '|' ) ) {
				$attribute = explode( '|', $attribute );
				$button_data[] = 'data-' . esc_attr( $attribute[0] ) .'="' . esc_attr( do_shortcode( $attribute[1] ) ) . '"';
			} else {
				$button_data[] = 'data-' . esc_attr( $attribute );
			}
		}
	}
}

// Wrap classes.
$wrap_classes = array();

if ( 'center' === $align ) {
	$wrap_classes[] = 'textcenter';
}

if ( 'block' === $layout ) {
	$wrap_classes[] = 'theme-button-block-wrap';
	$wrap_classes[] = 'wpex-block';
	$wrap_classes[] = 'wpex-clear';
}

if ( 'expanded' === $layout ) {
	$wrap_classes[]   = 'theme-button-expanded-wrap';
	$button_classes[] = 'expanded';
}

if ( $wrap_classes ) {
	$wrap_classes[] = 'theme-button-wrap';
	$wrap_classes[] = 'wpex-clr';
	$wrap_classes   = implode( ' ', $wrap_classes );
}

$wrap_classes = vcex_parse_shortcode_classes( $wrap_classes, 'vcex_button', $atts );

// Custom Style.
$inline_style = vcex_inline_style( array(
	'background'         => $custom_background,
	'padding'            => $font_padding,
	'color'              => $custom_color,
	'border'             => $border,
	'font_size'          => $font_size,
	'font_weight'        => $font_weight,
	'letter_spacing'     => $letter_spacing,
	'border_radius'      => $border_radius,
	'margin'             => $margin,
	'width'              => $width,
	'text_transform'     => $text_transform,
	'font_family'        => $font_family,
	'animation_delay'    => $animation_delay,
	'animation_duration' => $animation_duration,
), false );

if ( $inline_style ) {
	$inline_style = ' style="'. esc_attr( $inline_style ) .'"';
}

// Custom hovers.
$hover_data = array();
if ( $custom_hover_background ) {
	$hover_data['background'] = esc_attr( vcex_parse_color( $custom_hover_background ) );
}
if ( $custom_hover_color ) {
	$hover_data['color'] = esc_attr( vcex_parse_color( $custom_hover_color ) );
}
if ( $hover_data ) {
	$button_data[] = "data-wpex-hover='" . htmlspecialchars( wp_json_encode( $hover_data ) ) . "'";
}

// Define button icon_classes.
$icon_left  = vcex_get_icon_class( $atts, 'icon_left' );
$icon_right = vcex_get_icon_class( $atts, 'icon_right' );

// Icon right style.
if ( $icon_right ) {
	$icon_right_style = vcex_inline_style ( array(
		'padding_left' => $icon_right_padding,
	) );
}

// Responsive styles.
$unique_classname = vcex_element_unique_classname();

$el_responsive_styles = array(
	'font_size' => $atts['font_size'],
);

$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

if ( $responsive_css ) {
	$button_classes[] = $unique_classname;
	$output .= '<style>' . $responsive_css . '</style>';
}

// Turn arrays into strings.
$button_classes = implode( ' ', $button_classes );
$button_data    = implode( ' ', $button_data );

// Open CSS wrapper.
if ( $css_wrap ) {

	$css_wrap_style = vcex_inline_style( array(
		'animation_delay' => ( $css_animation && 'none' !== $css_animation ) ? $animation_delay : '',
	) );

	$output .= '<div class="' . vcex_vc_shortcode_custom_css_class( $css_wrap ) . vcex_get_css_animation( $css_animation ) . ' wpex-clr"' . $css_wrap_style . '>';

}

	// Open wrapper for specific button styles.
	if ( $wrap_classes ) {
		$output .= '<div class="' . esc_attr( $wrap_classes ) . '">';
	}

		$href = esc_url( do_shortcode( $url ) );

		$link_attrs = array(
			'id'       => vcex_get_unique_id( $unique_id ),
			'href'     => $href,
			'title'    => $title ? esc_attr( do_shortcode( $title ) ) : '',
			'class'    => esc_attr( $button_classes ),
			'target'   => $target,
			'style'    => $inline_style,
			'rel'      => $rel,
			'data'     => $button_data,
			'download' => ( 'true' == $download_attribute ) ? 'download' : '',
		);

		if ( 'toggle_element' === $atts['onclick'] ) {
			$link_attrs['aria-expanded'] = ( 'active' === $state ) ? 'true' : 'false';
			$link_attrs['aria-controls'] = $href;
		}

		// Open Link.
		$output .= '<a' . vcex_parse_html_attributes( $link_attrs ) . '>';

			// Open inner span.
			$output .= '<span class="theme-button-inner">';

				// Left Icon.
				if ( $icon_left ) {

					vcex_enqueue_icon_font( $icon_type, $icon_left );

					$icon_left_style = vcex_inline_style ( array(
						'padding_right' => $icon_left_padding,
					) );

					$attrs = array(
						'class' => array(
							'vcex-icon-wrap',
							'theme-button-icon-left',
						),
						'style' => $icon_left_style,
					);

					if ( $icon_left_transform ) {
						$attrs['class'][] = 'wpex-transition-transform wpex-duration-200';
						$attrs['data-wpex-hover'] = htmlspecialchars( wp_json_encode( array(
							'parent'    => '.vcex-button',
							'transform' => 'translateX(' . vcex_validate_font_size( $icon_left_transform ) . ')',
						) ) );
					}

					$output .= '<span' . vcex_parse_html_attributes( $attrs ) . '>';

						$output .= '<span class="' . esc_attr( $icon_left ) . '" aria-hidden="true"></span>';

					$output .= '</span>';

				}

				// Text.
				$output .= do_shortcode( $content );

				// Icon Right.
				if ( $icon_right ) {

					vcex_enqueue_icon_font( $icon_type, $icon_right );

					$attrs = array(
						'class' => array(
							'vcex-icon-wrap',
							'theme-button-icon-right',
						),
						'style' => $icon_right_style,
					);

					if ( $icon_right_transform ) {
						$attrs['class'][] = 'wpex-transition-transform wpex-duration-200';
						$attrs['data-wpex-hover'] = htmlspecialchars( wp_json_encode( array(
							'parent'    => '.vcex-button',
							'transform' => 'translateX(' . vcex_validate_font_size( $icon_right_transform ) . ')',
						) ) );
					}

					$output .= '<span' . vcex_parse_html_attributes( $attrs ) . '>';

						$output .= '<span class="' . esc_attr( $icon_right ) . '" aria-hidden="true"></span>';

					$output .= '</span>';

				}

			// Close inner span.
			$output .= '</span>';

		// Close link.
		$output .= '</a>';

	// Close wrapper for specific button styles.
	if ( $wrap_classes ) {
		$output .=  '</div>';
	}

// Close css wrap div.
if ( $css_wrap ) {
	$output .= '</div>';
}

// @codingStandardsIgnoreLine
echo $output . ' ';