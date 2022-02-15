<?php
namespace TotalThemeCore\WPBakery\Params;

defined( 'ABSPATH' ) || exit;

/**
 * WPBakery Param => Color Picker.
 *
 * @package TotalThemeCore
 * @version 1.3.1
 */
final class Colorpicker {

	public static function output( $settings, $value ) {

		$is_custom = false;
		$custom_color = '';
		$palette = array();

		// @todo this will be it's own function wpex_get_color_palette()
		if ( function_exists( 'wpex_get_color_palette' ) ) {
			$palette = wpex_get_color_palette();
		}

		if ( ! empty( $value ) && ! array_key_exists( $value, $palette ) ) {
			$is_custom = true;
			$custom_color = $value;
		}

		$output = '<div class="vcex-color-param">';

			// Select Field.
			$output .= '<select class="vcex-color-param__select" name="vcex-color-param__select">';

			$output .= '<option value="" ' . selected( $is_custom, true, false ) . '>' . esc_html( 'Custom', 'total-theme-core' ) . '</option>';

				if ( ! empty( $palette ) && is_array( $palette ) ) {
					foreach ( $palette as $key => $val ) {
						$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $value, $key, false ) . ' data-color="' . esc_attr( $val['color'] ) . '">' . esc_html( $val['name'] ) . '</option>';
					}
				}

				if ( isset( $settings['extra_choices'] ) && is_array( $settings['extra_choices'] ) ) {
					foreach ( $settings['extra_choices'] as $label => $val ) {
						$output .= '<option value="' . esc_attr( $val ) . '" ' . selected( $value, $val, false ) . ' data-color="' . esc_attr( $val ) . '">' . esc_html( $label ) . '</option>';
					}
				}

			$output .= '</select>';

			// Color Picker.
			$output .= '<input class="vcex-color-param__picker vc_color-control" name="vcex-color-param__picker" class="vc_color-control" type="text" value="' . esc_attr( $custom_color ) . '">';

			// Color Preview
			$output .= '<div class="vcex-color-param__preview"></div>';

			// Hidden field.
			$output .= '<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value  ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_field" type="hidden" value="' . esc_attr( $value ) . '">';

		$output .= '</div>';

		return $output;

	}

}