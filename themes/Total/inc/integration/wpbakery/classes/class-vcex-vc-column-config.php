<?php
use TotalTheme\Integration\WPBakery\Deprecated_CSS_Params_Style;

defined( 'ABSPATH' ) || exit;

/**
 * WPBakery Column Tweaks.
 *
 * @package TotalTheme
 * @subpackage WPBakery
 * @version 5.3
 */
if ( ! class_exists( 'VCEX_VC_Column_Config' ) ) {

	class VCEX_VC_Column_Config {

		/**
		 * Main constructor.
		 *
		 * @since 2.0.0
		 */
		public function __construct() {
			add_action( 'vc_after_init', __CLASS__ . '::add_params', 40 ); // add params first
			add_action( 'vc_after_init', __CLASS__ . '::modify_params', 40 ); // priority is crucial.
			add_filter( 'vc_edit_form_fields_attributes_vc_column', __CLASS__ . '::edit_form_fields' );
			add_filter( 'vc_edit_form_fields_attributes_vc_column_inner', __CLASS__ . '::edit_form_fields' );
			add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, __CLASS__ . '::shortcode_classes', 9999, 3 );
			add_filter( 'shortcode_atts_vc_column', __CLASS__ . '::shortcode_atts' );
			add_filter( 'vc_shortcode_output', __CLASS__ . '::custom_output', 10, 4 );
		}

		/**
		 * Used to update default parms.
		 *
		 * @since 4.3
		 */
		public static function modify_params() {

			if ( ! function_exists( 'vc_update_shortcode_param' ) ) {
				return;
			}

			// Modify el_id.
			$param = \WPBMap::getParam( 'vc_column', 'el_id' );
			if ( $param ) {
				$param['weight'] = 99;
				vc_update_shortcode_param( 'vc_column', $param );
			}

			// Modify el_class.
			$param = \WPBMap::getParam( 'vc_column', 'el_class' );
			if ( $param ) {
				$param['weight'] = 99;
				vc_update_shortcode_param( 'vc_column', $param );
			}

			// Modify css_animation.
			$param = \WPBMap::getParam( 'vc_column', 'css_animation' );
			if ( $param ) {
				$param['weight'] = 99;
				vc_update_shortcode_param( 'vc_column', $param );
			}

			// Modify video_bg.
			$param = \WPBMap::getParam( 'vc_column', 'video_bg' );
			if ( $param ) {
				$param['group'] = esc_html__( 'Video', 'total' );
				vc_update_shortcode_param( 'vc_column', $param );
			}

			// Modify video_bg_parallax.
			$param = \WPBMap::getParam( 'vc_column', 'video_bg_parallax' );
			if ( $param ) {
				$param['group'] = esc_html__( 'Video', 'total' );
				vc_update_shortcode_param( 'vc_column', $param );
			}

			// Modify video_bg_url.
			$param = \WPBMap::getParam( 'vc_column', 'video_bg_url' );
			if ( $param ) {
				$param['group'] = esc_html__( 'Video', 'total' );
				vc_update_shortcode_param( 'vc_column', $param );
			}

			// Modify parallax_speed_video.
			$param = \WPBMap::getParam( 'vc_column', 'parallax_speed_video' );
			if ( $param ) {
				$param['group'] = esc_html__( 'Video', 'total' );
				vc_update_shortcode_param( 'vc_column', $param );
			}

			// Modify parallax.
			$param = \WPBMap::getParam( 'vc_column', 'parallax' );
			if ( $param ) {
				$param['group'] = esc_html__( 'Parallax', 'total' );
				vc_update_shortcode_param( 'vc_column', $param );
			}

			// Modify parallax_image.
			$param = \WPBMap::getParam( 'vc_column', 'parallax_image' );
			if ( $param ) {
				$param['group'] = esc_html__( 'Parallax', 'total' );
				vc_update_shortcode_param( 'vc_column', $param );
			}

			// Modify parallax_speed_bg.
			$param = \WPBMap::getParam( 'vc_column', 'parallax_speed_bg' );
			if ( $param ) {
				$param['group'] = esc_html__( 'Parallax', 'total' );
				$param['dependency'] = array(
					'element' => 'parallax',
					'value' => array( 'content-moving', 'content-moving-fade' ),
				);
				vc_update_shortcode_param( 'vc_column', $param );
			}

			// Modify width.
			$param = \WPBMap::getParam( 'vc_column', 'width' );
			if ( $param ) {
				$param['weight'] = -1;
				vc_update_shortcode_param( 'vc_column', $param );
			}

		}

		/**
		 * Adds new params for the VC Rows.
		 *
		 * @since 2.0.0
		 */
		public static function add_params() {

			if ( ! function_exists( 'vc_add_params' ) ) {
				return;
			}

			/*-----------------------------------------------------------------------------------*/
			/*  - Columns
			/*-----------------------------------------------------------------------------------*/

			// Array of params to add
			$column_params = array();

			$column_params[] = array(
				'type'       => 'vcex_visibility',
				'heading'    => esc_html__( 'Visibility', 'total' ),
				'param_name' => 'visibility',
				'std'        => '',
				'weight'     => 99,
			);

			$column_params[] = array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Animation Duration', 'total'),
				'param_name' => 'animation_duration',
				'description' => esc_html__( 'Enter your custom time in seconds (decimals allowed).', 'total'),
			);

			$column_params[] = array(
				'type' => 'textfield',
				'heading' => esc_html__( 'CSS Animation Delay', 'total'),
				'param_name' => 'css_animation_delay', // @todo rename to just animation_delay
				'description' => esc_html__( 'Enter your custom time in seconds (decimals allowed).', 'total'),
			);

			$column_params[] = array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Minimum Height', 'total' ),
				'param_name'  => 'min_height',
				'description' => esc_html__( 'You can enter a minimum height for this row.', 'total' ),
			);

			if ( function_exists( 'vcex_shadow_choices' ) ) {
				$column_params[] = array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Shadow', 'total' ),
					'param_name' => 'wpex_shadow',
					'value' => vcex_shadow_choices(),
				);
			}

			$column_params[] = array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Typography Style', 'total' ),
				'param_name'  => 'typography_style',
				'value'       => array_flip( wpex_typography_styles() ),
				'description' => esc_html__( 'Will alter the font colors of all child elements. This is an older setting that is somewhat deprecated.', 'total' ),
			);

			/* Design Options */
			$column_params[] = array(
				'type' => 'vcex_ofswitch',
				'heading' => esc_html__( 'Use Featured Image as Background?', 'total' ),
				'param_name' => 'wpex_featured_bg_image',
				'std' => 'false',
				'description' => esc_html__( 'Enable this option to use the current post featured image as the row background.', 'total' ),
				'group' => esc_html__( 'Design Options', 'total' ),
				'weight' => -2,
			);

			$column_params[] = array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Fixed Background Style', 'total' ),
				'param_name' => 'wpex_fixed_bg',
				'group' => esc_html__( 'Design Options', 'total' ),
				'weight' => -2,
				'dependency' => array( 'element' => 'parallax', 'is_empty' => true ),
				'value' => array(
					esc_html__( 'None', 'total' ) => '',
					esc_html__( 'Fixed', 'total' ) => 'fixed',
					esc_html__( 'Fixed top', 'total' ) => 'fixed-top',
					esc_html__( 'Fixed bottom', 'total' ) => 'fixed-bottom',
				),
			);

			$column_params[] = array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Background Position', 'total' ),
				'param_name' => 'wpex_bg_position',
				'group' => esc_html__( 'Design Options', 'total' ),
				'description' => esc_html__( 'Enter your custom background position. Example: "center center"', 'total' ),
				'weight' => -2,
				'dependency' => array( 'element' => 'parallax', 'is_empty' => true ),
			);

			$column_params[] = array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Background Image Size', 'total' ),
				'param_name' => 'wpex_bg_size',
				'group' => esc_html__( 'Design Options', 'total' ),
				'description' => esc_html__( 'Specify the size of the background image. Example: 100% 100% ', 'total' ),
				'weight' => -2,
				'dependency' => array( 'element' => 'parallax', 'is_empty' => true ),
			);

			$column_params[] = array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Z-Index', 'total' ),
				'param_name' => 'wpex_zindex',
				'group' => esc_html__( 'Design Options', 'total' ),
				'description' => esc_html__( 'Note: Adding z-index values on rows containing negative top/bottom margins will allow you to overlay the rows, however, this can make it hard to access the page builder tools in the frontend editor and you may need to use the backend editor to modify the overlapped rows.', 'total' ),
				'weight' => -2,
				'dependency' => array( 'element' => 'parallax', 'is_empty' => true ),
			);

			// Hidden fields = Deprecated params, these should be removed on save.
			$deprecated_column_params = array(
				'id',
				'style',
				'typo_style',
				'bg_style',
				'drop_shadow',
			);

			if ( wpex_vc_maybe_parse_deprecated_css_options( 'vc_column' ) ) {
				$deprecated_column_params = array_merge( $deprecated_column_params, array(
					'bg_color',
					'bg_image',
					'border_style',
					'border_color',
					'border_width',
					'margin_top',
					'margin_bottom',
					'margin_left',
					'padding_top',
					'padding_bottom',
					'padding_left',
					'padding_right',
				) );
			}

			foreach ( $deprecated_column_params as $param ) {

				$column_params[] = array(
					'type'       => 'hidden',
					'param_name' => $param,
				);

			}

			vc_add_params( 'vc_column', $column_params );

			/*-----------------------------------------------------------------------------------*/
			/*  - Inner Columns
			/*-----------------------------------------------------------------------------------*/
			$inner_column_params = array();

			// Hidden fields = Deprecated params, these should be removed on save
			$deprecated_params = array(
				'id',
				'style',
				'bg_style',
				'typo_style',
			);

			if ( wpex_vc_maybe_parse_deprecated_css_options( 'vc_column_inner' ) ) {
				$deprecated_params = array_merge( $deprecated_params, array(
					'bg_color',
					'bg_image',
					'border_style',
					'border_color',
					'border_width',
					'margin_top',
					'margin_bottom',
					'margin_left',
					'padding_top',
					'padding_bottom',
					'padding_left',
					'padding_right'
				) );
			}

			foreach ( $deprecated_params as $param ) {

				$inner_column_params[] = array(
					'type'       => 'hidden',
					'param_name' => $param,
				);

			}

			vc_add_params( 'vc_column_inner', $inner_column_params );

		}

		/**
		 * Tweaks attributes on edit.
		 *
		 * @since 3.0.0
		 */
		public static function edit_form_fields( $atts ) {

			// Parse ID
			if ( empty( $atts['el_id'] ) && ! empty( $atts['id'] ) ) {
				$atts['el_id'] = $atts['id'];
				unset( $atts['id'] );
			}

			// Parse $atts['typo_style'] into $atts['typography_style']
			if ( empty( $atts['typography_style'] ) && ! empty( $atts['typo_style'] ) ) {
				if ( in_array( $atts['typo_style'], array_flip( wpex_typography_styles() ) ) ) {
					$atts['typography_style'] = $atts['typo_style'];
					unset( $atts['typo_style'] );
				}
			}

			// Remove old style param and add it to the classes field
			$style = isset( $atts['style'] ) ? $atts['style'] : '';
			if ( $style && ( 'bordered' == $style || 'boxed' == $style ) ) {
				if ( ! empty( $atts['el_class'] ) ) {
					$atts['el_class'] .= ' ' . $style . '-column';
				} else {
					$atts['el_class'] = $style . '-column';
				}
				unset( $atts['style'] );
			}

			// Parse css
			if ( empty( $atts['css'] ) && wpex_vc_maybe_parse_deprecated_css_options( 'vc_column' ) ) {

				// Convert deprecated fields to css field.
				$atts['css'] = Deprecated_CSS_Params_Style::generate_css( $atts );

				// Unset deprecated vars.
				unset( $atts['bg_image'] );
				unset( $atts['bg_color'] );

				unset( $atts['margin_top'] );
				unset( $atts['margin_bottom'] );
				unset( $atts['margin_right'] );
				unset( $atts['margin_left'] );

				unset( $atts['padding_top'] );
				unset( $atts['padding_bottom'] );
				unset( $atts['padding_right'] );
				unset( $atts['padding_left'] );

				unset( $atts['border_width'] );
				unset( $atts['border_style'] );
				unset( $atts['border_color'] );

			}

			// Return $atts
			return $atts;

		}

		/**
		 * Tweak shortcode classes.
		 *
		 * @since 4.0
		 */
		public static function shortcode_classes( $class_string, $tag, $atts ) {

			// Edits only for columns.
			if ( 'vc_column' !== $tag && 'vc_column_inner' !== $tag ) {
				return $class_string;
			}

			// Move 'vc_column_container' to the front.
			$class_string = str_replace( 'wpb_column', '', $class_string );
			$class_string = 'wpb_column ' . trim( $class_string );

			// Remove colorfill class which VC adds extra margins to.
			$class_string = str_replace( 'vc_col-has-fill', 'wpex-vc_col-has-fill', $class_string );

			// Visibility.
			if ( ! empty( $atts['visibility'] ) ) {
				$class_string .= ' ' . wpex_visibility_class( $atts['visibility'] );
			}

			// Style => deprecated fallback.
			if ( ! empty( $atts['style'] ) && 'default' != $atts['style'] ) {
				$class_string .= ' ' . $atts['style'] . '-column';
			}

			// Typography Style => deprecated fallback.
			if ( ! empty( $atts['typo_style'] ) && empty( $atts['typography_style'] ) ) {
				$class_string .= ' ' . wpex_typography_style_class( $atts['typo_style'] );
			} elseif ( empty( $atts['typo_style'] ) && ! empty( $atts['typography_style'] ) ) {
				$class_string .= ' ' . wpex_typography_style_class( $atts['typography_style'] );
			}

			// Return class string.
			return $class_string;

		}

		/**
		 * Customize the column HTML output.
		 *
		 * @since 4.0
		 */
		public static function custom_output( $output, $obj, $atts, $shortcode ) {
			if ( 'vc_column' !== $shortcode ) {
				return $output;
			}

			/* Outer Column Edits */

				$outer_style = '';

				// Z-Index.
				if ( ! empty( $atts['wpex_zindex'] ) ) {
					$outer_style .= 'z-index:' . esc_attr( $atts['wpex_zindex'] ) . '!important;';
				}

				// Add animation delays and duration.
				if ( ! empty( $atts['animation_duration'] ) ) {
					$outer_style .= 'animation-duration:' . floatval( $atts['animation_duration'] ) . 's;';
				}
				if ( ! empty( $atts['css_animation_delay'] ) ) {
					$outer_style .= 'animation-delay:' . floatval( $atts['css_animation_delay'] ) . 's;';
				}

				// Add outer inline style.
				if ( $outer_style ) {
					$outer_style = 'style="' . esc_attr( $outer_style ) . '"';
					$output = str_replace( 'class="wpb_column', $outer_style . ' class="wpb_column', $output );
				}

			/* Inner Column Edits */

				// Fix empty space after vc_column-inner classname.
				// @todo Remove when WPBakery fixes.
				$output = str_replace( 'class="vc_column-inner "', 'class="vc_column-inner"', $output );

				// Generate inline CSS.
				$inner_style = '';

				// Min Height.
				if ( ! empty( $atts['min_height'] ) ) {
					$min_height = $atts['min_height'];
					if ( ! preg_match('/[A-z]/', $min_height ) && strpos( $min_height, '%' ) === false ) {
						$min_height = intval( $min_height ) . 'px';
					}
					$inner_style .= 'min-height:' . $min_height . ';';
				}

				// Inline css styles => Fallback For OLD Total Params - @deprecated in 4.9.
				if ( empty( $atts['css'] ) && wpex_vc_maybe_parse_deprecated_css_options( 'vc_column' ) ) {
					$inner_style .= Deprecated_CSS_Params_Style::generate_css( $atts, 'inline_css' );
				}

				// Custom background.
				if ( array_key_exists( 'wpex_featured_bg_image', $atts )
					&& wpex_validate_boolean( $atts['wpex_featured_bg_image'] )
					&& empty( $atts['parallax'] )
				) {

					$thumbnail_id = '';

					$post_id = wpex_get_dynamic_post_id();

					if ( $post_id ) {
						$thumbnail_id = get_post_thumbnail_id( $post_id );
					} else {
						$thumbnail_id = wpex_get_term_thumbnail_id();
					}

					if ( $thumbnail_id && 0 !== $thumbnail_id ) {

						$inner_style .= 'background-image:url(' . esc_url( wp_get_attachment_image_url( $thumbnail_id, 'full' ) ) . ')';

						if ( apply_filters( 'wpex_vc_column_featured_bg_image_has_important', true ) ) {
							$inner_style .= '!important';
						}

						$inner_style .= ';';

					}

				}

				// Background position.
				if ( ! empty( $atts['wpex_bg_position'] ) ) {
					$inner_style .= 'background-position:' . $atts['wpex_bg_position'] . ' !important;';
				}

				// Background size.
				if ( ! empty( $atts['wpex_bg_size'] ) ) {
					$inner_style .= 'background-size:' . $atts['wpex_bg_size'] . ' !important;';
				}

				// Add inner inline style.
				if ( $inner_style ) {
					$inner_style = 'style="' . esc_attr( $inner_style ) . '"';
					$output = str_replace( 'class="vc_column-inner', $inner_style . ' class="vc_column-inner', $output );
				}

				// Add Fixed background classname.
				if ( ! empty( $atts['wpex_fixed_bg'] ) ) {
					$output = str_replace( 'class="vc_column-inner', 'class="vc_column-inner bg-' . sanitize_html_class( $atts['wpex_fixed_bg'] ), $output );
				}

				// Custom Shadow classname
				if ( ! empty( $atts['wpex_shadow'] ) ) {
					$output = str_replace( 'class="vc_column-inner', 'class="vc_column-inner wpex-' . sanitize_html_class( $atts['wpex_shadow'] ), $output );
				}

			// Return output.
			return $output;

		}

		/**
		 * Parse column atts.
		 *
		 * @since 5.1
		 */
		public static function shortcode_atts( $atts ) {

			// Set parallax image equal to featured image.
			if ( ! empty( $atts['parallax'] )
				&& isset( $atts['wpex_featured_bg_image'] )
				&& wpex_validate_boolean( $atts['wpex_featured_bg_image'] )
				&& has_post_thumbnail()
			) {
				$atts['parallax_image'] = get_post_thumbnail_id();
			}

			// Return column atts.
			return $atts;

		}

	}

}
new VCEX_VC_Column_Config();