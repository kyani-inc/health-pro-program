<?php
namespace TotalThemeCore\WPBakery\Params;

defined( 'ABSPATH' ) || exit;

/**
 * WPBakery Param => Text Transform.
 *
 * @package TotalThemeCore
 * @version 1.3.1
 */
final class Text_Transform {

	public static function output( $settings, $value ) {

		if ( function_exists( 'wpex_text_transforms' ) ) {

			$output = '<select name="'
				. $settings['param_name']
				. '" class="wpb_vc_param_value wpb-input wpb-select '
				. $settings['param_name']
				. ' ' . $settings['type'] .'">';

			$options = wpex_text_transforms();

			foreach ( $options as $key => $name ) {

				$output .= '<option value="' . esc_attr( $key )  . '" ' . selected( $value, $key, false ) . '>' . esc_attr( $name ) . '</option>';

			}

			$output .= '</select>';

		} else {
			$output .= '<input type="text" class="wpb_vc_param_value '
				. esc_attr( $settings['param_name'] ) . ' '
				. esc_attr( $settings['type'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" value="' . esc_attr( $value ) . '">';
		}

		return $output;

	}

}