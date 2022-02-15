<?php
/**
 * vcex_image_grid shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_image_grid', $atts ) ) {
	return;
}

// Define output var
$output = '';

// Define 3rd party attributes that do not exist in atts by default.
$rml_folder = '';

// Store orginal atts value for use in non-builder params.
$og_atts = $atts;

// Define entry counter.
$entry_count = ! empty( $og_atts['entry_count'] ) ? $og_atts['entry_count'] : 0;

// Get and extract shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_image_grid', $atts, $this );
extract( $atts );

// Sanitize atts.
$overlay_style = ! empty( $overlay_style ) ? $overlay_style : 'none';

// Get images from custom field.
if ( ! empty( $custom_field_gallery ) ) {

	$image_ids = get_post_meta( vcex_get_the_ID(), trim( $custom_field_gallery ), true );

// Get images from post gallery.
} elseif ( 'true' == $post_gallery ) {
	if ( ! wp_doing_ajax() ) {
		$og_atts['post_id'] = vcex_get_the_ID(); // important for load more function.
	}
	$image_ids = vcex_get_post_gallery_ids( $og_atts['post_id'] );
}

// Get images based on Real Media folder.
elseif ( defined( 'RML_VERSION' ) && $rml_folder ) {
	$rml_query = new WP_Query( array(
		'post_status'    => 'inherit',
		'posts_per_page' => -1,
		'post_type'      => 'attachment',
		'orderby'        => 'rml', // Order by custom order of RML
		'rml_folder'     => $rml_folder,
		'fields'         => 'ids',
	) );
	if ( $rml_query->have_posts() ) {
		$image_ids = $rml_query->posts;
	}
}

// If there aren't any images return.
if ( empty( $image_ids ) ) {
	return;
}

// Otherwise if there are images lets turn it into an array.
else {

	// Get image ID's.
	if ( is_string( $image_ids ) ) {
		$attachment_ids = explode( ',', $image_ids );
	} else {
		$attachment_ids = $image_ids;
	}

}

// Apply filters.
$attachment_ids = apply_filters( 'vcex_image_grid_attachment_ids', $attachment_ids, $atts );

// Lets do some things now that we have images.
if ( ! empty ( $attachment_ids ) ) :

	// Declare vars.
	$is_isotope = false;

	// Remove duplicate images.
	$attachment_ids = array_unique( $attachment_ids );

	// Turn links into array.
	if ( $custom_links ) {
		$custom_links = explode( ',', $custom_links );
	} else {
		$custom_links = array();
	}

	// Count items.
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

	// Pagination variables.
	$posts_per_page = $posts_per_page ?: '-1';
	$paged          = NULL;
	$no_found_rows  = true;
	if ( '-1' != $posts_per_page ) {
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}
		$no_found_rows = false;
	}

	// Lets create a new Query so the image grid can be paginated.
	$vcex_query = new WP_Query( array(
		'post_type'      => 'attachment',
		//'post_mime_type'    => 'image/jpeg,image/gif,image/jpg,image/png',
		'post_status'    => 'any',
		'posts_per_page' => $posts_per_page,
		'paged'          => ! empty( $og_atts['paged'] ) ? $og_atts['paged'] : $paged,
		'post__in'       => $attachment_ids,
		'no_found_rows'  => $no_found_rows,
		'orderby'        => ! empty( $atts['orderby'] ) ? $atts['orderby'] : 'post__in',
		'order'          => $atts['order']
	) );

	// Display images if we found some.
	if ( $vcex_query->have_posts() ) :

		$output .= '<div class="vcex-image-grid-wrap wpex-clr">';

		// Define grid style settings and enqueue scripts.
		switch ( $grid_style ) {
			case 'justified':
				vcex_enqueue_justified_gallery_scripts();
				break;
			case 'masonry':
			case 'no-margins':
				$is_isotope = true;
				vcex_enqueue_isotope_scripts();
				break;
		}

		// Link target.
		$atts['link_target'] = $custom_links_target;

		// Wrap Classes.
		$wrap_classes = array(
			'vcex-module',
			'vcex-image-grid',
			'wpex-clr'
		);

		if ( 'justified' === $grid_style ) {
			$wrap_classes[] = 'vcex-justified-gallery';
		} else {
			$wrap_classes[] = 'grid-style-' . sanitize_html_class( $grid_style );
			$wrap_classes[] = 'wpex-row';
		}

		if ( $columns_gap && 'justified' !== $grid_style ) {
			$wrap_classes[] = 'gap-' . sanitize_html_class( $columns_gap );
		}

		if ( $is_isotope ) {
			$wrap_classes[] = 'vcex-isotope-grid no-transition';
		}

		if ( 'no-margins' === $grid_style ) {
			$wrap_classes[] = 'vcex-no-margin-grid';
		}

		if ( 'lightbox' === $thumbnail_link ) {
			if ( 'true' === $lightbox_gallery ) {
				$wrap_classes[] = 'wpex-lightbox-group';
			}
		}

		if ( $classes ) {
			$wrap_classes[] = vcex_get_extra_class( $classes );
		}

		if ( $visibility ) {
			$wrap_classes[] = vcex_parse_visibility_class( $visibility );
		}

		// Wrap data attributes
		$wrap_data = array();

		switch ( $grid_style ) {
			case 'justified':
				$justified_gallery_settings = array(
					'selector'  => 'vcex-image-grid-entry',
					'margins'   => $justified_row_margin ? absint( $justified_row_margin ) : 5,
					'rowHeight' => $justified_row_height ? absint( $justified_row_height ) : 200,
					'lastRow'   => $justified_last_row ? wp_strip_all_tags( $justified_last_row ) : 'justified',
					'captions'  => false,
				);
				if ( is_rtl() ) {
					$justified_gallery_settings['rtl'] = true;
				}
				$justified_gallery_settings = (array) apply_filters( 'vcex_image_grid_justified_gallery_settings', $justified_gallery_settings, $atts );
				$wrap_data[] = 'data-justified-gallery="' . esc_attr( htmlspecialchars( wp_json_encode( $justified_gallery_settings ) ) ) . '"';
				break;
			case 'masonry':
			case 'no-margins':
				$wrap_data[] = 'data-transition-duration="0.0"';
				break;
		}

		if ( 'lightbox' === $thumbnail_link ) {
			$lightbox_data = array();
			if ( $lightbox_path ) {
				if ( 'disabled' === $lightbox_path ) {
					$lightbox_data[] = 'data-thumbnails="false"';
				}
			}
			if ( ! $lightbox_title || 'false' === $lightbox_title ) {
				$lightbox_data[] = 'data-show_title="false"';
			}
			vcex_enqueue_lightbox_scripts();
			$wrap_data = array_merge( $wrap_data, $lightbox_data );
		}

		// Columns classes
		$columns_class = vcex_get_grid_column_class( $atts );

		// Entry Classes
		$entry_classes = array(
			'vcex-image-grid-entry',
			'vcex-grid-item'
		);

		if ( $is_isotope ) {
			$entry_classes[] = 'vcex-isotope-entry';
		}

		if ( $content_alignment && 'none' !== $content_alignment ) {
			$entry_classes[] = 'text' . sanitize_html_class( $content_alignment ); // doesn't swap in RTL.
		}

		if ( 'justified' !== $grid_style ) {

			if ( 'no-margins' === $grid_style ) {
				$entry_classes[] = 'vcex-no-margin-entry';
			}

			if ( $columns ) {
				$entry_classes[] = $columns_class;
			}

			if ( 'false' === $responsive_columns ) {
				$entry_classes[] = 'nr-col';
			} else {
				$entry_classes[] = 'col';
			}

		}

		if ( $css_animation && 'none' !== $css_animation && 'justified' !== $grid_style ) {
			$entry_classes[] = vcex_get_css_animation( $css_animation );
		}

		// Figure classes - image + caption
		$figure_classes = array(
			'vcex-image-grid-entry-figure',
			'wpex-last-mb-0',
			'wpex-clr'
		);

		if ( ! empty( $vertical_align ) && 'none' !== $vertical_align ) {

			switch ( $vertical_align ) {
				case 'top':
					$vertical_align = 'start';
					break;
				case 'bottom':
					$vertical_align = 'end';
					break;
			}


			$figure_classes[] = 'wpex-flex wpex-flex-col wpex-flex-grow wpex-justify-' . sanitize_html_class( $vertical_align );
		}

		if ( $entry_css ) {
			$figure_classes[] = vcex_vc_shortcode_custom_css_class( $entry_css );
		}

		// Lightbox class
		if ( 'true' === $lightbox_gallery ) {
			$lightbox_class = 'wpex-lightbox-group-item';
		} else {
			$lightbox_class = 'wpex-lightbox';
		}

		// Title style & title related vars
		if ( 'yes' === $title ) {
			$title_tag_escaped = $title_tag ? tag_escape( $title_tag ) : 'h2';
			$title_type  = $title_type ?: 'title';
			$title_style = vcex_inline_style( array(
				'font_size'      => $title_size,
				'color'          => $title_color,
				'text_transform' => $title_transform,
				'line_height'    => $title_line_height,
				'margin'         => $title_margin,
				'font_weight'    => $title_weight,
				'font_family'    => $title_font_family,
			) );
		}

		// Content style & title related vars
		if ( 'true' === $excerpt ) {
			$excerpt_style = vcex_inline_style( array(
				'font_size'      => $excerpt_size,
				'color'          => $excerpt_color,
				'text_transform' => $excerpt_transform,
				'line_height'    => $excerpt_line_height,
				'margin'         => $excerpt_margin,
				'font_weight'    => $excerpt_weight,
				'font_family'    => $excerpt_font_family,
			) );
		}

		// Link attributes
		if ( $link_attributes ) {
			$link_attributes_array = explode( ',', $link_attributes );
			if ( is_array( $link_attributes_array ) ) {
				$link_attributes = '';
				foreach( $link_attributes_array as $attribute ) {
					if ( false !== strpos( $attribute, '|' ) ) {
						$attribute = explode( '|', $attribute );
						$link_attributes .= ' ' . esc_attr( $attribute[0] ) .'="' . esc_attr( do_shortcode( $attribute[1] ) ) . '"';
					}
				}
			}
		}

		// Convert arrays to strings
		$wrap_classes = implode( ' ', $wrap_classes );

		// Apply filters
		$wrap_classes = vcex_parse_shortcode_classes( $wrap_classes, 'vcex_image_grid', $atts );

		// Wrap attributes
		$wrap_attrs = array(
			'id'    => vcex_get_unique_id( $unique_id ),
			'class' => $wrap_classes,
			'data'  => implode( ' ', $wrap_data ),
		);

		// Open CSS div
		if ( $css ) {

			$output .= '<div class="vcex-image-grid-css-wrapper ' . vcex_vc_shortcode_custom_css_class( $css ) . '">';

		}

		/*--------------------------------*/
		/* [Header ]
		/*--------------------------------*/
		if ( $header ) {
			$output .= vcex_get_module_header( array(
				'style'   => $header_style,
				'content' => $header,
				'classes' => array(
					'vcex-module-heading',
					'vcex_image_grid-heading'
				),
			) );
		}

		/*--------------------------------*/
		/* [ Begin Grid output ]
		/*--------------------------------*/
		$output .= '<div' . vcex_parse_html_attributes( $wrap_attrs ) . '>';

			// Loop through images.
			while ( $vcex_query->have_posts() ) :

				// Reset thubmnail type at start - important!
				$thumbnail_link = $atts['thumbnail_link'];

				// Add to entry count.
				$entry_count++;

				// Get post from query.
				$vcex_query->the_post();

				// Get post data and define main vars.
				$post_id          = get_the_ID();
				$post_data        = vcex_get_attachment_data( $post_id );
				$link_url         = '';
				$link_title_att   = '';
				$post_alt_escaped = esc_attr( wp_strip_all_tags( $post_data['alt'] ) );
				$og_attachment_id = $post_id; // Original attachment ID used to locate it's custom link.

				// Get original attachment ID - fix for WPML.
				if ( $custom_links_count && function_exists( 'icl_object_id' ) ) {
					global $sitepress;
					if ( $sitepress ) {
						$_icl_lang_duplicate_of = get_post_meta( $post_id, '_icl_lang_duplicate_of', true );
						$wpml_attachment_id = icl_object_id( $post_id, 'attachment', false, $sitepress->get_default_language() );
						if ( ! array_key_exists( $wpml_attachment_id, $images_links_array ) ) {
							$wpml_attachment_id = icl_object_id( $post_id, 'attachment', false, apply_filters( 'wpml_current_language', NULL ) );
						}
						if ( array_key_exists( $wpml_attachment_id, $images_links_array ) ) {
							$og_attachment_id = $wpml_attachment_id;
						}
					}
				}

				// Pluck array to see if item has custom link.
				if ( array_key_exists( $og_attachment_id, $images_links_array ) ) {
					$link_url = $images_links_array[$og_attachment_id];
				}

				// Remove links if the post_url is a # symbol.
				if ( '#' === $link_url ) {
					$link_url = '';
				}

				// Check for custom meta links (if the link is defined by the attachment meta).
				if ( 'custom_link' === $thumbnail_link && $link_meta_key ) {
					$meta_custom_link = get_post_meta( $post_id, wp_strip_all_tags( $link_meta_key ), true );
					if ( ! empty( $meta_custom_link ) ) {
						$link_url = $meta_custom_link;
					}
				} else {
					$meta_custom_link = get_post_meta( $post_id, '_wpex_custom_link', true );
					if ( ! empty( $meta_custom_link ) ) {
						$thumbnail_link = 'custom_link';
						$link_url = $meta_custom_link;
					}
				}

				// If $thumbnail_link is set to custom_link but there is no link set the $thumbnail_link to null.
				if ( 'custom_link' === $thumbnail_link && empty( $link_url ) ) {
					$thumbnail_link = null;
				}

				// Define thumbnail args.
				$thumbnail_args = array(
					'size'          => $img_size,
					'attachment'    => $post_id,
					'alt'           => $post_alt_escaped,
					'width'         => $img_width,
					'height'        => $img_height,
					'crop'          => $img_crop,
					'class'         => implode( ' ', vcex_get_entry_thumbnail_class( null, 'vcex_image_grid', $atts ) ),
					'apply_filters' => 'vcex_image_grid_thumbnail_args',
					'filter_arg1'   => $atts,
				);

				// Add data-no-lazy to prevent conflicts with WP-Rocket.
				if ( $is_isotope || 'justified' == $grid_style ) {
					$thumbnail_args['attributes'] = array( 'data-no-lazy' => 1 );
				}

				// Set image HTML since we'll use it a lot later on.
				$post_thumbnail = vcex_get_post_thumbnail( $thumbnail_args );

				// Entry classes (don't set to entry_classes because this isn't reset on every item).
				$loop_entry_classes = implode( ' ', $entry_classes );

				if ( 'justified' !== $grid_style && is_array( $entry_classes ) ) {
					$loop_entry_classes .= ' col-' . sanitize_html_class( $entry_count );
				}

				// Begin entry output.
				$output .= '<div class="id-' . esc_attr( $post_id ) . ' ' . esc_attr( $loop_entry_classes ) . '">';

					// Open figure element.
					$output .= '<figure class="' . esc_attr( implode( ' ', $figure_classes ) ) . '">';

						// Define media type.
						$atts['media_type'] = 'thumbnail';

						// Image wrap.
						$output .= '<div class="' . esc_attr( implode( ' ', vcex_get_entry_media_class( array( 'vcex-image-grid-entry-img' ), 'vcex_image_grid', $atts ) ) ) . '">';

							switch ( $thumbnail_link ) :

								// Lightbox link.
								case 'lightbox':

									// Define lightbox vars.
									$atts['lightbox_data'] = $lightbox_data;
									$lightbox_image        = vcex_get_lightbox_image( $post_id );
									$lightbox_url          = $lightbox_image;
									$video_url             = $post_data['video'];

									// Data attributes.
									if ( 'false' !== $lightbox_title ) {
										if ( 'title' === $lightbox_title ) {
											$data_title = get_the_title( $post_id );
											if ( $data_title ) {
												$atts['lightbox_data']['data-title'] = 'data-title="' . esc_attr( wp_strip_all_tags( $data_title ) ) . '"';
											}
										} elseif ( 'alt' === $lightbox_title ) {
											if ( $post_alt_escaped ) {
												$atts['lightbox_data']['data-title'] = 'data-title="' . $post_alt_escaped . '"';
											}
										}
									}

									// Caption data.
									if ( 'false' !== $lightbox_caption && $post_data['caption'] ) {
										$atts['lightbox_data']['data-caption'] = 'data-caption="' . str_replace( '"',"'", $post_data['caption'] ) . '"';
									}

									// Video data.
									if ( $video_url ) {
										$video_embed_url = vcex_get_video_embed_url( $video_url );
										$lightbox_url    = $video_embed_url ?: $video_url;
										$atts['lightbox_data']['data-thumb'] = 'data-thumb="'. $lightbox_image .'"';
									}

									// Apply filters to lightbox data.
									$atts['lightbox_data'] = apply_filters( 'vcex_image_grid_lightbox_data', $atts['lightbox_data'], $atts, $post_id );

									// Convert data attributes to array.
									$atts['lightbox_data'] = ' ' . implode( ' ', $atts['lightbox_data'] );

									// Add lightbox class to atts.
									$atts['lightbox_class'] = $lightbox_class;

									// Get title tag if enabled.
									if ( 'true' === $link_title_tag ) {
										$link_title_att = vcex_html( 'title_attr', $post_alt_escaped, false );
									}

									// Open link tag.
									$output .= '<a href="' . esc_url( $lightbox_url ) . '" class="vcex-image-grid-entry-img ' . $atts['lightbox_class'] . '"' . $link_title_att . $atts["lightbox_data"] . $link_attributes .'>';

										// Display image.
										$output .= $post_thumbnail;

										// Video icon overlay.
										if ( $video_url && 'none' === $overlay_style ) {
											$output .= '<div class="overlay-icon"><span>&#9658;</span></div>';
										}

										// Inner link overlay HTML.
										$output .= vcex_get_entry_image_overlay( 'inside_link', 'vcex_image_grid', $atts );

									$output .= '</a>';

									break;

								// Attachment link.
								case 'attachment_page':
								case 'full_image':

									// Get URL.
									if ( 'attachment_page' === $thumbnail_link ) {
										$url = get_permalink();
									} else {
										$url = wp_get_attachment_url( $post_id );
									}

									// Set title tag if enabled.
									if ( 'true' === $link_title_tag && $post_alt_escaped ) {
										$link_title_att = vcex_html( 'title_attr', $post_alt_escaped, false );
									}

									// Link target.
									$link_target = vcex_html( 'target_attr', $atts['link_target'], false );

									// Open link tag.
									$output .= '<a href="' . esc_url( $url ) . '" class="vcex-image-grid-entry-img"' . $link_title_att . $link_target . $link_attributes . '>';

										// Display image.
										$output .= $post_thumbnail;

										// Inner link overlay HTML.
										$output .= vcex_get_entry_image_overlay( 'inside_link', 'vcex_image_grid', $atts );

									$output .= '</a>';

									break;

								// Custom link.
								case 'custom_link':

									// Set title tag if enabled.
									if ( 'true' === $link_title_tag ) {
										$link_title_att = vcex_html( 'title_attr', $post_alt_escaped, false );
									}

									// Link target.
									$link_target = vcex_html( 'target_attr', $atts['link_target'], false );

									// Open link tag.
									$output .= '<a href="' . esc_url( $link_url ) . '" class="vcex-image-grid-entry-img"' . $link_title_att . $link_target . $link_attributes . '>';

										// Display image.
										$output .= $post_thumbnail;

										// Inner link overlay HTML.
										$output .= vcex_get_entry_image_overlay( 'inside_link', 'vcex_image_grid', $atts );

									$output .= '</a>';

									break;

								// Parent page (Uploaded to link)
								case 'parent_page':

									$post_parent = get_post_parent( $post_id );

									// Open link.
									if ( $post_parent ) {
										$output .= '<a href="' . esc_url( get_permalink( $post_parent ) ) . '" class="vcex-image-grid-entry-img"' . $link_attributes . '>';

									}

										// Display image.
										$output .= $post_thumbnail;

										// Inner link overlay HTML.
										$output .= vcex_get_entry_image_overlay( 'inside_link', 'vcex_image_grid', $atts );

									// Close link.
									if ( $post_parent ) {
										$output .= '</a>';
									}

									break;

							// Just the Image - no link.
							default:

								// Display image.
								$output .= $post_thumbnail;

								// Inner link overlay HTML.
								$output .= vcex_get_entry_image_overlay( 'inside_link', 'vcex_image_grid', $atts );

							endswitch;

							// Outside link overlay html.
							if ( 'none' !== $overlay_style ) {

								if ( 'custom_link' === $thumbnail_link && $link_url ) {
									$atts['overlay_link'] = $link_url;
								} elseif( 'lightbox' === $thumbnail_link && $lightbox_url ) {
									$atts['lightbox_link'] = $lightbox_url;
								}

								// Outer link overlay HTML.
								$output .= vcex_get_entry_image_overlay( 'outside_link', 'vcex_image_grid', $atts );

							}

						// Close image wrap.
						$output .= '</div>';


						// Title.
						if ( 'yes' === $title && 'justified' !== $grid_style ) {

							// Get correct title.
							switch ( $title_type ) {
								case 'title':
									$post_title_display = get_the_title();
									break;
								case 'alt':
									$post_title_display = $post_alt_escaped;
									break;
								case 'caption':
									$post_title_display = wp_get_attachment_caption();
									break;
								case 'description':
									$post_title_display = get_the_content();
									break;
								default:
									$post_title_display = '';
									break;
							}

							// Display title.
							if ( $post_title_display ) {

								$output .= '<figcaption class="vcex-image-grid-entry-title wpex-mb-10 wpex-clr">';

									$output .= '<'. $title_tag_escaped . $title_style .' class="entry-title">';

										$output .= wp_kses_post( $post_title_display );

									$output .= '</'. $title_tag_escaped .'>';

								$output .= '</figcaption>';

							}

						}

						// Excerpt.
						if ( 'true' === $excerpt && 'justified' !== $grid_style ) {

							switch ( $excerpt_type ) {
								case 'caption':
									$excerpt_display = wp_get_attachment_caption();
									break;
								case 'description':
									$excerpt_display = get_the_content();
									break;
								default:
									$excerpt_display = '';
									break;
							}

							if ( $excerpt_display ) {

								$output .= '<div class="vcex-image-grid-entry-excerpt wpex-mb-20 wpex-clr"' . $excerpt_style . '>';

									$output .= wp_kses_post( $excerpt_display );

								$output .= '</div>';

							}

						}

					$output .= '</figure>';

				$output .= '</div>';

				// Clear counter.
				if ( $entry_count === (int) $columns ) {
					$entry_count = 0;
				}

			// End while loop.
			endwhile;

		$output .= '</div>';

		// Close CSS div.
		if ( $css ) {
			$output .= '</div>';
		}

		// Display pagination if enabled.
		if ( ( '-1' !== $posts_per_page && 'true' === $pagination ) && 'true' !== $atts['pagination_loadmore'] ) {

			$output .= vcex_pagination( $vcex_query, false );

		}

		// Load more button.
		if ( 'true' === $atts['pagination_loadmore'] && ! empty( $vcex_query->max_num_pages ) ) {
			vcex_loadmore_scripts();
			$og_atts['entry_count'] = $entry_count; // Update counter
			$output .= vcex_get_loadmore_button( 'vcex_image_grid', $og_atts, $vcex_query );
		}

	$output .= '</div>'; // end wrap.

	endif; // End Query.

	// Reset the post data to prevent conflicts with WP globals.
	wp_reset_postdata();

	// @codingStandardsIgnoreLine.
	echo $output;

// End image check.
endif;