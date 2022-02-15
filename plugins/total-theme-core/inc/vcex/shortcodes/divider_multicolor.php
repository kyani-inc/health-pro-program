<?php
/**
 * Divider Multi-Color Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Multi_Color_Divider_Shortcode' ) ) {

	class VCEX_Multi_Color_Divider_Shortcode {

		/**
		 * Define shortcode name.
		 *
		 * Keep as fallback.
		 */
		public $shortcode = 'vcex_divider_multicolor';

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_divider_multicolor', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Divider_Multicolor::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( vcex_get_shortcode_template( 'vcex_divider_multicolor' ) );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				array(
					'type' => 'param_group',
					'param_name' => 'colors',
					'value' => urlencode( json_encode( array(
						array(
							'value' => '#301961',
						),
						array(
							'value' => '#452586',
						),
						array(
							'value' => '#301961',
						),
						array(
							'value' => '#5f3aae',
						),
						array(
							'value' => '#01c1a8',
						),
						array(
							'value' => '#11e2c5',
						),
						array(
							'value' => '#6ffceb',
						),
						array(
							'value' => '#b0fbff',
						),
					) ) ),
					'params' => array(
						array(
							'type' => 'colorpicker',
							'heading' => esc_html__( 'Color', 'total-theme-core' ),
							'param_name' => 'value',
							'admin_label' => true,
						),
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'total-theme-core' ),
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-theme-core' ),
					'param_name' => 'el_class',
				),
				array(
					'type' => 'vcex_visibility',
					'heading' => esc_html__( 'Visibility', 'total-theme-core' ),
					'param_name' => 'visibility',
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
					'type' => 'textfield',
					'heading' => esc_html__( 'Width', 'total-theme-core' ),
					'param_name' => 'width',
					'value' => '100%',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Align', 'total-theme-core' ),
					'param_name' => 'align',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Margin Bottom', 'total-theme-core' ) . ' ' . esc_html__( '(legacy option)', 'total-theme-core' ),
					'description' => vcex_shortcode_param_description( 'margin' ),
					'param_name' => 'margin_bottom',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Height', 'total-theme-core' ),
					'param_name' => 'height',
					'description' => vcex_shortcode_param_description( 'px' ),
					'value' => '8px',
					'admin_label' => true,
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_divider_multicolor' );

		}

	}

}
new VCEX_Multi_Color_Divider_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Divider_Multicolor' ) ) {
	class WPBakeryShortCode_Vcex_Divider_Multicolor extends WPBakeryShortCode {}
}