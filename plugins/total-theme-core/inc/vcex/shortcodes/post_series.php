<?php
/**
 * Post Series Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.1
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Post_Series_Shortcode' ) ) {

	class VCEX_Post_Series_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_post_series', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Post_Series::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_post_series', $atts );
			include( vcex_get_shortcode_template( 'vcex_post_series' ) );
			do_action( 'vcex_shortcode_after', 'vcex_post_series', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {
			$params = array(
				array(
					'type' => 'vcex_notice',
					'param_name' => 'main_notice',
					'text' => esc_html__( 'Go to Appearance > Customize > General Theme Options > Post Series to customize this global element.', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Max Width', 'total-theme-core' ),
					'param_name' => 'max_width',
					'description' => vcex_shortcode_param_description( 'width' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Aligment', 'total-theme-core' ),
					'param_name' => 'align',
					'dependency' => array( 'element' => 'max_width', 'not_empty' => true ),
				),
			);
			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_post_series' );
		}

	}

}
new VCEX_Post_Series_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Post_Series' ) ) {
	class WPBakeryShortCode_Vcex_Post_Series extends WPBakeryShortCode {}
}