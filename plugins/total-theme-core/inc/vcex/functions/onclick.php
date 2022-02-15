<?php
/**
 * Shortcode onclick functions.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return shortcode onclick attributes.
 */
function vcex_get_shortcode_onclick_attributes( $atts = array(), $shortcode_tag = '' ) {

	$attrs = array(
		'href'  => '',
		'class' => array(), // always return empty array for adding classes.
	);

	$has_lightbox = false;

	$onclick = $atts['onclick'] ?? '';

	switch ( $onclick ) {

		case 'internal_link':

			if ( ! empty( $atts['onclick_internal_link'] ) ) {

				$internal_link = vcex_build_link( $atts['onclick_internal_link'] );

				if ( ! empty( $internal_link['url'] ) ) {
					$attrs['href'] = $internal_link[ 'url' ];
				} else {
					$attrs['href'] = '#'; // @maybe this isn't a good idea?
				}

			}

			break;

		case 'custom_field':

			if ( ! empty( $atts['onclick_custom_field'] ) ) {
				$attrs['href'] = get_post_meta( vcex_get_the_ID(), $atts['onclick_custom_field'], true );
			}

			break;

		case 'callback_function':

			if ( ! empty( $atts['onclick_callback_function'] ) && function_exists( $atts['onclick_callback_function'] ) ) {
				$attrs['href'] = call_user_func( $atts['onclick_callback_function'] );
			}

			break;

		case 'lightbox_image':

			$has_lightbox = true;
			$attrs['class'][] = 'wpex-lightbox';
			$lightbox_image = '';

			if ( ! empty( $atts['onclick_lightbox_image'] ) && is_numeric( $atts['onclick_lightbox_image'] ) ) {
				$lightbox_image = vcex_get_lightbox_image( $atts['onclick_lightbox_image'] );
			}

			if ( $lightbox_image && is_string( $lightbox_image ) ) {
				$attrs['href'] = $lightbox_image;
			} elseif ( isset( $atts['onclick_url'] ) ) {
				$attrs['href'] = $atts['onclick_url'];
			}

			break;

		case 'lightbox_video':
		case 'lightbox_post_video':

			$has_lightbox = true;
			$attrs['class'][] = 'wpex-lightbox';

			if ( 'lightbox_post_video' == $onclick ) {
				$attrs['href'] = vcex_get_post_video_oembed_url( vcex_get_the_ID() );
			} elseif ( isset( $atts['onclick_url'] ) ) {
				$attrs['href'] = $atts['onclick_url'];
			}

			break;

		case 'lightbox_gallery':
		case 'lightbox_post_gallery':
			$has_lightbox = true;
			$attrs['href'] = '#';
			$attrs['class'][] = 'wpex-lightbox-gallery';
			break;

		case 'popup':

			$has_lightbox = true;
			$attrs['class'][] = 'wpex-lightbox';

			if ( isset( $atts['onclick_url'] ) ) {
				$attrs['href'] = $atts['onclick_url'];
			}

			break;

		case 'local_scroll':
			if ( isset( $atts['onclick_url'] ) ) {
				$attrs['href'] = $atts['onclick_url'];
				$attrs['class'][] = 'local-scroll-link';
				unset( $atts['target'] );
				unset( $atts['rel'] );
			}
			break;

		case 'toggle_element':
			$attrs['href'] = $atts['onclick_url'];
			$attrs['class'][] = 'wpex-toggle-element-trigger';
			$attrs['aria-controls'] = $atts['onclick_url'];
			if ( empty( $atts['onclick_data_attributes'] ) || false === strpos( $atts['onclick_data_attributes'], 'aria-expanded' ) ) {
				$attrs['aria-expanded'] = 'false';
			}
			break;

		case 'custom_link':
		default:
			if ( isset( $atts['onclick_url'] ) ) {
				$attrs['href'] = $atts['onclick_url'];
			}
			break;

	}

	// Custom title attribute.
	if ( ! empty( $atts['onclick_title'] ) ) {
		$attrs['title'] = $atts['onclick_title'];
	}

	// Custom target.
	if ( ! empty( $atts['onclick_target'] ) ) {
		$attrs['target'] = $atts['onclick_target'];
	}

	// Custom rel attribute.
	if ( ! empty( $atts['onclick_rel'] ) ) {
		$attrs['rel'] = $atts['onclick_rel'];
	}

	// Lightbox additions.
	if ( $has_lightbox ) {

		// No target or rel needed for lightbox links.
		unset( $atts['target'] );
		unset( $atts['rel'] );

		// Enqueue lightbox scripts.
		vcex_enqueue_lightbox_scripts();

		// Get lightbox settings
		$lightbox_settings = vcex_get_shortcode_onclick_lightbox_settings( $atts, $shortcode_tag );

		if ( $lightbox_settings ) {
			foreach( $lightbox_settings as $key => $value) {
				$attrs['data-' . $key] = $value;
			}
		}

	}

	// Check for custom data attributes.
	if ( ! empty( $atts['onclick_data_attributes'] ) ) {
		$custom_data = $atts['onclick_data_attributes'];
		if ( is_string( $custom_data ) ) {
			$custom_data = explode( ',', $custom_data );
		}
		if ( $custom_data && is_array( $custom_data ) ) {
			foreach( $custom_data as $data ) {
				if ( is_string( $data ) && false !== strpos( $data, '|' ) ) {
					$data = explode( '|', $data );
					$attrs['data-' . esc_attr( $data[0] )] = esc_attr( do_shortcode( $data[1] ) );
				} else {
					$attrs['data-' . esc_attr( $data )] = '';
				}
			}
		}
	}

	// Parse href result.
	if ( ! empty( $attrs['href'] ) ) {

		// Sanitize url.
		$attrs['href'] = esc_url( do_shortcode( trim( $attrs['href'] ) ) );

		// Set correct url scheme for lightbox urls to prevent errors.
		if ( in_array( $onclick, array( 'lightbox_image', 'lightbox_video', 'popup' ) ) ) {
			$attrs['href'] = set_url_scheme( $attrs['href'] );
		}

	}

	// Apply filters and return attributes array.
	return apply_filters( 'vcex_shortcode_onclick_attributes', $attrs, $atts, $shortcode_tag );

}

/**
 * Return shortcode lightbox settings.
 */
function vcex_get_shortcode_onclick_lightbox_settings( $atts = array(), $shortcode_tag = '' ) {

	$settings = array();

	$onclick = '';

	if ( isset( $atts['onclick'] ) ) {
		$onclick = $atts['onclick'];
	}

	switch ( $onclick ) {
		case 'popup':

			$type = 'iframe';

			if ( ! empty( $atts['onclick_url'] ) ) {

				$url = $atts['onclick_url'];

				if ( function_exists( 'str_starts_with' ) ) {
					if ( str_starts_with( $url, '#' ) ) {
						$type = 'inline';
					}
				} elseif ( substr( $url, 0, 1 ) === '#' ) {
					$type = 'inline';
				}

			}

			$settings['type'] = $type;

			break;
		case 'lightbox_gallery':
		case 'lightbox_post_gallery':

			if ( 'lightbox_post_gallery' == $onclick ) {
				$post_gallery_attachments = vcex_get_post_gallery_ids();
				if ( ! empty( $post_gallery_attachments ) && is_array( $post_gallery_attachments ) ) {
					$lightbox_gallery_attachments = $post_gallery_attachments;
				}
			}

			// Custom gallery should show as a backup if the post gallery is enabled but there aren't any pictures.
			// This is because of how the image element used to work pre Total 5.1.
			if ( 'lightbox_gallery' == $onclick || empty( $lightbox_gallery_attachments ) ) {
				if ( ! empty( $atts['onclick_lightbox_gallery'] ) && is_string( $atts['onclick_lightbox_gallery'] ) ) {
					$lightbox_gallery_attachments = explode( ',', $atts['onclick_lightbox_gallery'] );
				}
			}

			if ( ! empty( $lightbox_gallery_attachments ) && is_array( $lightbox_gallery_attachments ) ) {
				$settings['gallery'] = vcex_parse_inline_lightbox_gallery( $lightbox_gallery_attachments );
			}

			break;
		default:
			break;
	}

	// Check for custom lightbox dimensions.
	if ( ! empty( $atts['onclick_lightbox_dims'] ) && in_array( $onclick, array( 'lightbox_video', 'popup' ) ) ) {
		$lightbox_dims = vcex_parse_lightbox_dims( $atts['onclick_lightbox_dims'], 'array' );
		if ( ! empty( $lightbox_dims['width'] ) ) {
			$settings['width'] = $lightbox_dims['width'];
		}
		if ( ! empty( $lightbox_dims['height'] ) ) {
			$settings['height'] = $lightbox_dims['height'];
		}
	}

	// Lightbox title.
	if ( ! empty( $atts['onclick_lightbox_title'] ) ) {
		$settings['title'] = esc_attr( $atts['onclick_lightbox_title'] );
	}

	// Lightbox caption.
	if ( ! empty( $atts['onclick_lightbox_caption'] ) ) {
		$settings['caption'] = str_replace( '"',"'", wp_kses_post( $atts['onclick_lightbox_caption'] ) );
	}

	return (array) apply_filters( 'vcex_shortcode_onclick_lightbox_settings', $settings, $atts, $shortcode_tag );

}