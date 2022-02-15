<?php
/**
 * Social Share Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.1
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Social_Share_Shortcode' ) ) {

	class VCEX_Social_Share_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_social_share', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Social_Share::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_social_share', $atts );
			include( vcex_get_shortcode_template( 'vcex_social_share' ) );
			do_action( 'vcex_shortcode_after', 'vcex_social_share', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$social_share_items = vcex_get_social_items();

			$default_sites = array();
			$site_choices  = array();

			foreach ( $social_share_items as $k => $v ) {
				$default_sites[$k] = array(
					'site' => $k
				);
				$site_choices[$v['site']] = $k;
			}

			$params = array(
				// Sites
				array(
					'type' => 'param_group',
					'param_name' => 'sites',
					'heading' => esc_html__( 'Sites', 'total-theme-core' ),
					'value' => urlencode( json_encode( $default_sites ) ),
					'params' => array(
						array(
							'type' => 'dropdown',
							'heading' => esc_html__( 'Site', 'total-theme-core' ),
							'param_name' => 'site',
							'admin_label' => true,
							'value' => $site_choices,
						),
					),
					'group' => esc_html__( 'Sites', 'total-theme-core' ),
				),
				// General
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Style', 'total-theme-core' ),
					'param_name' => 'style',
					'value' => array(
						esc_html__( 'Default', 'total-theme-core' ) => '',
						esc_html__( 'Flat', 'total-theme-core' )    => 'flat',
						esc_html__( 'Minimal', 'total-theme-core' ) => 'minimal',
						esc_html__( '3D', 'total-theme-core' )      => 'three-d',
						esc_html__( 'Rounded', 'total-theme-core' ) => 'rounded',
						esc_html__( 'Magazine', 'total-theme-core' ) => 'mag',
						esc_html__( 'Custom', 'total-theme-core' )  => 'custom',
					),
					'description' => esc_html__( 'You can customize your social share buttons under Appearance > Customize > General Theme Options > Social Share Buttons.', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Alignment', 'total-theme-core' ),
					'param_name' => 'align',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin',
					'value' => vcex_margin_choices(),
					'admin_label' => true,
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
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_social_share' );

		}

	}

}
new VCEX_Social_Share_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Social_Share' ) ) {
	class WPBakeryShortCode_Vcex_Social_Share extends WPBakeryShortCode {}
}