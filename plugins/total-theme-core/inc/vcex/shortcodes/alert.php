<?php
/**
 * Alert Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Alert_Shortcode' ) ) {

	class VCEX_Alert_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {

			add_shortcode( 'vcex_alert', __CLASS__ . '::output' );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Alert::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_alert', $atts );
			include( vcex_get_shortcode_template( 'vcex_alert' ) );
			do_action( 'vcex_shortcode_after', 'vcex_alert', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Type', 'total-theme-core' ),
					'param_name' => 'type',
					'choices' => array(
						''        => esc_html__( 'Default', 'total-theme-core' ),
						'info'    => esc_html__( 'Info', 'total-theme-core' ),
						'success' => esc_html__( 'Success', 'total-theme-core' ),
						'warning' => esc_html__( 'Warning', 'total-theme-core' ),
						'error'   => esc_html__( 'Error', 'total-theme-core' ),
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Heading', 'total' ),
					'param_name' => 'heading',
					'description' => vcex_shortcode_param_description( 'text' ),
				),
				array(
					'type' => 'textarea_html',
					'heading' => esc_html__( 'Content', 'total-theme-core' ),
					'param_name' => 'content',
					'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce laoreet vestibulum elit eget fringilla.',
					'admin_label' => true,
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
					'param_name' => 'bottom_margin',
					'value' => vcex_margin_choices(),
					'admin_label' => true,
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Vertical Padding', 'total-theme-core' ),
					'param_name' => 'padding_y',
					'value' => vcex_padding_choices(),
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
					'heading' => esc_html__( 'Font Size', 'total' ),
					'param_name' => 'font_size',
					'value' => vcex_font_size_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_alert' );

		}

	}

}
new VCEX_Alert_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Alert' ) ) {
	class WPBakeryShortCode_Vcex_Alert extends WPBakeryShortCode {}
}