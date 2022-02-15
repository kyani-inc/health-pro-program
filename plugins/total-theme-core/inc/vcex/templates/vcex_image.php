<?php
/**
 * vcex_image shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_image', $atts ) ) {
	return;
}

// Get shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_image', $atts, $this );

// Extract attributes for easier usage.
extract( $atts );

// Define vars
$output = $image = $attachment = $image_style = '';
$has_overlay = ( $overlay_style && 'none' !== $overlay_style );

// Get link attributes (get early incase we need to modify them for this specific shortcode).
$onclick_attrs = vcex_get_shortcode_onclick_attributes( $atts, 'vcex_image' );

// Get image attachment ID.
switch ( $source ) {
	case 'featured':
		if ( is_tax() || is_tag() || is_category() ) {
			$attachment = vcex_get_term_thumbnail_id( get_queried_object_id() );
		} else {
			$attachment = get_post_thumbnail_id( vcex_get_the_ID() );
		}
		break;
	case 'custom_field':
		if ( $custom_field_name ) {
			$custom_field_val = get_post_meta( vcex_get_the_ID(), $custom_field_name, true );
			if ( is_numeric( $custom_field_val ) ) {
				$attachment = $custom_field_val;
			} elseif( is_string( $custom_field_val ) ) {
				$image_url = $custom_field_val;
			}
		}
		break;
	case 'callback_function':
		if ( ! empty( $atts['callback_function'] ) && function_exists( $atts['callback_function'] ) ) {
			$callback_val = call_user_func( $atts['callback_function'] );
			if ( is_numeric( $callback_val ) ) {
				$attachment = $callback_val;
			} elseif( is_string( $callback_val ) ) {
				$image_url = $callback_val;
			}
		}
		break;
	case 'media_library':
	default:
		$attachment = $image_id;
		break;
}

// Define image classes.
$image_classes = array( 'wpex-align-middle' );

if ( '100%' === $atts['width'] ) {
	$image_classes[] = 'wpex-w-100';
}

if ( $atts['shadow'] ) {
	$image_classes[] = 'wpex-' . sanitize_html_class( $atts['shadow'] );
}

// Inline image style.
$image_style = vcex_inline_style( array(
	'border_radius' => $atts['border_radius'],
), false ); // don't include style tag.

// Set image title for overlays.
if ( ! empty( $atts['img_title'] ) ) {
	$atts['post_title'] = $atts['img_title'];
}

// Set image excerpt for overlays.
if ( ! empty( $atts['img_caption'] ) ) {
	$atts['post_excerpt'] = $atts['img_caption'];
	$atts['overlay_excerpt'] = $atts['img_caption'];
}

// Generate image html.
if ( $attachment ) {

	$img_args = array(
		'attachment' => $attachment,
		'size'       => $atts['img_size'],
		'crop'       => $atts['img_crop'],
		'width'      => $atts['img_width'],
		'height'     => $atts['img_height'],
		'style'      => $image_style,
		'class'      => $image_classes,
	);

	// Add width to SVG images to fix rendering issues.
	$attachment_mime_type = get_post_mime_type( $attachment );
	if ( 'image/svg+xml' === $attachment_mime_type ) {

		if ( empty( $atts['width'] ) ) {
			$img_args['attributes']['width'] = '9999';
		} else {
			$width_attribute = $atts['width'];
			$width_attribute = str_replace( 'px', '', $width_attribute );
			if ( is_numeric( $width_attribute ) ) {
				$img_args['attributes']['width'] = esc_attr( $width_attribute );
			} else {
				$img_args['attributes']['width'] = '9999';
			}
		}

	}

	if ( $atts['alt_attr'] ) {
		$img_args['alt'] = esc_attr( $atts['alt_attr'] );
	}

	$image = vcex_get_post_thumbnail( $img_args );

	// Lightbox image fallback.
	if ( empty( $onclick_attrs['href'] ) && in_array( $onclick, array( 'lightbox_image' ) ) ) {
		$onclick_attrs['href'] = vcex_get_lightbox_image( $attachment );
	}

} else {

	switch ( $source ) {
		case 'external':
			$image_url = $atts['external_image'];
			if ( $atts['alt_attr'] && empty( $atts['post_title'] ) ) {
				$atts['post_title'] = $atts['alt_attr']; // for overlays.
			}
			break;
		case 'author_avatar':
			$image_url = get_avatar_url( get_post(), array( 'size' => $atts['img_width'] ) );
			break;
		case 'user_avatar':
			$image_url = get_avatar_url( wp_get_current_user(), array( 'size' => $atts['img_width'] ) );
			break;
		default:
			if ( ! empty( $custom_field_val ) ) {
				$image_url = $custom_field_val;
			}
			break;
	}

	// Display non-attachment image if URL isn't empty and it's a string.
	if ( ! empty( $image_url ) && is_string( $image_url ) ) {

		// Define image attributes.
		$image_attrs = array(
			'src'   => set_url_scheme( esc_url( $image_url ) ),
			'class' => $image_classes,
			'style' => $image_style,
			'alt'   => ! empty( $atts['alt_attr'] ) ? esc_attr( $atts['alt_attr'] ) :  '',
		);

		// Add width to SVG images to fix rendering issues.
		if ( false !== strpos( $image_url, '.svg' ) ) {

			if ( empty( $atts['width'] ) ) {
				$image_attrs['width'] = '99999';
			} else {
				$width_attribute = $atts['width'];
				$width_attribute = str_replace( 'px', '', $width_attribute );
				$image_attrs['width'] = esc_attr( $width_attribute );
			}

		}

		// Set image output.
		$image = '<img ' . trim( vcex_parse_html_attributes( $image_attrs ) )  . '>';

	}

}

// Return if no image has been added.
if ( empty( $image ) ) {
	if ( vcex_vc_is_inline() && function_exists( 'wpex_get_placeholder_image' ) ) {
		$image = wpex_get_placeholder_image();
	} else {
		return;
	}
}

// Define wrap classes.
$wrap_classes = array(
	'vcex-image',
	'vcex-module',
	'wpex-clr'
);

if ( $bottom_margin_class = vcex_sanitize_margin_class( $atts['bottom_margin'], 'wpex-mb-' ) ) {
	$wrap_classes[] = $bottom_margin_class;
}

if ( $atts['align'] ) {
	$wrap_classes[] = 'text' . sanitize_html_class( $atts['align'] );
}

if ( $css_animation_class = vcex_get_css_animation( $atts['css_animation'] ) ) {
	$wrap_classes[] = $css_animation_class;
}

if ( $visibility_class = sanitize_html_class( $atts['visibility'] ) ) {
	$wrap_classes[] = $visibility_class;
}

if ( $el_class ) {
	$wrap_classes[] = vcex_get_extra_class( $el_class );
}

$wrap_classes = vcex_parse_shortcode_classes( implode( ' ', $wrap_classes ), 'vcex_image', $atts );

// Link Setup.
if ( ! empty( $onclick_attrs['href'] ) ) {

	// Define post_permalink for use with Image overlay styles.
	$atts['post_permalink'] = esc_url( $onclick_attrs['href'] );

	// Define lightbox data for use with overlay styles.
	if ( $has_overlay && ( 'popup' == $onclick || false !== strpos( $onclick, 'lightbox_' ) ) ) {

		$atts['lightbox_link'] = $onclick_attrs['href'];

		$lightbox_settings = vcex_get_shortcode_onclick_lightbox_settings( $atts );

		if ( $lightbox_settings ) {
			$parsed_data = array();
			foreach ( $lightbox_settings as $k => $v ) {
				$parsed_data[] = 'data-' . $k . '="' . $v . '"';
			}
			$atts['lightbox_data'] = $parsed_data;
			if ( ! empty( $lightbox_settings['gallery'] ) ) {
				$atts['lightbox_class']= 'wpex-lightbox-gallery';
			}

		}

	}

}

$wrap_inline_style = vcex_inline_style( array(
	'animation_duration' => $animation_duration,
	'animation_delay'    => $animation_delay,
) );

// Start output.
$output .= '<figure class="' . esc_attr( $wrap_classes ) . '"' . $wrap_inline_style . '>';

	$inner_classes = array(
		'vcex-image-inner',
		'wpex-relative',
	);

	if ( empty( $atts['width'] ) || '100%' !== $atts['width'] ) {
		$inner_classes[] = 'wpex-inline-block';
	}

	if ( $img_filter ) {
		$inner_classes[] = vcex_image_filter_class( $img_filter );
	}

	if ( $img_hover_style ) {
		$inner_classes[] = vcex_image_hover_classes( $img_hover_style );
	}

	if ( $has_overlay ) {
		$inner_classes[] = vcex_image_overlay_classes( $overlay_style );
	}

	if ( $hover_animation ) {
		$inner_classes[] = vcex_hover_animation_class( $hover_animation );
		vcex_enque_style( 'hover-animations' );
	}

	if ( $css ) {
		$inner_classes[] = vcex_vc_shortcode_custom_css_class( $css );
	}

	$inner_style_args = array();

	if ( ! empty( $atts['width'] ) && '100%' !== $atts['width'] ) {
		$inner_style_args['max_width'] = $atts['width'];
	}

	if ( $has_overlay && ! empty( $atts['border_radius'] ) ) {
		$inner_style_args['border_radius'] = $atts['border_radius'];
	}

	$inner_style = vcex_inline_style( $inner_style_args );

	// Setup post data which is used for image overlays.
	if ( $attachment ) {
		global $post;
		$get_post = get_post( $attachment );
		setup_postdata( $get_post );
		$post = $get_post;
	}

	// Begin module output.
	$output .= '<div class="' . esc_attr( implode( ' ', $inner_classes ) ) . '"' . $inner_style . '>';

		if ( ! empty( $onclick_attrs['href'] ) ) {
			$output .= '<a' . vcex_parse_html_attributes( $onclick_attrs ) . '>';
		}

		$output .= $image;

		if ( $has_overlay ) {
			ob_start();
			vcex_image_overlay( 'inside_link', $overlay_style, $atts );
			$output .= ob_get_clean();
		}

		if ( ! empty( $onclick_attrs['href'] ) ) {
			if ( 'true' == $onclick_video_overlay_icon ) {
				$output .= '<div class="overlay-icon"><span>&#9658;</span></div>';
			}
			$output .= '</a>';
		}

		if ( $has_overlay ) {
			ob_start();
			vcex_image_overlay( 'outside_link', $overlay_style, $atts );
			$output .= ob_get_clean();
		}

	$output .= '</div>'; // close inner class.

	if ( 'true' == $caption ) {
		if ( ! empty( $atts['img_caption'] ) ) {
			$caption_text = $atts['img_caption'];
		} else {
			$caption_text = wp_get_attachment_caption( $attachment );
		}
		if ( $caption_text ) {
			$output .= '<figcaption class="wpex-mt-10">' . do_shortcode( wp_kses_post( $caption_text ) ) . '</figcaption>';
		}
	}

	wp_reset_postdata();

$output .= '</figure>';

// @codingStandardsIgnoreLine.
echo $output;