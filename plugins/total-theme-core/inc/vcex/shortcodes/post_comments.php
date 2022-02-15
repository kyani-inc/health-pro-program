<?php
/**
 * Post Comments Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.1
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Comments_Shortcode' ) ) {

	class VCEX_Comments_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_post_comments', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Post_Comments::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_post_comments', $atts );
			include( vcex_get_shortcode_template( 'vcex_post_comments' ) );
			do_action( 'vcex_shortcode_after', 'vcex_post_comments', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Display Heading?', 'total-theme-core' ),
					'param_name' => 'show_heading',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin',
					'value' => vcex_margin_choices(),
					'admin_label' => true,
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
				array(
					'type' => 'vcex_visibility',
					'heading' => esc_html__( 'Visibility', 'total-theme-core' ),
					'param_name' => 'visibility',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'total-theme-core' ),
					'param_name' => 'el_class',
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-theme-core' ),
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_post_comments' );

		}

	}

}
new VCEX_Comments_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Post_Comments' ) ) {
	class WPBakeryShortCode_Vcex_Post_Comments extends WPBakeryShortCode {}
}