<?php
/**
 * Spacing Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Spacing_Shortcode' ) ) {

	class VCEX_Spacing_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_spacing', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Spacing::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( vcex_get_shortcode_template( 'vcex_spacing' ) );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				array(
					'type' => 'textfield',
					'admin_label' => true,
					'heading' => esc_html__( 'Height', 'total-theme-core' ),
					'param_name' => 'size',
					'value' => '30px',
					'description' => vcex_shortcode_param_description( 'height' ),
					'dependency' => array( 'element' => 'responsive', 'value' => 'false' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Responsive?', 'total-theme-core' ),
					'param_name' => 'responsive',
					'value' => 'false',
				),
				array(
					'type' => 'vcex_responsive_sizes',
					'heading' => esc_html__( 'Height', 'total-theme-core' ),
					'param_name' => 'size_responsive',
					'value' => '30px',
					'description' => vcex_shortcode_param_description( 'height' ),
					'dependency' => array( 'element' => 'responsive', 'value' => 'true' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Custom Classes', 'total-theme-core' ),
					'param_name' => 'class',
				),
				array(
					'type' => 'vcex_visibility',
					'heading' => esc_html__( 'Visibility', 'total-theme-core' ),
					'param_name' => 'visibility',
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_spacing' );

		}

	}

}
new VCEX_Spacing_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Spacing' ) ) {
	class WPBakeryShortCode_Vcex_Spacing extends WPBakeryShortCode {}
}