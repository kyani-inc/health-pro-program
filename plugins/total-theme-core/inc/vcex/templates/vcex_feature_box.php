<?php
/**
 * vcex_feature_box shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_feature_box', $atts ) ) {
	return;
}

// Output.
$output = '';

// Get and extract shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_feature_box', $atts, $this );

// Set image.
$image = ! empty( $atts['image'] ) ? $atts['image'] : 'placeholder';

// Check if equal heights is enabled.
if ( isset( $atts['equal_heights'] ) && 'true' == $atts['equal_heights'] && empty( $atts['video'] ) ) {
	$equal_heights = true;
} else {
	$equal_heights = false;
}

// Add style
$wrap_style = vcex_inline_style( array(
	'padding'            => $atts['padding'],
	'background'         => $atts['background'],
	'border'             => $atts['border'],
	'text_align'         => $atts['text_align'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
) );

// Classes.
$wrap_classes = array(
	'vcex-module',
	'vcex-feature-box',
	'wpex-clr'
);

if ( ! empty( $atts['bottom_margin'] ) ) {
	$wrap_classes[] = vcex_sanitize_margin_class( $atts['bottom_margin'], 'wpex-mb-' );
}

if ( ! empty( $atts['shadow'] ) ) {
	$wrap_classes[] = vcex_parse_shadow_class( $atts['shadow'] );
}

if ( ! empty( $atts['visibility'] ) ) {
	$wrap_classes[] = vcex_parse_visibility_class( $atts['visibility'] );
}

if ( ! empty( $atts['css_animation'] ) && 'none' !== $atts['css_animation'] ) {
	$wrap_classes[] = vcex_get_css_animation( $atts['css_animation'] );
}

if ( ! empty( $atts['classes'] ) ) {
	$wrap_classes[] = vcex_get_extra_class( $atts['classes'] );
}

if ( ! empty( $atts['style'] ) ) {
	$wrap_classes[] = sanitize_html_class( $atts['style'] );
}

if ( $equal_heights ) {
	$wrap_classes[] = 'vcex-feature-box-match-height';
}

if ( ! empty( $atts['tablet_widths'] ) ) {
	$wrap_classes[] = 'vcex-tablet-collapse';
} elseif ( ! empty( $atts['phone_widths'] ) ) {
	$wrap_classes[] = 'vcex-phone-collapse';
}

if ( 'true' == $atts['content_vertical_align'] && ! $equal_heights ) {
	$wrap_classes[] = 'v-align-middle';
	$wrap_classes[] = 'wpex-flex';
	$wrap_classes[] = 'wpex-items-center';
	if ( 'left-content-right-image' === $atts['style'] ) {
		$wrap_classes[] = 'wpex-flex-row-reverse';
	}
}

$wrap_classes = vcex_parse_shortcode_classes( $wrap_classes, 'vcex_feature_box', $atts );

$output .= '<div class="' . esc_attr( $wrap_classes ) . '"' . vcex_get_unique_id( $atts['unique_id'] ) . $wrap_style . '>';

	// Image/Video check.
	if ( $image || ! empty( $atts['video'] ) ) {

		// Add classes.
		$media_classes = array(
			'vcex-feature-box-media',
			'wpex-w-50',
		);

		if ( 'left-content-right-image' === $atts['style'] ) {
			$media_classes[] = 'wpex-float-right';
		} elseif ( 'left-image-right-content' === $atts['style'] ) {
			$media_classes[] = 'wpex-float-left';
		}

		if ( $equal_heights ) {
			$media_classes[] = 'vcex-match-height';
			$media_classes[] = 'wpex-relative';
			$media_classes[] = 'wpex-overflow-hidden';
		}

		// Media style.
		$media_style = vcex_inline_style( array(
			'width' => $atts['media_width'],
		) );

		$output .= '<div class="' . esc_attr( implode( ' ', $media_classes ) ) . '"' . $media_style . '>';

			// Display Video.
			if ( ! empty( $atts['video'] ) ) {

				$video = $atts['video'];

				// @todo move to a helper function.
				if ( apply_filters( 'wpex_has_oembed_cache', true ) ) { // filter added for testing purposes.
					global $wp_embed;
					if ( $wp_embed && is_object( $wp_embed ) ) {
						$video_html = $wp_embed->shortcode( array(), $video );
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

			}

			// Display Image.
			elseif ( $image ) {

				// Define thumbnail args.
				$thumbnail_args = array(
					'attachment' => $image,
					'size'       => $atts['img_size'],
					'width'      => $atts['img_width'],
					'height'     => $atts['img_height'],
					'crop'       => $atts['img_crop'],
					'class'      => 'wpex-block wpex-m-auto',
				);

				// Image inline CSS.
				$image_style = '';
				if ( isset( $atts['img_border_radius'] ) ) {
					$image_style = vcex_inline_style( array(
						'border_radius' => $atts['img_border_radius'],
					) );
					$thumbnail_args['style'] = 'border-radius:' . esc_attr( $atts['img_border_radius'] ) . ';';
				}

				// Image classes.
				$image_classes = array(
					'vcex-feature-box-image'
				);

				if ( ! empty( $atts['img_filter'] ) ) {
					$image_classes[] = vcex_image_filter_class( $atts['img_filter'] );
				}

				if ( ! empty( $atts['img_hover_style'] ) && ! $equal_heights ) {
					$image_classes[] = vcex_image_hover_classes( $atts['img_hover_style'] );
				}

				if ( $equal_heights ) {
					$image_classes[] = 'wpex-absolute';
					$image_classes[] = 'wpex-inset-0';
					$image_classes[] = 'wpex-w-100';
					$image_classes[] = 'wpex-h-100';
					$thumbnail_args['class'] .= ' wpex-absolute wpex-max-w-none';
				}

				// Image URL.
				if ( ! empty( $atts['image_url'] ) || 'image' === $atts['image_lightbox'] ) {

					// Standard URL.
					$link     = vcex_build_link( $atts['image_url'] );
					$a_href   = $link['url'] ?? '';
					$a_title  = $link['title'] ?? '';
					$a_target = $link['target'] ?? '';
					$a_target = ( false !== strpos( $a_target, 'blank' ) ) ? ' target="_blank"' : '';

					// Image lightbox.
					$data_attributes = '';

					if ( ! empty( $atts['image_lightbox'] ) ) {

						$image_lightbox = $atts['image_lightbox'];

						vcex_enqueue_lightbox_scripts();

						switch ( $image_lightbox ) {
							case 'image':
							case 'self':
								$a_href = vcex_get_lightbox_image( $image );
								break;
							case 'url':
							case 'iframe':
								$data_attributes .= ' data-type="iframe"';
								break;
							case 'video_embed':
								$a_href = vcex_get_video_embed_url( $a_href );
								break;
							case 'inline':
								$data_attributes .= ' data-type="inline"';
								break;
						}

						if ( $a_href ) {
							$image_classes[] = 'wpex-lightbox';
						}

						// Add lightbox dimensions.
						if ( ! empty( $atts['lightbox_dimensions'] )
							&& in_array( $image_lightbox, array( 'video_embed', 'url', 'html5', 'iframe', 'inline' ) )
						) {
							$lightbox_dims = vcex_parse_lightbox_dims( $atts['lightbox_dimensions'], 'array' );
							if ( $lightbox_dims ) {
								$data_attributes .= ' data-width="' . $lightbox_dims['width'] . '"';
								$data_attributes .= ' data-height="' . $lightbox_dims['height'] . '"';
							}
						}

					}

				}

				// Open link if defined.
				if ( ! empty( $a_href ) ) {

					$link_classes = array(
						'vcex-feature-box-image-link',
						'wpex-block',
						'wpex-m-auto',
						'wpex-overflow-hidden' // used for border radius or other mods to the image
					);

					$link_classes = array_merge( $link_classes, $image_classes );

					$output .= '<a href="' . esc_url( $a_href ) . '" title="' . esc_attr( $a_title ) . '" class=" ' . esc_attr( implode( ' ', $link_classes ) ) . '"' . $image_style . '' . $data_attributes . '' . $a_target . '>';


				// Link isn't defined open div.
				} else {

					$output .= '<div class="' . esc_attr( implode( ' ', $image_classes ) ) . '"' . $image_style . '>';

				}

					// Display image.
					$output .= vcex_get_post_thumbnail( $thumbnail_args );

				// Close link.
				if ( ! empty( $a_href ) ) {

					$output .= '</a>';

				// Link not defined, close div.
				} else {

					$output .= '</div>';

				}

				} // End video check.

			$output .= '</div>'; // close media.

		} // $video or $image check.

		// Content area.
		if ( ! empty( $content ) || ! empty( $atts['heading'] ) ) {

			$content_classes = array(
				'vcex-feature-box-content',
				'wpex-w-50',
			);

			if ( 'left-content-right-image' === $atts['style'] ) {
				$content_classes[] = 'wpex-float-left';
				$content_classes[] = 'wpex-pr-30';
			} elseif ( 'left-image-right-content' === $atts['style'] ) {
				$content_classes[] = 'wpex-float-right';
				$content_classes[] = 'wpex-pl-30';
			}

			if ( $equal_heights ) {
				$content_classes[] = 'vcex-match-height';
			}

			$content_classes[] = 'wpex-clr';

			$content_style = vcex_inline_style( array(
				'width'      => $atts['content_width'],
				'background' => $atts['content_background'],
			) );

			$output .= '<div class="' . esc_attr( implode( ' ', $content_classes ) ) . '"' . $content_style . '>';

			if ( ! empty( $atts['content_padding'] ) ) {

				$atts['style'] = vcex_inline_style( array(
					'padding' => $atts['content_padding'],
				) );

				$output .= '<div class="vcex-feature-box-padding-container wpex-clr"' . $atts['style'] . '>';

			}

			// Heading.
			if ( ! empty( $atts['heading'] ) ) {

				if ( empty( $atts['heading_type'] ) ) {
					$atts['heading_type'] = apply_filters( 'vcex_feature_box_heading_default_tag', 'h2' );
				}

				$safe_heading_tag = tag_escape( $atts['heading_type'] );

				// Classes.
				$heading_attrs = array(
					'class' => '',
				);

				$heading_class = array(
					'vcex-feature-box-heading',
					'wpex-heading',
					'wpex-text-lg',
					'wpex-mb-20',
				);

				// Heading style.
				$heading_attrs['style'] = vcex_inline_style( array(
					'font_family'    => $atts['heading_font_family'],
					'color'          => $atts['heading_color'],
					'font_size'      => $atts['heading_size'],
					'font_weight'    => $atts['heading_weight'],
					'margin'         => $atts['heading_margin'],
					'letter_spacing' => $atts['heading_letter_spacing'],
					'text_transform' => $atts['heading_transform'],
				), false );

				// Responsive heading styles.
				$unique_classname = vcex_element_unique_classname();

				$el_responsive_styles = array(
					'font_size' => $atts['heading_size'],
				);

				$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

				if ( $responsive_css ) {
					$heading_class[] = $unique_classname;
					$output .= '<style>' . $responsive_css . '</style>';
				}

				// Heading URL.
				$a_href = '';
				if ( ! empty( $atts['heading_url'] ) && '||' !== $atts['heading_url'] ) {
					$link     = vcex_build_link( $atts['heading_url'] );
					$a_href   = $link['url'] ?? '';
					$a_title  = $link['title'] ?? '';
					$a_target = $link['target'] ?? '';
					$a_target = ( false !== strpos( $a_target, 'blank' ) ) ? ' target="_blank"' : '';
				}

				if ( isset( $a_href ) && $a_href ) {

					$output .= '<a href="' . esc_url( do_shortcode( $a_href ) ) . '" title="' . esc_attr( do_shortcode( $a_title ) ) . '"class="vcex-feature-box-heading-link wpex-no-underline"' . $a_target . '>';

				}

				$heading_attrs['class'] = $heading_class;

				/**
				 * Filters the Feature Box heading attributes.
				 *
				 * @param array $heading_attrs
				 * @param array $shortcode_atts
				 */
				$heading_attrs = apply_filters( 'vcex_feature_box_heading_attrs', $heading_attrs, $atts );

				// Display the heading.
				$output .= '<' . $safe_heading_tag . vcex_parse_html_attributes( $heading_attrs ) . '>';

					$output .= wp_kses_post( do_shortcode( $atts['heading'] ) );

				$output .= '</' . $safe_heading_tag .'>';

				if ( isset( $a_href ) && $a_href ) {
					$output .= '</a>';
				}

			} //  End heading.

			// Text.
			if ( ! empty( $content ) ) {

				// Text classes.
				$content_text_class = array(
					'vcex-feature-box-text',
					'wpex-last-mb-0',
					'wpex-clr',
				);

				// Responsive text styles.
				$unique_classname = vcex_element_unique_classname();

				$el_responsive_styles = array(
					'font_size' => $atts['content_font_size'],
				);

				$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

				if ( $responsive_css ) {
					$content_text_class[] = $unique_classname;
					$output .= '<style>' . $responsive_css . '</style>';
				}

				// Text styles.
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

					$output .= do_shortcode( wpautop( wp_kses_post( $content ) ) );

				$output .= '</div>';

			} // End content.

			// Close padding container.
			if ( ! empty( $atts['content_padding'] ) ) {

				$output .= '</div>';

			}

		$output .= '</div>';

	} // End content + Heading wrap.

$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;