<?php
defined( 'ABSPATH' ) || exit;

/**
 * WPBakery Tabs Configuration
 *
 * @package TotalTheme
 * @subpackage WPBakery
 * @version 5.3
 *
 * @todo rename to WPBakery_Old_Tabs_Config.
 */
if ( ! class_exists( 'VCEX_VC_Tabs_Config' ) ) {
	class VCEX_VC_Tabs_Config {

		/**
		 * Main constructor
		 *
		 * @since 2.0.0
		 */
		public function __construct() {
			add_action( 'vc_after_init', __CLASS__ . '::add_params', 40 );
			add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, __CLASS__ . '::shortcode_classes', 99, 3 );
		}

		/**
		 * Add custom params.
		 *
		 * @since 4.0
		 */
		public static function add_params() {

			if ( ! function_exists( 'vc_add_params' ) ) {
				return;
			}

			$styles = array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Style', 'total' ),
				'param_name' => 'style',
				'value' => array(
					esc_html__( 'Default', 'total' ) => 'default',
					esc_html__( 'Alternative #1', 'total' ) => 'alternative-one',
					esc_html__( 'Alternative #2', 'total' ) => 'alternative-two',
				),
				'weight' => 9999,
			);

			vc_add_param( 'vc_tabs', $styles );
			vc_add_param( 'vc_tour', $styles );

		}

		/**
		 * Add custom params
		 *
		 *
		 * @since 4.0
		 */
		public static function shortcode_classes( $class_string, $tag, $atts ) {

			if ( ( 'vc_tabs' == $tag || 'vc_tour' == $tag ) && ! empty( $atts['style'] ) ) {
				$class_string .= ' tab-style-' . sanitize_html_class( $atts['style'] );
			}

			return $class_string;

		}

	}
}
new VCEX_VC_Tabs_Config();