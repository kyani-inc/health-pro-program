<?php
/**
 * Image Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.1
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Image_Shortcode' ) ) {

	class VCEX_Image_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_image', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Image::instance();
			}
		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_image', $atts );
			include( vcex_get_shortcode_template( 'vcex_image' ) );
			do_action( 'vcex_shortcode_after', 'vcex_image', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {
			$params = array(
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Source', 'total-theme-core' ),
					'param_name' => 'source',
					'std' => 'media_library',
					'value' => array(
						esc_html__( 'Media Library', 'total-theme-core' ) => 'media_library',
						esc_html__( 'External', 'total-theme-core' ) => 'external',
						esc_html__( 'Custom Field', 'total-theme-core' ) => 'custom_field',
						esc_html__( 'Featured Image', 'total-theme-core' ) => 'featured',
						esc_html__( 'Post Author Avatar', 'total-theme-core' ) => 'author_avatar',
						esc_html__( 'Current User Avatar', 'total-theme-core' ) => 'user_avatar',
						esc_html__( 'Callback Function', 'total-theme-core' ) => 'callback_function',
					),
					'admin_label' => true,
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__( 'Image', 'total-theme-core' ),
					'param_name' => 'image_id',
					'dependency' => array( 'element' => 'source', 'value' => 'media_library' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'External Image URL', 'total-theme-core' ),
					'param_name' => 'external_image',
					'dependency' => array( 'element' => 'source', 'value' => 'external' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Custom Field Name', 'total-theme-core' ),
					'param_name' => 'custom_field_name',
					'dependency' => array( 'element' => 'source', 'value' => 'custom_field' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Callback Function', 'total-theme-core' ),
					'param_name' => 'callback_function',
					'dependency' => array( 'element' => 'source', 'value' => 'callback_function' ),
					'admin_label' => true,
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Caption', 'total-theme-core' ),
					'param_name' => 'caption',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Alt Attribute', 'total-theme-core' ),
					'param_name' => 'alt_attr',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Image Title', 'total-theme-core' ),
					'param_name' => 'img_title',
					'description' => esc_html__( 'Used for image overlay styles.', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Image Caption', 'total-theme-core' ),
					'param_name' => 'img_caption',
					'description' => esc_html__( 'Used when enabling the image caption or when using image overlay styles that display excerpts.', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_visibility',
					'heading' => esc_html__( 'Visibility', 'total-theme-core' ),
					'param_name' => 'visibility',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'total-theme-core' ),
					'param_name' => 'el_class',
					'description' => vcex_shortcode_param_description( 'el_class' ),
				),
				vcex_vc_map_add_css_animation(),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Animation Duration', 'total'),
					'param_name' => 'animation_duration',
					'description' => esc_html__( 'Enter your custom time in seconds (decimals allowed).', 'total'),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Animation Delay', 'total'),
					'param_name' => 'animation_delay',
					'description' => esc_html__( 'Enter your custom time in seconds (decimals allowed).', 'total'),
				),
				// Style
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin', // can't name it margin_bottom due to WPBakery parsing issue
					'value' => vcex_margin_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Width', 'total-theme-core' ),
					'param_name' => 'width',
					'description' => esc_html__( 'Constrain your image to a specific width without having to crop it. Can also be used to force a specific width on an SVG image. Enter 100% to stretch your image to fill the parent container.', 'total-theme-core' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Align', 'total-theme-core' ),
					'param_name' => 'align',
					'std' => '',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Border Radius', 'total-theme-core' ),
					'param_name' => 'border_radius',
					'description' => vcex_shortcode_param_description( 'border_radius' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Shadow', 'total' ),
					'param_name' => 'shadow',
					'value' => vcex_shadow_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_image_filters',
					'heading' => esc_html__( 'Image Filter', 'total-theme-core' ),
					'param_name' => 'img_filter',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_hover_animations',
					'heading' => esc_html__( 'Hover Animation', 'total-theme-core'),
					'param_name' => 'hover_animation',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_overlay',
					'heading' => esc_html__( 'Image Overlay', 'total-theme-core' ),
					'param_name' => 'overlay_style',
					'std' => 'none',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'exclude_choices' => array(
						'thumb-swap',
						'thumb-swap-title',
						'category-tag',
						'category-tag-two'
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Overlay Excerpt Length', 'total-theme-core' ),
					'param_name' => 'overlay_excerpt_length',
					'value' => '15',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'overlay_style', 'value' => 'title-excerpt-hover' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Overlay Button Text', 'total-theme-core' ),
					'param_name' => 'overlay_button_text',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'overlay_style', 'value' => 'hover-button' ),
				),
				array(
					'type' => 'vcex_image_hovers',
					'heading' => esc_html__( 'CSS3 Image Hover', 'total-theme-core' ),
					'param_name' => 'img_hover_style',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'hover_animation', 'is_empty' => true ),
				),
				// Image Size.
				array(
					'type' => 'vcex_image_sizes',
					'heading' => esc_html__( 'Image Size', 'total-theme-core' ),
					'param_name' => 'img_size',
					'std' => 'wpex_custom',
					'group' => esc_html__( 'Size', 'total-theme-core' ),
					'description' => esc_html__( 'Note: For security reasons custom cropping only works on images hosted on your own server in the WordPress uploads folder. If you are using an external image it will display in full.', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_image_crop_locations',
					'heading' => esc_html__( 'Image Crop Location', 'total-theme-core' ),
					'param_name' => 'img_crop',
					'group' => esc_html__( 'Size', 'total-theme-core' ),
					'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Image Crop Width', 'total-theme-core' ),
					'param_name' => 'img_width',
					'group' => esc_html__( 'Size', 'total-theme-core' ),
					'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Image Crop Height', 'total-theme-core' ),
					'param_name' => 'img_height',
					'description' => esc_html__( 'Leave empty to disable vertical cropping and keep image proportions.', 'total-theme-core' ),
					'group' => esc_html__( 'Size', 'total-theme-core' ),
					'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
				),
				// Link
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'On click action', 'total-theme-core' ),
					'param_name' => 'onclick',
					'value' => array(
						esc_html__( 'None', 'total-theme-core' ) => '',
						esc_html__( 'Go to custom link', 'total-theme-core' ) => 'custom_link',
						esc_html__( 'Go to internal page', 'total-theme-core' ) => 'internal_link',
						esc_html__( 'Scroll to section', 'total-theme-core' ) => 'local_scroll',
						esc_html__( 'Toggle Element', 'total-theme-core' ) => 'toggle_element',
						esc_html__( 'Go to custom field value', 'total-theme-core' ) => 'custom_field',
						esc_html__( 'Go to callback function value', 'total-theme-core' ) => 'callback_function',
						esc_html__( 'Open inline content or iframe popup', 'total-theme-core' ) => 'popup',
						esc_html__( 'Open image lightbox', 'total-theme-core' ) => 'lightbox_image',
						esc_html__( 'Open image gallery lightbox', 'total-theme-core' ) => 'lightbox_gallery',
						esc_html__( 'Open video lightbox', 'total-theme-core' ) => 'lightbox_video',
						esc_html__( 'Open post image gallery lightbox', 'total-theme-core' ) => 'lightbox_post_gallery',
						esc_html__( 'Open post video lightbox', 'total-theme-core' ) => 'lightbox_post_video',
					),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Link', 'total-theme-core' ),
					'param_name' => 'onclick_url',
					'description' => vcex_shortcode_param_description( 'text' ),
					'dependency' => array(
						'element' => 'onclick',
						'value' => array(
							'custom_link',
							'local_scroll',
							'popup',
							'lightbox_image',
							'lightbox_video',
							'toggle_element',
						),
					),
					'description' => esc_html__( 'Enter your custom link url, lightbox url or local/toggle element ID (including a # at the front).', 'total-theme-core' ),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'vc_link',
					'heading' => esc_html__( 'Internal Link', 'total-theme-core' ),
					'param_name' => 'onclick_internal_link',
					'group' => esc_html__( 'Link', 'total-theme-core' ),
					'description' => esc_html__( 'This setting is used only if you want to link to an internal page to make it easier to find and select it. Any extra settings in the popup (title, target, nofollow) are ignored.', 'total-theme-core' ),
					'dependency' => array( 'element' => 'onclick', 'value' => 'internal_link' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Custom Field ID', 'total-theme-core' ),
					'param_name' => 'onclick_custom_field',
					'dependency' => array( 'element' => 'onclick', 'value' => 'custom_field' ),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Callback Function', 'total-theme-core' ),
					'param_name' => 'onclick_callback_function',
					'dependency' => array( 'element' => 'onclick', 'value' => 'callback_function' ),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__( 'Lightbox Image', 'total-theme-core' ),
					'param_name' => 'onclick_lightbox_image',
					'dependency' => array( 'element' => 'onclick', 'value' => 'lightbox_image' ),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'attach_images',
					'heading' => esc_html__( 'Lightbox Gallery', 'total-theme-core' ),
					'param_name' => 'onclick_lightbox_gallery',
					'group' => esc_html__( 'Link', 'total-theme-core' ),
					'dependency' => array( 'element' => 'onclick', 'value' => 'lightbox_gallery' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title Attribute', 'total-theme-core' ),
					'param_name' => 'onclick_title',
					'group' => esc_html__( 'Link', 'total-theme-core' ),
					'dependency' => array( 'element' => 'onclick', 'not_empty' => true ),
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Target', 'total-theme-core' ),
					'param_name' => 'onclick_target',
					'std' => 'self',
					'choices' => array(
						'self'   => esc_html__( 'Self', 'total-theme-core' ),
						'_blank' => esc_html__( 'Blank', 'total-theme-core' ),
					),
					'dependency' => array(
						'element' => 'onclick',
						'value' => array( 'custom_link', 'internal_link', 'custom_field', 'callback_function' ),
					),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Rel Attribute', 'total-theme-core' ),
					'param_name' => 'onclick_rel',
					'std' => '',
					'choices' => array(
						'' => esc_html__( 'None', 'total-theme-core' ),
						'nofollow' => esc_html__( 'Nofollow', 'total-theme-core' ),
						'sponsored' => esc_html__( 'Sponsored', 'total-theme-core' ),
					),
					'dependency' => array(
						'element' => 'onclick',
						'value' => array( 'custom_link', 'internal_link', 'custom_field', 'callback_function' ),
					),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Video Overlay Icon?', 'total-theme-core' ),
					'param_name' => 'onclick_video_overlay_icon',
					'group' => esc_html__( 'Link', 'total-theme-core' ),
					'std' => 'false',
					'description' => esc_html__( 'More options available under Style > Image Overlay.', 'total-theme-core' ),
					'dependency' => array( 'element' => 'onclick', 'value' => 'lightbox_video' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Lightbox Dimensions (optional)', 'total-theme-core' ),
					'param_name' => 'onclick_lightbox_dims',
					'description' => esc_html__( 'Enter a custom width and height for your lightbox pop-up window. Use format widthxheight. Example: 1920x1080.', 'total-theme-core' ),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
					'dependency' => array( 'element' => 'onclick', 'value' => array( 'lightbox_video', 'popup' ) ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Lightbox Title', 'total-theme-core' ),
					'param_name' => 'onclick_lightbox_title',
					'dependency' => array(
						'element' => 'onclick',
						'value' => array( 'lightbox_image', 'lightbox_video', 'popup' )
					),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'textarea',
					'heading' => esc_html__( 'Lightbox Caption', 'total-theme-core' ),
					'param_name' => 'onclick_lightbox_caption',
					'dependency' => array(
						'element' => 'onclick',
						'value' => array( 'lightbox_image', 'lightbox_video', 'popup' )
					),
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'exploded_textarea',
					'heading' => esc_html__( 'Custom Data Attributes', 'total-theme-core' ),
					'param_name' => 'onclick_data_attributes',
					'group' => esc_html__( 'Link', 'total-theme-core' ),
					'dependency' => array(
						'element' => 'onclick',
						'value' => array(
							'custom_link',
							'custom_field',
							'callback_function',
							'popup',
							'toggle_element',
						),
					),
					'description' => esc_html__( 'Enter your custom data attributes in the format of data|value. Hit enter after each set of data attributes.', 'total-theme-core' ),
				),
				// CSS
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'total-theme-core' ),
					'param_name' => 'css',
					'group' => esc_html__( 'CSS', 'total-theme-core' ),
				),
				// Deprecated params.
				array( 'type' => 'hidden', 'param_name' => 'link' ),                        // @since v5.1
				array( 'type' => 'hidden', 'param_name' => 'link_local_scroll' ),           // @since v5.1
				array( 'type' => 'hidden', 'param_name' => 'lightbox' ),                    // @since v5.1
				array( 'type' => 'hidden', 'param_name' => 'lightbox_post_gallery' ),       // @since v5.1
				array( 'type' => 'hidden', 'param_name' => 'lightbox_url' ),                // @since v5.1
				array( 'type' => 'hidden', 'param_name' => 'lightbox_type' ),               // @since v5.1
				array( 'type' => 'hidden', 'param_name' => 'lightbox_dimensions' ),         // @since v5.1
				array( 'type' => 'hidden', 'param_name' => 'lightbox_custom_img' ),         // @since v5.1
				array( 'type' => 'hidden', 'param_name' => 'lightbox_title' ),              // @since v5.1
				array( 'type' => 'hidden', 'param_name' => 'lightbox_gallery' ),            // @since v5.1
				array( 'type' => 'hidden', 'param_name' => 'lightbox_caption' ),            // @since v5.1
				array( 'type' => 'hidden', 'param_name' => 'lightbox_video_overlay_icon' ), // @since v5.1
			);

			/**
			 * Filters the vcex_image shortcode params.
			 *
			 * @param array $params
			 */
			$params = (array) apply_filters( 'vcex_shortcode_params', $params, 'vcex_image' );

			return $params;
		}

		/**
		 * Parses deprecated params.
		 */
		public static function parse_deprecated_attributes( $atts = '' ) {
			if ( empty( $atts ) || ! is_array( $atts ) ) {
				return $atts;
			}

			// Convert to onclick method.
			if ( empty( $atts['onclick'] ) ) {

				if ( isset( $atts['lightbox'] ) && 'true' == $atts['lightbox' ] ) {

					if ( ! empty( $atts['lightbox_type'] ) ) {
						switch ( $atts['lightbox_type'] ) {
							case 'iframe':
							case 'url':
							case 'inline':
							case 'html5':
								$atts['onclick'] = 'popup';
								break;
							case 'video':
								$atts['onclick'] = 'lightbox_video';
								break;
							case 'image':
							default:
								$atts['onclick'] = 'lightbox_image';
								break;
						}
					} else {
						$atts['onclick'] = 'lightbox_image';
					}

					if ( isset( $atts['lightbox_post_gallery'] ) && 'true' === $atts['lightbox_post_gallery'] ) {
						$atts['onclick'] = 'lightbox_post_gallery';
						unset( $atts['lightbox_post_gallery'] );
					} elseif ( ! empty( $atts['lightbox_gallery'] ) ) {
						$atts['onclick'] = 'lightbox_gallery';
					} elseif ( ! empty( $atts['lightbox_custom_img'] ) ) {
						$atts['onclick'] = 'lightbox_image';
					}

					if ( ! empty( $atts['lightbox_url'] ) ) {
						if ( empty( $atts['onclick_url'] ) ) {
							$atts['onclick_url'] = $atts['lightbox_url'];
						}
						if ( empty( $atts['onclick'] ) ) {
							if ( false !== strpos( $atts['lightbox_url'], 'youtu' )
								|| false !== strpos( $atts['lightbox_url'], 'vimeo' )
							) {
								$atts['onclick'] = 'lightbox_video';
							} else {
								$atts['onclick'] = 'lightbox_image';
							}
						}
						unset( $atts['lightbox_url'] );
					}

					unset( $atts['lightbox'] );

				} else {

					if ( ! empty( $atts['link'] ) ) {

						$link = vcex_build_link( $atts['link'] );

						if ( ! empty( $link['url'] ) && empty( $atts['onclick_url'] ) ) {
							$atts['onclick_url'] = $link[ 'url' ];
						}

						if ( ! empty( $link['title'] ) ) {
							$atts['onclick_title'] = $link[ 'title' ];
						}

						if ( ! empty( $link['target'] ) ) {
							$atts['onclick_target'] = $link[ 'target' ];
						}

						if ( ! empty( $link['rel'] ) ) {
							$atts['onclick_rel'] = $link[ 'rel' ];
						}

						$atts['onclick'] = 'custom_link';

						unset( $atts['link'] );

					}
					if ( isset( $atts['link_local_scroll'] ) && 'true' === $atts['link_local_scroll' ] ) {
						$atts['onclick'] = 'local_scroll';
						$atts['onclick_target'] = 'self';
					}

				}

			}

			if ( ! empty( $atts['lightbox_dimensions'] ) ) {
				$atts['onclick_lightbox_dims'] = $atts['lightbox_dimensions'];
				unset( $atts['lightbox_dimensions'] );
			}

			if ( ! empty( $atts['lightbox_custom_img'] ) ) {
				$atts['onclick_lightbox_image'] = $atts['lightbox_custom_img'];
				unset( $atts['lightbox_custom_img'] );
			}

			if ( ! empty( $atts['lightbox_title'] ) ) {
				$atts['onclick_lightbox_title'] = $atts['lightbox_title'];
				unset( $atts['lightbox_title'] );
			}

			if ( ! empty( $atts['lightbox_gallery'] ) ) {
				$atts['onclick_lightbox_gallery'] = $atts['lightbox_gallery'];
				unset( $atts['lightbox_gallery'] );
			}

			if ( ! empty( $atts['lightbox_caption'] ) ) {
				$atts['onclick_lightbox_caption'] = $atts['lightbox_caption'];
				unset( $atts['lightbox_caption'] );
			}

			if ( ! empty( $atts['lightbox_video_overlay_icon'] ) ) {
				$atts['onclick_video_overlay_icon'] = $atts['lightbox_video_overlay_icon'];
				unset( $atts['lightbox_video_overlay_icon'] );
			}

			return $atts;
		}

	}

}
new VCEX_Image_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Image' ) ) {
	class WPBakeryShortCode_Vcex_Image extends WPBakeryShortCode {}
}