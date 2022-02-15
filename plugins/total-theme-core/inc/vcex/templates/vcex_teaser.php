<?php
/**
 * vcex_teaser shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_teaser', $atts ) ) {
	return;
}

// Define output var.
$output = '';

// Get shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_teaser', $atts, $this );

// Generate url.
$url = $atts['url'] ?? '';
if ( $url && '||' !== $url && '|||' !== $url ) {

	// Deprecated attributes.
	$url_title = $url_title ?? '';
	$url_target = $url_target ?? '';

	// Get link field attributes.
	$url_atts = vcex_build_link( $url );
	if ( ! empty( $url_atts['url'] ) ) {
		$url        = $url_atts['url'] ?? $url;
		$url_title  = $url_atts['title'] ?? $url_title;
		$url_target = $url_atts['target'] ?? $url_target;
	}

	// Title fallback (shouldn't be an empty title).
	$url_title = $url_title ?: $atts['heading'];

	// Link classes.
	$url_classes = 'wpex-no-underline';

	// Sanitize target.
	if ( 'true' === $atts['url_local_scroll'] ) {
		$url_classes .= ' local-scroll-link';
		$url_target = '';
	}

	$url_attrs = array(
		'href'   => esc_url( do_shortcode( $url ) ),
		'title'  => esc_attr( do_shortcode( $url_title ) ),
		'class'  => esc_attr( $url_classes ),
		'target' => $url_target,
		'rel'    => $url_atts['rel'] ?? '',
	);

	$url_output = '<a' . vcex_parse_html_attributes( $url_attrs ) . '>';

} // End url sanitization

// Add main Classes.
$wrap_classes = array(
	'vcex-module',
	'vcex-teaser'
);

if ( $atts['style'] ) {
	$wrap_classes[] = 'vcex-teaser-' . sanitize_html_class( $atts['style'] );
}

if ( $atts['bottom_margin'] ) {
	$wrap_classes[] = vcex_sanitize_margin_class( $atts['bottom_margin'], 'wpex-mb-' );
}

if ( $atts['shadow'] ) {
	$wrap_classes[] = vcex_parse_shadow_class( $atts['shadow'] );
}

if ( $atts['classes'] ) {
	$wrap_classes[] = vcex_get_extra_class( $atts['classes'] );
}

if ( $atts['text_align'] ) {
	$wrap_classes[] = 'text' . sanitize_html_class( $atts['text_align'] );
}

if ( $atts['visibility'] ) {
	$wrap_classes[] = vcex_parse_visibility_class( $atts['visibility'] );
}

if ( ! empty( trim( $atts['hover_animation'] ) ) ) {
	$wrap_classes[] = vcex_hover_animation_class( $atts['hover_animation'] );
	vcex_enque_style( 'hover-animations' );
}

if ( 'two' === $atts['style'] ) {
	$wrap_classes[] = 'wpex-bg-gray-100';
	$wrap_classes[] = 'wpex-p-20';
	if ( empty( $atts['border_radius'] ) ) {
		$wrap_classes[] = 'wpex-rounded';
	}
} elseif ( 'three' === $atts['style'] ) {
	$wrap_classes[] = 'wpex-bg-gray-100';
} elseif ( 'four' === $atts['style'] ) {
	$wrap_classes[] = 'wpex-border wpex-border-solid wpex-border-gray-200';
}

if ( $atts['css'] ) {
	$wrap_classes[] = vcex_vc_shortcode_custom_css_class( $atts['css'] );
}

// Add inline style for main div (uses special code because there are added checks).
$wrap_style = array();

if ( $atts['padding'] && ( 'two' === $atts['style'] || 'three' === $atts['style'] ) ) {
	$wrap_style['padding'] = $atts['padding'];
}

if ( $atts['background'] ) {
	if ( 'two' === $atts['style']  || ( 'three' === $atts['style'] && '' === $content_background ) )
	$wrap_style['background'] = $atts['background'];
}

if ( $atts['border_color'] ) {
	$wrap_style['border_color'] = $atts['border_color'];
}

if ( $atts['border_radius'] ) {
	$wrap_style['border_radius'] = $atts['border_radius'];
}

$wrap_style = vcex_inline_style( $wrap_style );

// Media and Content classes for different styles.
$media_classes = array( 'vcex-teaser-media' );
$content_classes = 'vcex-teaser-content wpex-clr';

if ( in_array( $atts['style'], array( 'three', 'four' ) )
	|| ( ! empty( $atts['shadow'] ) ) && in_array( $atts['style'], array( '', 'one' ) )
) {
	$content_classes .= ' wpex-p-20';
} elseif ( empty( $atts['img_bottom_margin'] ) ) {
	$media_classes[] = 'wpex-mb-20';
}

if ( $img_bottom_margin_class = vcex_parse_margin_class( $atts['img_bottom_margin'], 'mb-' ) ) {
	$media_classes[] = $img_bottom_margin_class;
}

// Parse wrap classes.
$wrap_classes = vcex_parse_shortcode_classes( $wrap_classes, 'vcex_teaser', $atts );

/*-------------------------------------------------------------------------------*/
/* [ Begin Output ]
/*-------------------------------------------------------------------------------*/

// Open css_animation element (added in it's own element to prevent conflicts with inner styling).
if ( $atts['css_animation'] && 'none' !== $atts['css_animation'] ) {

	$animation_classes = array( trim( vcex_get_css_animation( $atts['css_animation'] ) ) );

	if ( $atts['visibility'] ) {
		$animation_classes[] = vcex_parse_visibility_class( $atts['visibility'] );
	}

	$css_animation_style = vcex_inline_style( array(
		'animation_delay' => $atts['animation_delay'],
		'animation_duration' => $atts['animation_duration'],
	) );

	$output .= '<div class="' . esc_attr( implode( ' ', $animation_classes ) ) . '"' . $css_animation_style . '>';

}

// Open main module element.
$output .= '<div class="' . esc_attr( $wrap_classes ) . '"' . vcex_get_unique_id( $atts['unique_id'] ) . $wrap_style . '>';

	/*-------------------------------------------------------------------------------*/
	/* [ Display Video ]
	/*-------------------------------------------------------------------------------*/
	if ( $atts['video'] ) {

		if ( $atts['img_border_radius'] ) {
			$img_border_radius_class = vcex_parse_border_radius_class( $atts['img_border_radius'] );
			if ( $img_border_radius_class ) {
				$media_classes[] = $img_border_radius_class;
				$media_classes[] = 'wpex-overflow-hidden';
			}
		}

		$output .= '<div class="' . esc_attr( implode( ' ', $media_classes ) ) . ' responsive-video-wrap">';

			if ( apply_filters( 'wpex_has_oembed_cache', true ) ) { // filter added for testing purposes.
				global $wp_embed;
				if ( $wp_embed && is_object( $wp_embed ) ) {
					$video_html = $wp_embed->shortcode( array(), $atts['video'] );
					// Check if output is a shortcode because if the URL is self hosted
					// it will pass through wp_embed_handler_video which returns a video shortcode
					if ( ! empty( $video_html )
						&& is_string( $video_html )
						&& false !== strpos( $video_html, '[video' )
					) {
						$video_html = do_shortcode( $video_html );
					}
					$output .= $video_html;
				}
			} else {
				$video_html = wp_oembed_get( $video );
				if ( ! empty( $video_html ) && ! is_wp_error( $video_html ) ) {
					$output .= '<div class="wpex-responsive-media">' . $video_html . '</div>';
				}
			}

		$output .= '</div>';

	}

	/*-------------------------------------------------------------------------------*/
	/* [ Image ]
	/*-------------------------------------------------------------------------------*/
	if ( $atts['image'] || $atts['external_image'] ) {

		// Image classes.
		if ( $atts['img_filter'] ) {
			$media_classes[] = vcex_image_filter_class( $atts['img_filter'] );
		}

		if ( $atts['img_hover_style'] ) {
			$media_classes[] = vcex_image_hover_classes( $atts['img_hover_style'] );
		}

		if ( $atts['img_align'] ) {
			$media_classes[] = 'text' . sanitize_html_class( $atts['img_align'] );
		}

		if ( 'stretch' === $atts['img_style'] ) {
			$media_classes[] = 'stretch-image';
		}

		$output .= '<figure class="' . esc_attr( implode( ' ', $media_classes ) ) . '">';

			if ( ! empty( $url_output ) ) {
				$output .= $url_output;
			}

			$image_class = 'wpex-align-middle';

			if ( $atts['img_border_radius'] ) {
				$img_border_radius_class = vcex_parse_border_radius_class( $atts['img_border_radius'] );
				if ( $img_border_radius_class ) {
					$image_class .= ' ' . $img_border_radius_class;
				}
			}

			if ( 'media_library' === $atts['image_source'] ) {

				$output .= vcex_get_post_thumbnail( array(
					'attachment' => $atts['image'],
					'crop'       => $atts['img_crop'],
					'size'       => $atts['img_size'],
					'width'      => $atts['img_width'],
					'height'     => $atts['img_height'],
					'alt'        => $atts['image_alt'] ?: $atts['heading'],
					'class'      => $image_class,
				) );

			} elseif ( 'external' === $atts['image_source'] ) {

				if ( is_string( $atts['external_image'] ) ) {
					$external_image = trim( $atts['external_image'] );
					if ( $external_image ) {
						$output .= '<img src="' . esc_url( $external_image ) . '" loading="lazy" class="' . esc_attr( $image_class ) . '">';
					}
				}

			}

			if ( ! empty( $url_output ) ) {
				$output .= '</a>';
			}

		$output .= '</figure>';

	} // End image output.

	/*-------------------------------------------------------------------------------*/
	/* [ Details ]
	/*-------------------------------------------------------------------------------*/
	if ( $content || $atts['heading'] ) {

		// Content area
		$content_style = array(
			'margin'     => $atts['content_margin'],
			'padding'    => $atts['content_padding'],
			'background' => $atts['content_background'],
		);

		if ( $atts['border_radius'] && ( 'three' === $atts['style'] || 'four' === $atts['style'] ) ) {
			$content_style['border_radius'] = $atts['border_radius'];
		}

		$content_style = vcex_inline_style( $content_style );

		$output .= '<div class="' . esc_attr( $content_classes ) . '"' . $content_style . '>';

			/*-------------------------------------------------------------------------------*/
			/* [ Heading ]
			/*-------------------------------------------------------------------------------*/
			if ( $atts['heading'] ) {

				// Heading class.
				$heading_class = array(
					'vcex-teaser-heading',
					'wpex-heading',
					'wpex-text-lg',
				);

				// Heading responsive styles.
				$unique_classname = vcex_element_unique_classname();

				$el_responsive_styles = array(
					'font_size' => $atts['heading_size'],
				);

				$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

				if ( $responsive_css ) {
					$heading_class[] = $unique_classname;
					$output .= '<style>' . $responsive_css . '</style>';
				}

				// Heading style.
				$heading_inline_style = vcex_inline_style( array(
					'font_family'    => $atts['heading_font_family'],
					'color'          => $atts['heading_color'],
					'font_size'      => $atts['heading_size'],
					'margin'         => $atts['heading_margin'],
					'font_weight'    => $atts['heading_weight'],
					'letter_spacing' => $atts['heading_letter_spacing'],
					'text_transform' => $atts['heading_transform'],
				), false );

				// Heading attributes.
				$heading_attrs = array(
					'class' => $heading_class,
					'style' => $heading_inline_style,
				);

				// Heading output..
				$safe_heading_tag = tag_escape( $atts['heading_type'] ?: 'h2' );
				$output .= '<' . $safe_heading_tag . vcex_parse_html_attributes( $heading_attrs ) . '>';

					// Open URL.
					if ( ! empty( $url_output ) ) {
						$output .= $url_output;
					}

						$output .= wp_kses_post( do_shortcode( $atts['heading'] ) );

					// Close URL.
					if ( ! empty( $url_output ) ) {
						$output .= '</a>';
					}

				$output .= '</' . $safe_heading_tag . '>';

			} // End heading.

			/*-------------------------------------------------------------------------------*/
			/* [ Content ]
			/*-------------------------------------------------------------------------------*/
			if ( $content ) {

				$content_text_class = array(
					'vcex-teaser-text',
					'wpex-mt-' . absint( $atts['content_top_margin'] ?: 10 ),
					'wpex-last-mb-0',
					'wpex-clr',
				);

				// Content responsive styles.
				$unique_classname = vcex_element_unique_classname();

				$el_responsive_styles = array(
					'font_size' => $atts['content_font_size'],
				);

				$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

				if ( $responsive_css ) {
					$content_text_class[] = $unique_classname;
					$output .= '<style>' . $responsive_css . '</style>';
				}

				// Content inline style.
				$content_text_style = vcex_inline_style( array(
					'font_size'   => $atts['content_font_size'],
					'color'       => $atts['content_color'],
					'font_weight' => $atts['content_font_weight'],
				), false );

				// Content attributes.
				$content_text_attrs = array(
					'class' => $content_text_class,
					'style' => $content_text_style,
				);

				// Output content.
				$output .= '<div' . vcex_parse_html_attributes( $content_text_attrs ) . '>';

					$output .= vcex_the_content( $content );

				$output .= '</div>';

			} // End content output.

		$output .= '</div>';

	} // End heading & content display.

$output .= '</div>';

if ( $atts['css_animation'] && 'none' !== $atts['css_animation'] ) {
	$output .= '</div>';
}

// @codingStandardsIgnoreLine
echo $output;