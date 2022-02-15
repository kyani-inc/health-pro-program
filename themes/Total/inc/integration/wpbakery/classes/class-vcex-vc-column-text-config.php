<?php
defined( 'ABSPATH' ) || exit;

/**
 * WPBakery Text Block Configuration.
 *
 * @package TotalTheme
 * @subpackage WPBakery
 * @version 1.3.2
 *
 * @todo rename to WPBakery_Column_Text_Config and add namespace
 */
if ( ! class_exists( 'VCEX_VC_Column_Text_Config' ) ) {

	final class VCEX_VC_Column_Text_Config {

		/**
		 * Main constructor
		 *
		 * @since 3.6.0
		 */
		public function __construct() {
			add_action( 'vc_after_init', __CLASS__ . '::modify_params', 40 );
			add_action( 'init', __CLASS__ . '::add_params', 40 );
			add_filter( 'vc_shortcode_output', __CLASS__ . '::custom_output', 10, 4 );
		}

		/**
		 * Modify Params
		 *
		 * @since 4.5
		 */
		public static function modify_params() {

			if ( ! function_exists( 'vc_update_shortcode_param' ) ) {
				return;
			}

			// Modify css.
			$param = \WPBMap::getParam( 'vc_column_text', 'css' );
			if ( $param ) {
				$param['weight'] = -1;
				vc_update_shortcode_param( 'vc_column_text', $param );
			}

		}

		/**
		 * Adds new params for the VC Rows
		 *
		 * @since 3.6.0
		 * @todo update to use vc_add_params instead of vc_add_param.
		 */
		public static function add_params() {

			// Visibility
			vc_add_param( 'vc_column_text', array(
				'type' => 'vcex_visibility',
				'heading' => esc_html__( 'Visibility', 'total' ),
				'param_name' => 'visibility',
			) );

			// Align
			vc_add_param( 'vc_column_text', array(
				'type' => 'vcex_text_alignments',
				'heading' => esc_html__( 'Align', 'total' ),
				'param_name' => 'align',
				'dependency' => array( 'element' => 'width', 'not_empty' => true ),
			) );

			// Width
			vc_add_param( 'vc_column_text', array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Width', 'total' ),
				'param_name' => 'width',
				'description' => esc_html__( 'Enter a custom width instead of using breaks to slim down your content width. ', 'total' ),
			) );

			// Typography
			if ( function_exists( 'vcex_inline_style' ) ) {
				$typo_params = array(
					array(
						'type' => 'vcex_text_alignments',
						'heading' => esc_html__( 'Text Align', 'total' ),
						'param_name' => 'text_align',
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => esc_html__( 'Font Family', 'total' ),
						'param_name' => 'font_family',
					),
					array(
						'type' => 'vcex_colorpicker',
						'heading' => esc_html__( 'Color', 'total' ),
						'param_name' => 'color',
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'heading' => esc_html__( 'Font Size', 'total' ),
						'param_name' => 'font_size',
						'target' => 'font-size',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => esc_html__( 'Auto Responsive Font Size', 'total' ),
						'param_name' => 'responsive_text',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Minimum Font Size', 'total' ),
						'param_name' => 'min_font_size',
						'dependency' => array( 'element' => 'responsive_text', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => esc_html__( 'Italic', 'total' ),
						'param_name' => 'italic',
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => esc_html__( 'Font Weight', 'total' ),
						'param_name' => 'font_weight',
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => esc_html__( 'Text Transform', 'total-theme-core' ),
						'param_name' => 'text_transform',
						'group' => esc_html__( 'Typography', 'total-theme-core' ),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Line Height', 'total' ),
						'param_name' => 'line_height',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Letter Spacing', 'total' ),
						'param_name' => 'letter_spacing',
					),
				);

				foreach ( $typo_params as $param ) {
					$param['group'] = esc_html__( 'Typography', 'total' );
					vc_add_param( 'vc_column_text', $param );
				}

			}

		}

		/**
		 * Add custom HTML to ouput
		 *
		 * @since 4.0
		 */
		public static function custom_output( $output, $obj, $atts, $shortcode ) {
			if ( 'vc_column_text' !== $shortcode ) {
				return $output;
			}

			$add_attrs = '';
			$add_classes = array();
			$inline_css = '';
			$unique_classname = function_exists( 'vcex_element_unique_classname' ) ? vcex_element_unique_classname() : '';

			// Inline style.
			if ( function_exists( 'vcex_inline_style' ) ) {
				$inline_style = vcex_inline_style( array(
					'color'          => isset( $atts['color'] ) ? $atts['color'] : '',
					'font_family'    => isset( $atts['font_family'] ) ? $atts['font_family'] : '',
					'font_size'      => isset( $atts['font_size'] ) ? $atts['font_size'] : '',
					'letter_spacing' => isset( $atts['letter_spacing'] ) ? $atts['letter_spacing'] : '',
					'font_weight'    => isset( $atts['font_weight'] ) ? $atts['font_weight'] : '',
					'text_align'     => isset( $atts['text_align'] ) ? $atts['text_align'] : '',
					'line_height'    => isset( $atts['line_height'] ) ? $atts['line_height'] : '',
					'width'          => isset( $atts['width'] ) ? $atts['width'] : '',
					'font_style'     => ( isset( $atts['italic'] ) && 'true' == $atts['italic'] ) ? 'italic' : '',
					'text_transform' => isset( $atts['text_transform'] ) ? $atts['text_transform'] : '',
				), false );
				if ( $inline_style ) {
					$add_attrs .= ' style="' . $inline_style . '"';
				}
			}

			// load custom fonts.
			if ( ! empty( $atts['font_family'] ) ) {
				wpex_enqueue_google_font( $atts['font_family'] );
			}

			// Auto Responsive text.
			if ( ! empty( $atts['responsive_text'] )
				&& 'true' == $atts['responsive_text']
				&& ! empty( $atts['font_size'] )
				&& ! empty( $atts['min_font_size'] )
			) {

				$font_size     = $atts['font_size'];
				$min_font_size = $atts['min_font_size'];

				// Convert em font size to pixels.
				if ( strpos( $atts['font_size'], 'em' ) !== false ) {
					$font_size = str_replace( 'em', '', $atts['font_size'] );
					$font_size = $font_size * wpex_get_body_font_size();
				}

				// Convert em min-font size to pixels.
				if ( strpos( $atts['min_font_size'], 'em' ) !== false ) {
					$min_font_size = str_replace( 'em', '', $atts['min_font_size'] );
					$min_font_size = $min_font_size * wpex_get_body_font_size();
				}

				// Add wrap classes and data.
				$add_attrs .= ' data-max-font-size="' . absint( $font_size ) . '"';
				$add_attrs .= ' data-min-font-size="' . absint( $min_font_size ) . '"';
				$add_classes[] = 'wpex-responsive-txt';

				// Enqueue scripts.
				wp_enqueue_script( 'vcex-responsive-text' );
			}

			// Responsive CSS.
			if ( $unique_classname && function_exists( 'vcex_element_responsive_css' ) ) {

				$el_responsive_styles = array(
					'font_size' => isset( $atts['font_size'] ) ? $atts['font_size'] : '',
				);

				$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

				if ( $responsive_css ) {
					$inline_css .= $responsive_css;
					$add_classes[] = $unique_classname;
				}

			} else if ( function_exists( 'vcex_get_module_responsive_data' )
				&& $responsive_data = vcex_get_module_responsive_data( $atts )
			) {
				$add_attrs .= ' ' . $responsive_data;
			}

			// Custom color.
			if ( ! empty( $atts['color'] ) ) {
				$custom_color_classes = 'has-custom-color wpex-child-inherit-color';
				$custom_color_classes = apply_filters( 'wpex_vc_column_text_custom_color_classes', $custom_color_classes );
				if ( $custom_color_classes && is_string( $custom_color_classes ) ) {
					$add_classes[] = $custom_color_classes;
				}
			}

			// Visibility.
			if ( ! empty( $atts['visibility'] ) ) {
				$add_classes[] = wpex_visibility_class( $atts['visibility'] );
			}

			// Align.
			if ( ! empty( $atts['width'] ) ) {
				$add_classes[] = 'wpex-max-w-100';
				if ( !  empty( $atts['align'] ) ) {
					$add_classes[] = 'align' . sanitize_html_class( $atts['align'] );
				} else {
					$add_classes[] = 'wpex-mx-auto';
				}
			}

			// Add classes.
			if ( $add_classes ) {
				$add_classes = implode( ' ', $add_classes );
				$output = str_replace( 'wpb_text_column', 'wpb_text_column ' . $add_classes, $output );
			}

			// Add custom attributes in first div.
			if ( $add_attrs ) {
				$pos = strpos( $output, '<div' );
				if ( $pos !== false ) {
					$output = substr_replace( $output, '<div ' . trim( $add_attrs ), $pos, strlen( '<div' ) );
				}
			}

			// Add inline CSS.
			if ( $inline_css ) {
				$inline_css = '<style>' . esc_attr( $inline_css ) . '</style>';
				$output = $inline_css . $output;
			}

			// Return output.
			return $output;

		}

	}

}
new VCEX_VC_Column_Text_Config();