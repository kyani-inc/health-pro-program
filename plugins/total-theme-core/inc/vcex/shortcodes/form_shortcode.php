<?php
/**
 * Form Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Form_Shortcode' ) ) {

	class VCEX_Form_Shortcode {

		/**
		 * Define shortcode name.
		 *
		 * Keep as fallback.
		 */
		public $shortcode = 'vcex_form_shortcode';

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_form_shortcode', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Form::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_form_shortcode', $atts );
			include( vcex_get_shortcode_template( 'vcex_form_shortcode' ) );
			do_action( 'vcex_shortcode_after', 'vcex_form_shortcode', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Shortcode', 'total' ),
					'param_name' => 'content',
					'admin_label' => true,
					'dependency' => array( 'element' => 'cf7_id', 'is_empty' => true ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'total-theme-core' ),
					'description' => vcex_shortcode_param_description( 'el_class' ),
					'param_name' => 'el_class',
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
					'heading' => esc_html__( 'Style', 'total' ),
					'param_name' => 'style',
					'std' => '',
					'value' => vcex_get_form_styles(),
					'description' => esc_html__( 'The theme will try and apply the necessary styles to your form (works best with Contact Form 7) but remember every contact form plugin has their own styles so additional tweaks may be required.', 'total' ),
					'group' => esc_html__( 'Style', 'total' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin',
					'value' => vcex_margin_choices(),
					'admin_label' => true,
					'group' => esc_html__( 'Style', 'total' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Full-Width Inputs', 'total' ),
					'param_name' => 'full_width',
					'group' => esc_html__( 'Style', 'total' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Text Align', 'total-theme-core' ),
					'param_name' => 'text_align',
					'value' => vcex_text_align_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Width', 'total' ),
					'param_name' => 'width',
					'description' => vcex_shortcode_param_description( 'width' ),
					'group' => esc_html__( 'Style', 'total' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Font Size', 'total' ),
					'param_name' => 'font_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Style', 'total' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Text Color', 'total' ),
					'param_name' => 'color',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background Color', 'total' ),
					'param_name' => 'background_color',
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
					'type' => 'dropdown',
					'heading' => esc_html__( 'Padding', 'total-theme-core' ),
					'param_name' => 'padding_all',
					'value' => vcex_padding_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Radius', 'total' ),
					'param_name' => 'border_radius',
					'value' => vcex_border_radius_choices(),
					'group' => esc_html__( 'Style', 'total' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Style', 'total-theme-core' ),
					'param_name' => 'border_style',
					'value' => vcex_border_style_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Width', 'total' ),
					'param_name' => 'border_width',
					'value' => vcex_border_width_choices(),
					'group' => esc_html__( 'Style', 'total' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Border Color', 'total' ),
					'param_name' => 'border_color',
					'group' => esc_html__( 'Style', 'total' ),
				),
				// CSS
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'total' ),
					'param_name' => 'css',
					'group' => esc_html__( 'CSS', 'total' ),
				),
			);

			$cf7 = vcex_select_cf7_form( array(
				'heading' => esc_html__( 'Contact Form 7 Select', 'total' ),
				'param_name' => 'cf7_id',
				'admin_label' => true,
			) );

			if ( $cf7 ) {
				array_unshift( $params, $cf7 );
			}

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_form_shortcode' );

		}

	}

}
new VCEX_Form_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Form_Shortcode' ) ) {
	class WPBakeryShortCode_Vcex_Form_Shortcode extends WPBakeryShortCode {}
}