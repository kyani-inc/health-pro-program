<?php
/**
 * Divider Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Divider_Shortcode' ) ) {

	class VCEX_Divider_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_divider', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Divider::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( vcex_get_shortcode_template( 'vcex_divider' ) );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				// General
				array(
					'type' => 'vcex_select_buttons',
					'admin_label' => true,
					'heading' => esc_html__( 'Type', 'total-theme-core' ),
					'param_name' => 'style',
					'std' => 'solid',
					'choices' => array(
						'solid' => esc_html__( 'Solid', 'total-theme-core' ),
						'dashed' => esc_html__( 'Dashed', 'total-theme-core' ),
						'double' => esc_html__( 'Double', 'total-theme-core' ),
						'dotted-line' => esc_html__( 'Dotted', 'total-theme-core' ),
						'dotted' => esc_html__( 'Ben-Day', 'total-theme-core' ),
					),
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
					'heading' => esc_html__( 'Spacing', 'total-theme-core' ),
					'param_name' => 'margin_y',
					'value' => vcex_margin_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Width', 'total-theme-core' ),
					'param_name' => 'width',
					'description' => vcex_shortcode_param_description( 'width' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Align', 'total-theme-core' ),
					'param_name' => 'align',
					'dependency' => array( 'element' => 'width', 'not_empty' => true ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Height', 'total-theme-core' ),
					'param_name' => 'height',
					'description' => vcex_shortcode_param_description( 'px' ),
					'dependency' => array(
						'element' => 'style',
						'value' => array( 'solid', 'dashed', 'double', 'dotted-line' ),
					),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Height', 'total-theme-core' ),
					'param_name' => 'dotted_height',
					'dependency' => array(
						'element' => 'style',
						'value' => 'dotted',
					),
					'description' => vcex_shortcode_param_description( 'px' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'color',
					'value' => '',
					'dependency' => array(
						'element' => 'style',
						'value' => array( 'solid', 'dashed', 'double', 'dotted-line' ),
					),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_trbl',
					'heading' => esc_html__( 'Margin', 'total-theme-core' ),
					'param_name' => 'margin',
					'description' => vcex_shortcode_param_description( 'margin' ),
					'dependency' => array( 'element' => 'margin_y', 'is_empty' => true ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				// Icon
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Icon library', 'total-theme-core' ),
					'param_name' => 'icon_type',
					'description' => esc_html__( 'Select icon library.', 'total-theme-core' ),
					'value' => array(
						esc_html__( 'Theme Icons', 'total-theme-core' ) => '',
						esc_html__( 'Font Awesome', 'total-theme-core' ) => 'fontawesome',
						esc_html__( 'Open Iconic', 'total-theme-core' ) => 'openiconic',
						esc_html__( 'Typicons', 'total-theme-core' ) => 'typicons',
						esc_html__( 'Entypo', 'total-theme-core' ) => 'entypo',
						esc_html__( 'Linecons', 'total-theme-core' ) => 'linecons',
						esc_html__( 'Pixel', 'total-theme-core' ) => 'pixelicons',
					),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon',
					'settings' => array( 'emptyIcon' => true, 'type' => 'ticons', 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'is_empty' => true ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_fontawesome',
					'settings' => array( 'emptyIcon' => true, 'type' => 'fontawesome', 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'fontawesome' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_openiconic',
					'settings' => array( 'emptyIcon' => true, 'type' => 'openiconic', 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'openiconic' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_typicons',
					'settings' => array( 'emptyIcon' => true, 'type' => 'typicons', 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_entypo',
					'settings' => array( 'emptyIcon' => true, 'type' => 'entypo', 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'entypo' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_linecons',
					'settings' => array( 'emptyIcon' => true, 'type' => 'linecons', 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'linecons' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_pixelicons',
					'settings' => array( 'emptyIcon' => true, 'type' => 'pixelicons', 'source' => vcex_pixel_icons() ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'pixelicons' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Icon Color', 'total-theme-core' ),
					'param_name' => 'icon_color',
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Icon Background', 'total-theme-core' ),
					'param_name' => 'icon_bg',
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Side Margin', 'total-theme-core' ),
					'param_name' => 'icon_spacing',
					'value' => vcex_margin_choices(),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Icon Size', 'total-theme-core' ),
					'param_name' => 'icon_size',
					'description' => vcex_shortcode_param_description( 'icon_size' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Icon Height', 'total-theme-core' ),
					'param_name' => 'icon_height',
					'description' => vcex_shortcode_param_description( 'height' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Icon Width', 'total-theme-core' ),
					'param_name' => 'icon_width',
					'description' => vcex_shortcode_param_description( 'width' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Icon Border Radius', 'total-theme-core' ),
					'param_name' => 'icon_border_radius',
					'description' => vcex_shortcode_param_description( 'border_radius' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_trbl',
					'heading' => esc_html__( 'Icon Padding', 'total-theme-core' ),
					'param_name' => 'icon_padding',
					'description' => vcex_shortcode_param_description( 'padding' ),
					'group' => esc_html__( 'Icon', 'total-theme-core' ),
				),
				// Hidden Removed attributes
				array( 'type' => 'hidden', 'param_name' => 'margin_top' ),
				array( 'type' => 'hidden', 'param_name' => 'margin_bottom' ),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_divider' );

		}

		/**
		 * Parses deprecated params.
		 */
		public static function parse_deprecated_attributes( $atts = '' ) {

			if ( empty( $atts ) || ! is_array( $atts ) ) {
				return $atts;
			}

			if ( empty( $atts['margin'] ) ) {
				$margin_top = isset( $atts['margin_top'] ) ?  $atts['margin_top'] : '';
				$margin_bottom = isset( $atts['margin_bottom'] ) ?  $atts['margin_bottom'] : '';
				if ( $margin_top || $margin_bottom ) {
					$atts['margin'] = vcex_combine_trbl_fields( $margin_top, '', $margin_bottom, '' );
				}
				unset( $atts['margin_top'] );
				unset( $atts['margin_bottom'] );
			}

			return $atts;

		}

	}

}
new VCEX_Divider_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Divider' ) ) {
	class WPBakeryShortCode_Vcex_Divider extends WPBakeryShortCode {}
}