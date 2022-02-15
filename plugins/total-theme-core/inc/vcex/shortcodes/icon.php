<?php
/**
 * Icon Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Icon_Shortcode' ) ) {

	class VCEX_Icon_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_icon', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Icon::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_icon', $atts );
			include( vcex_get_shortcode_template( 'vcex_icon' ) );
			do_action( 'vcex_shortcode_after', 'vcex_icon', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
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
						esc_html__( 'Material', 'total-theme-core' ) => 'material',
						esc_html__( 'Mono Social', 'total-theme-core' ) => 'monosocial',
						esc_html__( 'Pixel (legacy)', 'total-theme-core' ) => 'pixelicons',
					),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon',
					'value' => 'ticon ticon-star-o',
					'settings' => array( 'emptyIcon' => true, 'type' => 'ticons', 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'is_empty' => true ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_fontawesome',
					'admin_label' => true,
					'value' => 'fa fa-info-circle',
					'settings' => array( 'emptyIcon' => true, 'type' => 'fontawesome', 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'fontawesome' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_openiconic',
					'std' => '',
					'settings' => array( 'emptyIcon' => true, 'type' => 'openiconic', 'iconsPerPage' => 100, ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'openiconic' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_typicons',
					'std' => '',
					'settings' => array( 'emptyIcon' => true, 'type' => 'typicons', 'iconsPerPage' => 100, ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_entypo',
					'std' => '',
					'settings' => array( 'emptyIcon' => true, 'type' => 'entypo', 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'entypo' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_linecons',
					'std' => '',
					'settings' => array( 'emptyIcon' => true, 'type' => 'linecons', 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'linecons' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_material',
					'std' => '',
					'settings' => array( 'emptyIcon' => true, 'type' => 'material', 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'material' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_pixelicons',
					'std' => '',
					'settings' => array( 'emptyIcon' => true, 'type' => 'pixelicons', 'source' => vcex_pixel_icons(), 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'pixelicons' ),
				),
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon', 'total-theme-core' ),
					'param_name' => 'icon_monosocial',
					'settings' => array( 'emptyIcon' => true, 'type' => 'monosocial', 'iconsPerPage' => 100 ),
					'dependency' => array( 'element' => 'icon_type', 'value' => 'monosocial' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Icon Font Alternative Classes', 'total-theme-core' ),
					'param_name' => 'icon_alternative_classes',
					'description' => esc_html__( 'If your are loading a custom icon font set on your site you can enter a custom classname(s) here.', 'total-theme-core' ),
				),
				// General
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Element ID', 'total-theme-core' ),
					'param_name' => 'unique_id',
					'admin_label' => true,
					'description' => vcex_shortcode_param_description( 'unique_id' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'total-theme-core' ),
					'param_name' => 'el_class',
					'description' => vcex_shortcode_param_description( 'el_class' ),
				),
				array(
					'type' => 'vcex_hover_animations',
					'heading' => esc_html__( 'Hover Animation', 'total-theme-core'),
					'param_name' => 'hover_animation',
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
					'param_name' => 'bottom_margin',
					'value' => vcex_margin_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'admin_label' => true,
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Icon Alignment', 'total-theme-core' ),
					'param_name' => 'align',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'float', 'is_empty' => true ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Float', 'total-theme-core' ),
					'param_name' => 'float',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'value' => array(
						esc_html__( 'None', 'total-theme-core' ) => '',
						esc_html__( 'Left', 'total-theme-core' ) => 'left',
						esc_html__( 'Center', 'total-theme-core' ) => 'center',
						esc_html__( 'Right', 'total-theme-core') => 'right',
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Size', 'total-theme-core' ),
					'param_name' => 'size',
					'std' => 'normal',
					'value' => array(
						esc_html__( 'Inherit', 'total-theme-core' ) => 'inherit',
						esc_html__( 'Tiny', 'total-theme-core' ) => 'tiny',
						esc_html__( 'Small', 'total-theme-core') => 'small',
						esc_html__( 'Normal', 'total-theme-core' ) => 'normal',
						esc_html__( 'Large', 'total-theme-core' ) => 'large',
						esc_html__( 'Extra Large', 'total-theme-core' ) => 'xlarge',
					),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Custom Size', 'total-theme-core' ),
					'param_name' => 'custom_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
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
					'type' => 'textfield',
					'heading' => esc_html__( 'Height', 'total-theme-core' ),
					'param_name' => 'height',
					'description' => vcex_shortcode_param_description( 'height' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'color',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color: Hover', 'total-theme-core' ),
					'param_name' => 'color_hover',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background', 'total-theme-core' ),
					'param_name' => 'background',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background: Hover', 'total-theme-core' ),
					'param_name' => 'background_hover',
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
					'type' => 'textfield',
					'heading' => esc_html__( 'Border', 'total-theme-core' ),
					'param_name' => 'border',
					'description' => esc_html__( 'Please use the shorthand format: width style color. Enter 0px or "none" to disable border.', 'total-theme-core' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				// Link
				array(
					'type' => 'vc_link',
					'heading' => esc_html__( 'Link', 'total-theme-core' ),
					'param_name' => 'link_url',
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Local Scroll', 'total-theme-core' ),
					'param_name' => 'link_local_scroll',
					'std' => 'false',
					'group' => esc_html__( 'Link', 'total-theme-core' ),
				),
				// Deprecated params
				array( 'type' => 'hidden', 'param_name' => 'style' ),
				array( 'type' => 'hidden', 'param_name' => 'padding' ),
				array( 'type' => 'hidden', 'param_name' => 'color_accent' ), // @since 1.2.8
				array( 'type' => 'hidden', 'param_name' => 'background_accent' ), // @since 1.2.8
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_icon' );

		}

		/**
		 * Parses deprecated params.
		 */
		public static function parse_deprecated_attributes( $atts = '' ) {

			if ( empty( $atts ) || ! is_array( $atts ) ) {
				return $atts;
			}

			// Convert link_url att to vcex_link format.
			if ( ! empty( $atts['link_url'] ) && false === strpos( $atts['link_url'], 'url:' ) ) {
				$url = 'url:'. rawurlencode( $atts['link_url'] ) .'|';
				$link_title = isset( $atts['link_title'] ) ? 'title:' . rawurlencode( $atts['link_title'] ) .'|' : '|';
				$link_target = ( isset( $atts['link_target'] ) && 'blank' === $atts['link_target'] ) ? 'target:_blank' : '';
				$atts['link_url'] = $url . $link_title . $link_target;
			}

			// Convert accent color on/off to set the accent color for color picker.
			if ( isset( $atts['color_accent'] ) && 'true' === $atts['color_accent'] ) {
				if ( empty( $atts['color'] ) ) {
					$atts['color'] = 'accent';
				}
				unset( $atts['color_accent'] );
			}

			if ( isset( $atts['background_accent'] ) && 'true' === $atts['background_accent'] ) {
				if ( empty( $atts['background'] ) ) {
					$atts['background'] = 'accent';
				}
				unset( $atts['background_accent'] );
			}

			// Update link target.
			if ( isset( $atts['link_target'] ) && 'local' === $atts['link_target'] ) {
				$atts['link_local_scroll'] = 'true';
				unset( $atts['link_target'] );
			}

			return $atts;

		}

	}

}
new VCEX_Icon_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Icon' ) ) {
	class WPBakeryShortCode_Vcex_Icon extends WPBakeryShortCode {}
}