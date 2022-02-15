<?php
namespace TotalThemeCore\WPBakery\Params;

defined( 'ABSPATH' ) || exit;

/**
 * WPBakery Param => Image Filter.
 *
 * @package TotalThemeCore
 * @version 1.3.1
 */
final class Image_Filter {

	public static function output( $settings, $value ) {

		if ( function_exists( 'wpex_image_filters' ) ) {

			$output = '<select name="'
					. $settings['param_name']
					. '" class="wpb_vc_param_value wpb-input wpb-select '
					. $settings['param_name']
					. ' ' . $settings['type'] .'">';

				$options = wpex_image_filters();

				foreach ( $options as $key => $name ) {

					$output .= '<option value="'. esc_attr( $key )  .'" '. selected( $value, $key, false ) .'>'. esc_attr( $name ) .'</option>';

				}

			$output .= '</select>';

		} else {
			$output = vcex_total_exclusive_notice();
			$output .= '<input type="hidden" class="wpb_vc_param_value '
					. esc_attr( $settings['param_name'] ) . ' '
					. esc_attr( $settings['type'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" value="' . esc_attr( $value ) . '">';
		}

		return $output;

	}

}