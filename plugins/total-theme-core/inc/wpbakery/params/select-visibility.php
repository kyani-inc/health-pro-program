<?php
namespace TotalThemeCore\WPBakery\Params;

defined( 'ABSPATH' ) || exit;

/**
 * WPBakery Param => Visibility.
 *
 * @package TotalThemeCore
 * @version 1.3.1
 */
final class Select_Visibility {

	public static function output( $settings, $value ) {

		if ( function_exists( 'wpex_visibility' ) ) {

			if ( $value ) {
				$value = str_replace( '-portrait', '', $value );
				$value = str_replace( '-landscape', '', $value );
			}

			$output = '<select name="'
				. esc_attr( $settings['param_name'] )
				. '" class="wpb_vc_param_value wpb-input wpb-select '
				. esc_attr( $settings['param_name'] )
				. ' ' . esc_attr(  $settings['type'] ) . '">';

			$options = wpex_visibility();

			foreach ( $options as $key => $name ) {

				$output .= '<option value="' . esc_attr( $key )  . '" ' . selected( $value, $key, false ) . '>' . esc_attr( $name ) . '</option>';

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