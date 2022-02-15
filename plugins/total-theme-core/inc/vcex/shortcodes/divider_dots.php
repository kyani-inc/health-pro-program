<?php
/**
 * Divider Dots Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Divider_Dots_Shortcode' ) ) {

	class VCEX_Divider_Dots_Shortcode {

		/**
		 * Define shortcode name.
		 *
		 * Keep as fallback.
		 */
		public $shortcode = 'vcex_divider_dots';

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_divider_dots', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Divider_Dots::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( vcex_get_shortcode_template( 'vcex_divider_dots' ) );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				// General
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin',
					'value' => vcex_margin_choices(),
					'admin_label' => true,
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Align', 'total-theme-core' ),
					'param_name' => 'align',
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'color',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Count', 'total-theme-core' ),
					'param_name' => 'count',
					'value' => '3',
					'admin_label' => true,
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Spacing', 'total-theme-core' ),
					'param_name' => 'spacing',
					'value' => vcex_margin_choices(),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Size', 'total-theme-core' ),
					'param_name' => 'size',
					'description' => esc_html__( 'Default', 'total-theme-core' ) . ': 5px',
				),
				array(
					'type' => 'vcex_trbl',
					'heading' => esc_html__( 'Margin', 'total-theme-core' ),
					'description' => vcex_shortcode_param_description( 'padding' ),
					'param_name' => 'margin',
				),
				array(
					'type' => 'vcex_visibility',
					'heading' => esc_html__( 'Visibility', 'total-theme-core' ),
					'param_name' => 'visibility',
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
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'total-theme-core' ),
					'param_name' => 'el_class',
					'description' => vcex_shortcode_param_description( 'el_class' ),
				),
				// Hidden Removed attributes
				array( 'type' => 'hidden', 'param_name' => 'margin_top' ),
				array( 'type' => 'hidden', 'param_name' => 'margin_bottom' ),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_divider_dots' );

		}

		/**
		 * Parse attributes.
		 */
		public static function parse_deprecated_attributes( $atts ) {

			if ( empty( $atts ) || ! is_array( $atts ) ) {
				return $atts;
			}

			// Parse old margin settings.
			if ( empty( $atts['margin'] ) ) {
				$margin_top = isset( $atts['margin_top'] ) ?  $atts['margin_top'] : '';
				$margin_bottom = isset( $atts['margin_bottom'] ) ?  $atts['margin_bottom'] : '';
				if ( $margin_top || $margin_bottom ) {
					$atts['margin'] = vcex_combine_trbl_fields( $margin_top, '', $margin_bottom, '' );
					unset( $atts['margin_top'] );
					unset( $atts['margin_bottom'] );
				}
			}

			// Return attributes.
			return $atts;

		}

	}
}
new VCEX_Divider_Dots_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Divider_Dots' ) ) {
	class WPBakeryShortCode_Vcex_Divider_Dots extends WPBakeryShortCode {}
}