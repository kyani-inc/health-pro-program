<?php
/**
 * vcex_image_carousel shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_image_carousel', $atts ) ) {
	return;
}

// Get and extract shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_image_carousel', $atts, $this );
extract( $atts );

// Output var.
$output = '';

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
		'orderby'        => 'rml', // Order by custom order of RML.
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

	// Get image ID's
	if ( ! is_array( $image_ids ) ) {
		$attachment_ids = explode( ',', $image_ids );
	} else {
		$attachment_ids = $image_ids;
	}

}

// Remove duplicate images.
$attachment_ids = array_unique( $attachment_ids );

// Sanitize attachments to make sure they exist.
$attachment_ids = array_filter( $attachment_ids, 'vcex_validate_attachment' );

if ( ! $attachment_ids ) {
	return;
}

// Turn links into array.
if ( $custom_links ) {
	$custom_links = explode( ',', $custom_links );
} else {
	$custom_links = array();
}

// Count items
$attachment_ids_count = count( $attachment_ids );
$custom_links_count   = count( $custom_links );

// Add empty values to custom_links array for images without links.
if ( $attachment_ids_count > $custom_links_count ) {
	$count = 0;
	foreach( $attachment_ids as $val ) {
		$count++;
		if ( ! isset( $custom_links[$count] ) ) {
			$custom_links[$count] = '#';
		}
	}
}

// New custom links count.
$custom_links_count = count( $custom_links );

// Remove extra custom links.
if ( $custom_links_count > $attachment_ids_count ) {
	$count = 0;
	foreach( $custom_links as $key => $val ) {
		$count ++;
		if ( $count > $attachment_ids_count ) {
			unset( $custom_links[$key] );
		}
	}
}

// Set links as the keys for the images.
$images_links_array = array_combine( $attachment_ids, $custom_links );

// Return if no images.
if ( ! $images_links_array ) {
	return;
}

// Lets create a new Query for the image carousel.
$vcex_query = new WP_Query( array(
	'post_type'      => 'attachment',
	//'post_mime_type'    => 'image/jpeg,image/gif,image/jpg,image/png',
	'post_status'    => 'any',
	'posts_per_page' => -1,
	'paged'          => NULL,
	'no_found_rows'  => true,
	'post__in'       => $attachment_ids,
	'orderby'        => ! empty( $atts['orderby'] ) ? $atts['orderby'] : 'post__in',
	'order'          => $atts['order']
) );

// Display carousel if there are images.
if ( $vcex_query->have_posts() ) :

	// Enqueue scripts.
	vcex_enqueue_carousel_scripts();

	// Main Classes.
	$wrap_classes = array(
		'vcex-module',
		'wpex-carousel',
		'wpex-carousel-images',
		'wpex-clr',
		'owl-carousel',
	);

	// Bottom margin
	if ( $bottom_margin ) {
		$wrap_classes[] = vcex_sanitize_margin_class( $bottom_margin, 'wpex-mb-' );
	}

	// Vertical align
	if ( 'true' == $vertical_align ) {
		$wrap_classes[] = 'wpex-carousel-items-center';
	}

	// Carousel style
	if ( $style && 'default' != $style ) {
		$wrap_classes[] = $style;
		$arrows_position = ( 'no-margins' == $style && 'default' == $arrows_position ) ? 'abs' : $arrows_position;
	}

	// Arrow classes
	if ( 'true' == $arrows ) {

		if ( ! $arrows_style ) {
			$arrows_style = 'default';
		}

		$wrap_classes[] = 'arrwstyle-' . sanitize_html_class( $arrows_style );

		if ( $arrows_position && 'default' != $arrows_position ) {
			$wrap_classes[] = 'arrwpos-' . sanitize_html_class( $arrows_position );
		}

	}

	// CSS animation class
	if ( $css_animation_class = vcex_get_css_animation( $css_animation ) ) {
		$wrap_classes[] = $css_animation_class;
	}

	// Custom classes
	if ( $classes ) {
		$wrap_classes[] = vcex_get_extra_class( $classes );
	}

	// Entry classes
	$entry_classes = array( 'wpex-carousel-slide' );

	if ( $content_alignment ) {
		$entry_classes[] = 'text' . sanitize_html_class( $content_alignment );
	}

	if ( $entry_css ) {
		$entry_classes[] .= vcex_vc_shortcode_custom_css_class( $entry_css );
	}

	// Lightbox css/js/classes
	if ( 'lightbox' === $thumbnail_link ) {
		vcex_enqueue_lightbox_scripts();
		if ( 'true' == $lightbox_gallery ) {
			$wrap_classes[] = 'wpex-carousel-lightbox';
		}
	}

	// Items to scroll fallback for old setting
	if ( 'page' === $items_scroll ) {
		$items_scroll = $items;
	}

	// Title design
	if ( 'yes' === $title ) {
		$heading_style = vcex_inline_style( array(
			'margin'         => $atts['content_heading_margin'],
			'text_transform' => $atts['content_heading_transform'],
			'font_weight'    => $atts['content_heading_weight'],
			'font_size'      => $atts['content_heading_size'],
			'color'          => $atts['content_heading_color'],
		) );
	}

	// Content Design
	if ( 'yes' === $title || 'yes' === $caption ) {

		// Non css_editor fields
		$content_style = array(
			'color'            => $atts['content_color'],
			'font_size'        => $atts['content_font_size'],
			'background_color' => $atts['content_background_color'],
			'border_color'     => $atts['content_border_color'],
		);

		// Deprecated fields
		if ( empty( $content_css ) ) {

			if ( ! empty( $content_background ) ) {
				$content_style['background'] = $content_background;
			}

			if ( ! empty( $content_padding ) ) {
				$content_style['padding'] = $content_padding;
			}

			if ( ! empty( $content_margin ) ) {
				$content_style['margin'] = $content_margin;
			}

			if ( ! empty( $content_border ) ) {
				$content_style['border'] = $content_border;
			}

		}

		// Generate inline style
		$content_style = vcex_inline_style( $content_style );

	}

	// Prevent auto play in visual composer
	if ( vcex_vc_is_inline() ) {
		$atts['auto_play'] = false;
	}

	// Apply filters
	$wrap_classes = vcex_parse_shortcode_classes( implode( ' ', $wrap_classes ), 'vcex_image_carousel', $atts );

	// Display header if enabled
	if ( $header ) {

		$output .= vcex_get_module_header( array(
			'style'   => $header_style,
			'content' => $header,
			'classes' => array(
				'vcex-module-heading',
				'vcex_image_carousel-heading',
			),
		) );

	}

	// Open wrapper for auto height
	if ( 'true' == $auto_height ) {
		$output .= '<div class="owl-wrapper-outer">';
	}

	// Wrap Style
	$wrap_style = vcex_inline_style( array(
		'animation_delay'    => $atts['animation_delay'],
		'animation_duration' => $atts['animation_duration'],
	) );

	/*--------------------------------*/
	/* [ Begin Carousel Output ]
	/*--------------------------------*/
	$output .= '<div class="' . esc_attr( $wrap_classes ) . '" data-wpex-carousel="' . vcex_get_carousel_settings( $atts, 'vcex_image_carousel' ) . '"' . vcex_get_unique_id( $unique_id ) . $wrap_style . '>';

		// Start counter used for lightbox.
		$count=0;

		// Loop through images
		while ( $vcex_query->have_posts() ) :

			// Add to counter.
			$count++;

			// Reset entry_classes.
			$loop_entry_classes = $entry_classes;

			// Get post from query.
			$vcex_query->the_post();

			// Store entry data in $atts array so we can apply a filter later.
			$atts['post_id']      = get_the_ID();
			$atts['post_data']    = vcex_get_attachment_data( $atts['post_id'] );
			$atts['post_link']    = $atts['post_data']['url'];
			$atts['post_url']     = '';
			$atts['post_alt']     = esc_attr( $atts['post_data']['alt'] );
			$atts['post_caption'] = $atts['post_data']['caption'];
			$atts['post_video']   = apply_filters( 'vcex_image_carousel_video_support', false ) ? $atts['post_data']['video'] : null;
			$atts['link_target']  = $custom_links_target; // save target for overlay styles
			$og_attachment_id     = $atts['post_id'];

			// Get original attachment ID - fix for WPML.
			if ( $custom_links_count && function_exists( 'icl_object_id' ) ) {
				global $sitepress;
				if ( $sitepress ) {
					$_icl_lang_duplicate_of = get_post_meta( $atts['post_id'], '_icl_lang_duplicate_of', true );
					$wpml_attachment_id = icl_object_id( $atts['post_id'], 'attachment', false, $sitepress->get_default_language() );
					if ( ! array_key_exists( $wpml_attachment_id, $images_links_array ) ) {
						$wpml_attachment_id = icl_object_id( $atts['post_id'], 'attachment', false, apply_filters( 'wpml_current_language', NULL ) );
					}
					if ( array_key_exists( $wpml_attachment_id, $images_links_array ) ) {
						$og_attachment_id = $wpml_attachment_id;
					}
				}
			}

			// Pluck array to see if item has custom link.
			if ( array_key_exists( $og_attachment_id, $images_links_array ) ) {
				$atts['post_url'] = $images_links_array[$og_attachment_id];
			}

			// Check for custom meta links.
			if ( 'custom_link' === $thumbnail_link ) {
				if ( $link_meta_key ) {
					$meta_custom_link = get_post_meta( $atts['post_id'], trim( $link_meta_key ), true );
					if ( ! empty( $meta_custom_link ) ) {
						$atts['post_url'] = $meta_custom_link;
					}
				} else {
					$meta_custom_link = get_post_meta( $atts['post_id'], '_wpex_custom_link', true );
					if ( ! empty( $meta_custom_link ) ) {
						$atts['post_url'] = $meta_custom_link;
					}
				}
			}

			// Remove links if the post_url is a # symbol.
			if ( '#' === $atts['post_url'] ) {
				$atts['post_url'] = '';
			}

			// Get correct title.
			switch ( $title_type ) {
				case 'alt':
					$attachment_title = esc_attr( $atts['post_data']['alt'] );
					break;
				case 'title':
				default:
					$attachment_title = get_the_title();
					break;
			}

			// Image|Video output.
			if ( empty( $atts['post_video'] ) ) {
				$atts['media_type'] = 'thumbnail';
				$image_output = vcex_get_post_thumbnail( array(
					'attachment'    => $atts['post_id'],
					'crop'          => $img_crop,
					'size'          => $img_size,
					'width'         => $img_width,
					'height'        => $img_height,
					'alt'           => $atts['post_alt'],
					'class'         => implode( ' ', vcex_get_entry_thumbnail_class( null, 'vcex_image_carousel', $atts ) ),
					'attributes'    => array( 'data-no-lazy' => 1 ),
					'apply_filters' => 'vcex_image_carousel_thumbnail_args',
					'filter_arg1'   => $atts,
				) );
			} else {
				$atts['media_type'] = 'video';
				$loop_entry_classes[] = 'owl-item-video';
			}

			/*--------------------------------*/
			/* [ Begin Entry Output ]
			/*--------------------------------*/
			$output .= '<div class="' . esc_attr( implode( ' ', $loop_entry_classes ) ) . '">';

				$output .= '<figure class="' . esc_attr( implode( ' ', vcex_get_entry_media_class( array( 'wpex-carousel-entry-media' ), 'vcex_image_carousel', $atts ) ) ) . '">';

					/*--------------------------------*/
					/* [ Entry Media ]
					/*--------------------------------*/

					// Entry Video.
					if ( ! empty( $atts['post_video'] ) ) {

						$output .= '<a href="' . esc_url( set_url_scheme( $atts['post_video'] ) ) . '" class="owl-video"></a>';

					}

					// Image thumbnail.
					else {

						// Add custom links to attributes for use with the overlay styles.
						if ( 'custom_link' === $thumbnail_link && $atts['post_url'] ) {
							$atts['overlay_link'] = esc_url( $atts['post_url'] );
						}

						// Lightbox.
						if ( 'lightbox' === $thumbnail_link ) {

							$atts['lightbox_data']  = array(); // must reset for each item
							$lightbox_image_escaped = vcex_get_lightbox_image( $atts['post_id'] );
							$atts['lightbox_link']  = $lightbox_image_escaped;

							// Main link attributes.
							$link_attrs = array(
								'href'  => '',
								'title' => $atts['post_alt'],
								'class' => 'wpex-carousel-entry-img',
							);

							// Main link lightbox attributes.
							if ( 'lightbox' == $thumbnail_link ) {

								if ( 'true' == $lightbox_gallery ) {
									$link_attrs['class'] .= ' wpex-carousel-lightbox-item';
								} else {
									$link_attrs['class'] .= ' wpex-lightbox';
								}

								$link_attrs['data-title'] = wp_strip_all_tags( $atts['post_alt'] );
								$link_attrs['data-count'] = $count;

								if ( ! in_array( $lightbox_title, array( 'false', 'none' ) ) ) {
									if ( 'title' == $lightbox_title ) {
										$link_attrs['data-title'] = wp_strip_all_tags( get_the_title( $atts['post_id'] ) );
									} elseif ( 'alt' == $lightbox_title ) {
										$link_attrs['data-title'] = wp_strip_all_tags( $atts['post_alt'] );
									}
								} else {
									$link_attrs['data-show_title'] = 'false';
								}

								// Check for video.
								if ( ! empty( $atts['post_data']['video'] ) ) {
									if ( $embed_url = vcex_get_video_embed_url( $atts['post_data']['video'] ) ) {
										$atts['lightbox_link']               = esc_url( $embed_url );
										$atts['lightbox_data']['data-thumb'] = 'data-thumb="' . $lightbox_image_escaped . '"';
									}
								}

								// Caption data.
								if ( 'false' != $lightbox_caption && $attachment_caption = get_post_field( 'post_excerpt', $atts['post_id'] ) ) {
									$link_attrs['data-caption'] = str_replace( '"',"'", $attachment_caption );
								}

								$link_attrs['href'] = $atts['lightbox_link'];

								if ( ! empty( $atts['lightbox_data'] ) ) {
									foreach ( $atts['lightbox_data'] as $ld_k => $ld_v ) {
										$link_attrs[$ld_k] = $ld_v;
									}
								}

							}

							$output .= '<a' . vcex_parse_html_attributes( $link_attrs ) . '>';

								// Display Image.
								$output .= $image_output;

								// Inner link overlay HTML.
								$output .= vcex_get_entry_image_overlay( 'inside_link', 'vcex_image_carousel', $atts );

							$output .= '</a>';


						}

						// Parent page link.
						elseif ( 'parent_page' === $thumbnail_link ) {

							$post_parent = get_post_parent( $atts['post_id'] );

							// Open link.
							if ( $post_parent ) {
								$link_attrs = array(
									'href'   => esc_url( get_permalink( $post_parent ) ),
									'class'  => 'wpex-carousel-entry-img',
									'target' => $custom_links_target,
								);

								$output .= '<a' . vcex_parse_html_attributes( $link_attrs ) . '>';

							}

								// Display image.
								$output .= $image_output;

								// Inner link overlay HTML.
								$output .= vcex_get_entry_image_overlay( 'inside_link', 'vcex_image_carousel', $atts );

							// Close link.
							if ( $post_parent ) {
								$output .= '</a>';
							}

						}

						// Attachment page.
						elseif ( 'attachment_page' === $thumbnail_link || 'full_image' === $thumbnail_link ) {

							// Get URL.
							if ( 'attachment_page' === $thumbnail_link ) {
								$url = get_permalink();
							} else {
								$url = wp_get_attachment_url( $atts['post_id'] );
							}

							// Open link tag.
							$link_attrs = array(
								'href'   => $url,
								'class'  => 'wpex-carousel-entry-img',
								'target' => $custom_links_target,
							);

							$output .= '<a' . vcex_parse_html_attributes( $link_attrs ) . '>';

								// Display Image.
								$output .= $image_output;

								// Inner link overlay HTML.
								$output .= vcex_get_entry_image_overlay( 'inside_link', 'vcex_image_carousel', $atts );

							$output .= '</a>';

						}

						// Custom Link.
						elseif ( 'custom_link' == $thumbnail_link && $atts['post_url'] ) {

							$link_attrs = array(
								'href'   => $atts['post_url'],
								'class'  => 'wpex-carousel-entry-img',
								'target' => $custom_links_target,
							);

							$output .= '<a' . vcex_parse_html_attributes( $link_attrs ) . '>';

								// Display Image.
								$output .= $image_output;

								// Inner link overlay HTML.
								$output .= vcex_get_entry_image_overlay( 'inside_link', 'vcex_image_carousel', $atts );

							$output .= '</a>';

						}

						// No link.
						else {

							// Display Image.
							$output .= $image_output;

							// Inner link overlay HTML.
							$output .= vcex_get_entry_image_overlay( 'inside_link', 'vcex_image_carousel', $atts );

						}

						// Outer link overlay HTML.
						$output .= vcex_get_entry_image_overlay( 'outside_link', 'vcex_image_carousel', $atts );

					} // end video/image check.

				$output .= '</figure>';

				/*--------------------------------*/
				/* [ Details ]
				/*--------------------------------*/
				if ( ( 'yes' === $title && $attachment_title ) || (  'yes' === $caption && $atts['post_caption'] ) ) :

					$output .= '<div class="' . esc_attr( implode( ' ', vcex_get_entry_details_class( array( 'wpex-carousel-entry-details' ), 'vcex_image_carousel', $atts ) ) ) . '"' . $content_style . '>';

						/*--------------------------------*/
						/* [ Title ]
						/*--------------------------------*/
						if ( 'yes' === $title && $attachment_title ) {

							$output .= '<div class="' . esc_attr( implode( ' ', vcex_get_entry_title_class( array( 'wpex-carousel-entry-title' ), 'vcex_image_carousel', $atts ) ) ) . '"' . $heading_style . '>';

								$output .= wp_kses_post( $attachment_title );

							$output .= '</div>';

						}

						/*--------------------------------*/
						/* [ Caption ]
						/*--------------------------------*/
						if ( 'yes' === $caption && $atts['post_caption'] ) {

							$output .= '<div class="' . esc_attr( implode( ' ', vcex_get_entry_excerpt_class( array( 'wpex-carousel-entry-excerpt' ), 'vcex_image_carousel', $atts ) ) ) . '">';

								$output .= do_shortcode( wp_kses_post( $atts['post_caption'] ) );

							$output .= '</div>';

						}

					$output .= '</div>';

				endif;

			$output .= '</div>';

		endwhile;

	$output .= '</div>';

	// Close wrap for single item auto height.
	if ( 'true' == $auto_height ) {

		$output .= '</div>';

	}

	// Reset the post data to prevent conflicts with WP globals.
	wp_reset_postdata();

	// @codingStandardsIgnoreLine.
	echo $output;

// End Query.
endif;