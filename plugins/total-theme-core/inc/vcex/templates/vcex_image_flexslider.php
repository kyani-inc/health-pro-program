<?php
/**
 * vcex_image_flexslider shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_image_flexslider', $atts ) ) {
	return;
}

// Define output var.
$output = '';

// Get and extract shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_image_flexslider', $atts, $this );
extract( $atts );

// Get images from custom field.
if ( ! empty( $custom_field_gallery ) ) {

	$image_ids = get_post_meta( vcex_get_the_ID(), trim( $custom_field_gallery ), true );

// Get images from post gallery.
} elseif ( 'true' == $post_gallery ) {
	$image_ids = vcex_get_post_gallery_ids();
}

// Get images based on Real Media folder.
elseif ( defined( 'RML_VERSION' ) && $rml_folder ) {
	$rml_query = new WP_Query( array(
		'post_status'    => 'inherit',
		'posts_per_page' => $posts_per_page,
		'post_type'      => 'attachment',
		'orderby'        => 'rml', // Order by custom order of RML
		'rml_folder'     => $rml_folder,
		'fields'         => 'ids',
	) );
	if ( $rml_query->have_posts() ) {
		$image_ids = $rml_query->posts;
	}
}

// If there aren't any images lets display a notice.
if ( empty( $image_ids ) ) {
	return;
}

// Otherwise if there are images lets turn it into an array.
else {

	// Get image ID's.
	if ( ! is_array( $image_ids ) ) {
		$attachments = explode( ',', $image_ids );
	} else {
		$attachments = $image_ids;
	}

	// Translate images.
	foreach ( $attachments as $key => $attachment ) {
		if ( function_exists( 'wpex_parse_obj_id' ) ) {
			$attachments[$key] = wpex_parse_obj_id( $attachment, 'attachment' );
		}
	}

}

// Sanitize attachments to make sure they exist.
$attachments = array_filter( $attachments, 'vcex_validate_attachment' );

if ( ! $attachments ) {
	return;
}

// Turn links into array.
if ( 'custom_link' == $thumbnail_link ) {

	// Remove duplicate images.
	$attachments = array_unique( $attachments );

	// Turn links into array.
	if ( $custom_links ) {
		$custom_links = explode( ',', $custom_links );
	} else {
		$custom_links = array();
	}

	// Count items.
	$attachments_count  = count( $attachments );
	$custom_links_count = count( $custom_links );

	// Add empty values to custom_links array for images without links.
	if ( $attachments_count > $custom_links_count ) {
		$count = 0;
		foreach( $attachments as $val ) {
			$count++;
			if ( ! isset( $custom_links[$count] ) ) {
				$custom_links[$count] = '#';
			}
		}
	}

	// New custom links count.
	$custom_links_count = count( $custom_links );

	// Remove extra custom links.
	if ( $custom_links_count > $attachments_count ) {
		$count = 0;
		foreach( $custom_links as $key => $val ) {
			$count ++;
			if ( $count > $attachments_count ) {
				unset( $custom_links[$key] );
			}
		}
	}

	// Set links as the keys for the images.
	$attachments = array_combine( $attachments, $custom_links );

} else {

	$attachments = array_combine( $attachments, $attachments );

}

// Output images.
if ( $attachments ) :

	// Load slider scripts.
	if ( vcex_vc_is_inline() ) {
		vcex_enqueue_slider_scripts();
		vcex_enqueue_slider_scripts( true ); // needs both in builder incase user switches settings.
	} else {
		$noCarouselThumbnails = ( 'true' == $control_thumbs_carousel ) ? false : true;
		vcex_enqueue_slider_scripts( $noCarouselThumbnails );
	}

	// Load lightbox scripts.
	if ( 'lightbox' === $thumbnail_link ) {
		vcex_enqueue_lightbox_scripts();
	}

	// Sanitize data and declare main vars.
	$caption_data = array();
	$wrap_data    = array();
	$slideshow    = vcex_vc_is_inline() ? 'false' : $slideshow;
	$lazy_load    = 'true' == $lazy_load ? true : false;

	// Slider attributes.
	if ( in_array( $animation, array( 'fade', 'fade_slides' ) ) ) {
		$wrap_data[] = 'data-fade="true"';
	}

	if ( 'true' == $randomize ) {
		$wrap_data[] = 'data-shuffle="true"';
	}

	if ( 'true' == $loop ) {
		$wrap_data[] = ' data-loop="true"';
	}

	if ( 'true' == $counter ) {
		$wrap_data[] = ' data-counter="true"';
	}

	if ( 'false' == $slideshow ) {
		$wrap_data[] = 'data-auto-play="false"';
	} else {
		if ( $autoplay_on_hover && 'pause' != $autoplay_on_hover ) {
			$wrap_data[] = 'data-autoplay-on-hover="' . esc_attr( $autoplay_on_hover ) . '"';
		}
	}

	if ( $slideshow && $slideshow_speed ) {
		$wrap_data[] = 'data-auto-play-delay="' . esc_attr( $slideshow_speed ) . '"';
	}

	if ( 'false' == $direction_nav ) {
		$wrap_data[] = 'data-arrows="false"';
	}

	if ( 'false' == $control_nav ) {
		$wrap_data[] = 'data-buttons="false"';
	}

	if ( 'false' == $direction_nav_hover ) {
		$wrap_data[] = 'data-fade-arrows="false"';
	}

	if ( 'true' == $control_thumbs ) {
		$wrap_data[] = 'data-thumbnails="true"';
	}

	if ( 'true' == $control_thumbs && 'true' == $control_thumbs_pointer ) {
		$wrap_data[] = 'data-thumbnail-pointer="true"';
	}

	if ( $animation_speed ) {
		$wrap_data[] = 'data-animation-speed="' . esc_attr( intval( $animation_speed ) ) . '"';
	}

	if ( 'false' == $auto_height ) {
		$wrap_data[] = 'data-auto-height="false"';
	} elseif ( $height_animation ) {
		$height_animation = intval( $height_animation );
		$height_animation = 0 == $height_animation ? '0.0' : $height_animation;
		$wrap_data[] = 'data-height-animation-duration="' . esc_attr( $height_animation ) . '"';
	}

	if ( $control_thumbs_height ) {
		$wrap_data[] = 'data-thumbnail-height="' . esc_attr( intval( $control_thumbs_height ) ) . '"';
	}

	if ( $control_thumbs_width ) {
		$wrap_data[] = 'data-thumbnail-width="' . esc_attr( intval( $control_thumbs_width ) ) . '"';
	}

	if ( 'false' == $autoplay_videos ) {
		$wrap_data[] = 'data-reach-video-action="none"';
	}

	if ( $custom_links && apply_filters( 'vcex_sliders_disable_desktop_swipe', true, 'vcex_image_flexslider' ) ) {
		$wrap_data[] = 'data-touch-swipe-desktop="false"';
	}

	// Caption attributes and classes.
	if ( 'true' == $caption ) {

		// Caption attributes.
		if ( $caption_position ) {
			$caption_data[] = 'data-position="' . esc_attr( $caption_position ) . '"';
		}

		if ( $caption_show_transition ) {
			$caption_data[] = 'data-show-transition="' . esc_attr( $caption_show_transition ) . '"';
		}

		if ( $caption_hide_transition ) {
			$caption_data[] = 'data-hide-transition="' . esc_attr( $caption_hide_transition ) . '"';
		}

		if ( $caption_width ) {
			$caption_data[] = 'data-width="' . vcex_validate_px_pct( $caption_width, 'px-pct' ) . '"';
		} else {
			$caption_data[] = 'data-width="100%"';
		}

		if ( $caption_horizontal ) {
			$caption_data[] = 'data-horizontal="' . esc_attr( intval( $caption_horizontal ) ) . '"';
		}

		if ( $caption_vertical ) {
			$caption_data[] = 'data-vertical="' . esc_attr( intval( $caption_vertical ) ) . '"';
		}

		if ( $caption_delay ) {
			$caption_data[] = 'data-show-delay="' . esc_attr( intval( $caption_delay ) ) . '"';
		}

		if ( empty( $caption_show_transition ) && empty( $caption_hide_transition ) ) {
			$caption_data[] = 'data-sp-static="false"';
		}

		// Caption classes.
		$caption_classes = array(
			'wpex-slider-caption',
			'sp-layer',
			'sp-padding',
			'wpex-clr'
		);

		if ( $atts['caption_visibility'] ) {
			$caption_classes[] = $caption_visibility;
		}

		if ( $atts['caption_style'] && 'none' !== $atts['caption_style'] ) {
			$caption_classes[] = 'sp-' . sanitize_html_class( $atts['caption_style'] );
		}

		if ( 'none' === $atts['caption_style'] ) {
			$caption_classes[] = 'wpex-text-lg';
			$caption_classes[] = 'wpex-md-text-3xl';
			$caption_classes[] = 'wpex-text-white';
			$caption_classes[] = 'wpex-font-semibold';
		}

		if ( 'true' == $atts['caption_rounded'] ) {
			$caption_classes[] = 'sp-rounded';
		}

		if ( 'false' == $atts['caption_show_transition'] && 'false' == $atts['caption_hide_transition'] ) {
			$caption_classes[] = 'sp-static';
		}

		// Caption style.
		$caption_inline_style = vcex_inline_style( array(
			'padding'     => $atts['caption_padding'],
			'color'       => $atts['caption_color'],
			'font_weight' => $atts['caption_font_weight'],
			'font_size'   => $atts['caption_font_size'],
		) );

		// Responsive font size.
		if ( $atts['caption_font_size'] ) {
			$unique_caption_class = vcex_element_unique_classname();
			$caption_classes[] = $unique_caption_class;
			$caption_css = vcex_responsive_attribute_css( $atts['caption_font_size'], $unique_caption_class, 'font_size' );
			if ( $caption_css ) {
				$output .= '<style>';
					$output .= $caption_css;
				$output .= '</style>';
			}
		}

	}

	// Main Classes.
	$wrap_classes = array(
		'vcex-module',
		'wpex-slider',
		'slider-pro',
		'vcex-image-slider',
		'wpex-clr'
	);

	if ( $bottom_margin ) {
		$wrap_classes[] = vcex_sanitize_margin_class( $bottom_margin, 'wpex-mb-' );
	}

	if ( $classes ) {
		$wrap_classes[] = vcex_get_extra_class( $classes );
	}

	if ( 'false' == $img_strech ) {
		$wrap_classes[] = 'no-stretch';
	}

	if ( $visibility ) {
		$wrap_classes[] = vcex_parse_visibility_class( $visibility );
	}

	if ( 'lightbox' === $thumbnail_link ) {
		$wrap_classes[] = 'wpex-lightbox-group';
		vcex_enque_style( 'ilightbox' );
		if ( $lightbox_path ) {
			$wrap_data[] = 'data-path="' . esc_attr( $lightbox_path ) . '"';
		}
		if ( 'none' === $lightbox_title ) {
			$wrap_data[] = 'data-show_title="false"';
		}
	}

	// Parse wrap class.
	$wrap_classes = vcex_parse_shortcode_classes( $wrap_classes, 'vcex_image_flexslider', $atts );

	/**
	 * Filters the image slider data attributes.
	 *
	 * @param array $wrap_data
	 * @param array $shortcode_attributes
	 */
	$wrap_data = (array) apply_filters( 'vcex_image_flexslider_data_attributes', $wrap_data, $atts );

	if ( $wrap_data && is_array( $wrap_data ) ) {
		$wrap_data = ' ' . trim( implode( ' ', $wrap_data ) );
	}

	// Open animation wrapper.
	if ( $css_animation && 'none' !== $css_animation ) {

		$css_animation_style = vcex_inline_style( array(
			'animation_delay'    => $atts['animation_delay'],
			'animation_duration' => $atts['animation_duration'],
		) );

		$output .= '<div class="' . vcex_get_css_animation( $css_animation ) . '"' . $css_animation_style . '>';
	}

	// Open css wrapper.
	if ( $css ) {
		$output .= '<div class="vcex-image-slider-css-wrap ' . vcex_vc_shortcode_custom_css_class( $css ) . '">';
	}

	// Create overlay HTML.
	if ( 'true' === $atts['overlay'] ) {
		$overlay_html = '<div class="wpex-slider__overlay wpex-absolute wpex-inset-0 wpex-bg-black wpex-opacity-30"';
			$overlay_html .= vcex_inline_style( array(
				'background' => $atts['overlay_color'],
				'opacity'    => $atts['overlay_opacity'],
			) );
		$overlay_html .='></div>';
	}

	// Preloader image.
	if ( 'true' != $randomize ) {

		$preloader_classes = 'wpex-slider-preloaderimg wpex-relative';

		if ( 'false' == $img_strech ) {
			$preloader_classes .= ' no-stretch';
		}

		if ( $visibility ) {
			$preloader_classes .= ' ' . sanitize_html_class( $visibility );
		}

		$output .= '<div class="' . esc_attr( $preloader_classes ) . '">';

			if ( ! empty( $overlay_html ) ) {
				$output .= $overlay_html;
			}

			$first_attachment = reset( $attachments );
			$output .= vcex_get_post_thumbnail( array(
				'attachment'    => current( array_keys( $attachments ) ),
				'size'          => $img_size,
				'crop'          => $img_crop,
				'width'         => $img_width,
				'height'        => $img_height,
				'attributes'    => array( 'data-no-lazy' => 1 ),
				'apply_filters' => 'vcex_image_flexslider_thumbnail_args',
				'filter_arg1'   => $atts,
			) );

		$output .= '</div>';

	}

	// Start slider output.
	$output .= '<div class="' . $wrap_classes . '"' . vcex_get_unique_id( $unique_id ) . $wrap_data . '>';

		$output .= '<div class="wpex-slider-slides sp-slides">';

			// Loop through attachments.
			foreach ( $attachments as $attachment => $custom_link ) :

				// Define main vars
				$custom_link      = ( '#' != $custom_link ) ? $custom_link : '';
				$attachment_link  = get_post_meta( $attachment, '_wp_attachment_url', true );
				$attachment_data  = vcex_get_attachment_data( $attachment );
				$caption_enabled  = ( 'true' == $caption ) ? true : false;
				$caption_type     = $caption_type ?: 'caption';
				$caption_output   = $caption_enabled ? $attachment_data[$caption_type] : '';
				$attachment_video = $attachment_data['video'];

				// Generate img HTML.
				$attachment_img = vcex_get_post_thumbnail( array(
					'attachment'    => $attachment,
					'size'          => $img_size,
					'crop'          => $img_crop,
					'width'         => $img_width,
					'height'        => $img_height,
					'alt'           => $attachment_data['alt'],
					'lazy_load'     => $lazy_load,
					'retina_data'   => 'retina',
					'attributes'    => array( 'data-no-lazy' => 1 ),
					'apply_filters' => 'vcex_image_flexslider_thumbnail_args',
					'filter_arg1'   => $atts,
				) );

				// Image or video needed.
				if ( $attachment_img || $attachment_video ) {

					$output .= '<div class="wpex-slider-slide sp-slide">';

						if ( ! empty( $overlay_html ) ) {
							$output .= $overlay_html;
						}

						$output .= '<div class="wpex-slider-media">';

							// Check if the current attachment has a video.
							if ( $attachment_video && 'true' != $lighbox_videos ) {

								if ( 'true' != $video_captions ) {
									$caption_enabled = false;
								}

								// Output video.
								$output .= '<div class="wpex-slider-video responsive-video-wrap">';

									$output .= vcex_video_oembed( $attachment_video, 'sp-video', array(
										'youtube' => array(
											'enablejsapi' => '1',
										)
									) );

								$output .= '</div>';

							} elseif( $attachment_img ) {

								// Lightbox links.
								if ( 'lightbox' === $thumbnail_link ) {

									// Video lightbox.
									if ( $attachment_video ) {

										$lightbox_url = vcex_get_video_embed_url( $attachment_video );
										$lightbox_data_attributes .= ' data-thumb="' . vcex_get_lightbox_image( $attachment ) . '"';

									}

									// Image lightbox.
									else {

										$lightbox_url = vcex_get_lightbox_image( $attachment );

									}

									// Define data attributes var.
									$lightbox_data_attributes = '';

									// Lightbox titles.
									if ( 'title' === $lightbox_title && $attachment_data['title'] ) {
										$lightbox_data_attributes .= ' data-title="' . $attachment_data['title'] . '"';
									} elseif ( 'alt' === $lightbox_title ) {
										$lightbox_alt = get_post_meta( $attachment, '_wp_attachment_image_alt', true );
										if ( $lightbox_alt ) {
											$lightbox_data_attributes .= ' data-title="' . esc_attr( $lightbox_alt ) . '"';
										} else {
											$lightbox_data_attributes .= ' data-title="false"';
										}
									}

									// Lightbox Captions.
									if ( $attachment_data['caption'] && 'false' != $lightbox_caption ) {
										$lightbox_data_attributes .= ' data-caption="' . str_replace( '"',"'", $attachment_data['caption'] ) . '"';
									}

									$output .= '<a href="' . esc_url( $lightbox_url ) . '" class="vcex-flexslider-entry-img wpex-slider-media-link wpex-lightbox-group-item"' . $lightbox_data_attributes . '>';
										$output .= $attachment_img;
									$output .= '</a>';

								// Custom Links.
								} elseif ( 'custom_link' == $thumbnail_link ) {

									// Check for a meta link value.
									if ( $link_meta_key ) {
										$meta_custom_link = get_post_meta( $attachment, wp_strip_all_tags( $link_meta_key ), true );
										if ( ! empty( $meta_custom_link ) ) {
											$custom_link = $meta_custom_link;
										}
									}

									// Custom link.
									if ( $custom_link ) {

										$output .= '<a href="' . esc_url( $custom_link ) . '"' . vcex_html( 'target_attr', $custom_links_target ) . ' class="wpex-slider-media-link">';

											$output .= $attachment_img;

										$output .= '</a>';

									// No link.
									} else {

										$output .= $attachment_img;

									}

								// Just images, no links.
								} else {

									// Display the main slider image.
									$output .= $attachment_img;

								}

							}

							// Display caption.
							if ( $caption_enabled && $caption_output ) {

								$output .= '<div class="' . esc_attr( implode( ' ', $caption_classes ) ) . '"' . implode( ' ', $caption_data ) . $caption_inline_style . '>';

									if ( in_array( $caption_type, array( 'description', 'caption' ) ) ) :

										$output .= wpautop( $caption_output );

									else :

										$output .= $caption_output;

									endif;

								$output .= '</div>';

							}

						$output .= '</div>';

					$output .= '</div>';

				}

			endforeach;

		$output .= '</div>';

		if ( 'true' == $control_thumbs ) {

			$thumbnails = array_keys( $attachments ); // strip out URL's.

			$thumbnails = apply_filters( 'vcex_image_flexslider_thumbnails', $thumbnails );

			if ( ! empty( $thumbnails ) && is_array( $thumbnails ) ) {

				$container_classes = 'wpex-slider-thumbnails';

				if ( 'true' == $control_thumbs_carousel ) {
					$container_classes .= ' sp-thumbnails';
				} else {
					$container_classes .= ' sp-nc-thumbnails';
				}

				$output .= '<div class="' . $container_classes . '">';

					$args = array(
						'size'        => $img_size,
						'crop'        => $img_crop,
						'width'       => $img_width,
						'height'      => $img_height,
						'lazy_load'   => $lazy_load,
						'retina_data' => 'retina',
						'attributes'  => array( 'data-no-lazy' => 1 ),
					);

					$entry_classes = '';
					if ( 'true' == $control_thumbs_carousel ) {
						$args['class'] = 'wpex-slider-thumbnail sp-thumbnail';
					} else {
						$args['class'] = 'wpex-slider-thumbnail sp-nc-thumbnail';
						if ( $control_thumbs_height || $control_thumbs_width ) {
							$args['size']   = null;
							$args['width']  = $control_thumbs_width ?: null;
							$args['height'] = $control_thumbs_height ?: null;
						}
					}

					foreach ( $thumbnails as $thumbnail ) {

						$args['attachment'] = $thumbnail;

						$output .= vcex_get_post_thumbnail( $args );

					}

				$output .= '</div>'; // close thumbnails container.

			}

		}

	$output .= '</div>';

	// Close css wrapper.
	if ( $css ) {
		$output .= '</div>';
	}

	// Close animation wrapper.
	if ( $css_animation && 'none' !== $css_animation ) {
		$output .= '</div>';
	}

	// @codingStandardsIgnoreLine
	echo $output;

endif;