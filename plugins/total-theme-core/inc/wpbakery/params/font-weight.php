<?php
namespace TotalThemeCore\WPBakery\Params;

defined( 'ABSPATH' ) || exit;

/**
 * WPBakery Param => Font Weight.
 *
 * @package TotalThemeCore
 * @version 1.3.1
 */
final class Font_Weight {

	public static function output( $settings, $value ) {

		if ( function_exists( 'wpex_font_weights' ) ) {

			$output = '<select name="'
				. esc_attr( $settings['param_name'] )
				. '" class="wpb_vc_param_value wpb-input wpb-select '
				. esc_attr( $settings['param_name'] )
				. ' ' . esc_attr( $settings['type'] ) .'">';

				$options = wpex_font_weights();

				foreach ( $options as $key => $name ) {

					$output .= '<option value="' . esc_attr( $key )  . '" ' . selected( $value, $key, false ) . '>' . esc_attr( $name ) . '</option>';

				}

			$output .= '</select>';

		} else {

			$output = '<input type="text" class="wpb_vc_param_value '
					. esc_attr( $settings['param_name'] ) . ' '
					. esc_attr( $settings['type'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" value="' . esc_attr( $value ) . '">';
		}

		return $output;

	}

}