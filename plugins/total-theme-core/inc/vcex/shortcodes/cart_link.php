<?php
/**
 * Cart Link Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Cart_Link_Shortcode' ) ) {

	class VCEX_Cart_Link_Shortcode {

		public function __construct() {

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Cart_Link::instance();
			}

		}

		public static function get_params() {

			$params = array(
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Link to Cart', 'total'),
					'param_name' => 'link',
					'std' => 'true',
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Items', 'total-theme-core' ),
					'param_name' => 'items',
					'std' => 'icon,count,total',
					'value' => array(
						esc_html__( 'Icon', 'js_composer' ) => 'icon',
						esc_html__( 'Count', 'js_composer' ) => 'count',
						esc_html__( 'Total', 'js_composer' ) => 'total',
					),
					'admin_label' => true,
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'total-theme-core' ),
					'param_name' => 'el_class',
					'description' => vcex_shortcode_param_description( 'el_class' ),
				),
				array(
					'type'  => 'vcex_font_family_select',
					'heading'  => esc_html__( 'Font Family', 'total-theme-core' ),
					'param_name'  => 'font_family',
				),
				array(
					'type'  => 'vcex_font_family_select',
					'heading'  => esc_html__( 'Font Family', 'total-theme-core' ),
					'param_name'  => 'font_family',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'font_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'font_color',
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_cart_link' );

		}

	}

}
new VCEX_Cart_Link_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Cart_Link' ) ) {
	class WPBakeryShortCode_Cart_Link extends WPBakeryShortCode {}
}