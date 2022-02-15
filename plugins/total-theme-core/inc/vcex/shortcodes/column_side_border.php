<?php
/**
 * Column Side Border Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Column_Side_Border_Shortcode' ) ) {

	class VCEX_Column_Side_Border_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_column_side_border', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Column_Side_Border::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( vcex_get_shortcode_template( 'vcex_column_side_border' ) );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				array(
					'type' => 'vcex_notice',
					'param_name' => 'editor_notice',
					'text' => esc_html__( 'Due to how the page builder works this module will display a placeholder in the front-end editor you will have to save and preview your live site to view the final result.', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Position', 'total-theme-core' ),
					'param_name' => 'position',
					'std' => 'right',
					'choices' => array(
						'left' => esc_html__( 'Left', 'total-theme-core' ),
						'right' => esc_html__( 'Right', 'total-theme-core' ) ,
					),
					'admin_label' => true,
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background Color', 'total-theme-core' ),
					'param_name' => 'background_color',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Custom Height', 'total-theme-core' ),
					'param_name' => 'height',
					'description' => esc_html__( 'Enter a custom px or % value. Default: 100%', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Custom Width', 'total-theme-core' ),
					'param_name' => 'width',
					'description' => esc_html__( 'Enter a custom px value. Default: 1px', 'total-theme-core' ),
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

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_column_side_border' );

		}

	}

}
new VCEX_Column_Side_Border_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Column_Side_Border' ) ) {
	class WPBakeryShortCode_Vcex_Column_Side_Border extends WPBakeryShortCode {}
}