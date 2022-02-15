<?php
/**
 * Staff Social Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Staff_Social_Shortcode' ) ) {

	class VCEX_Staff_Social_Shortcode {

		public function __construct() {

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Staff_Social::instance();
			}

		}

	}

}
new VCEX_Staff_Social_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Staff_Social' ) ) {
	class WPBakeryShortCode_Staff_Social extends WPBakeryShortCode {}
}