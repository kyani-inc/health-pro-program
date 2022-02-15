<?php
namespace TotalThemeCore\WPBakery\Params;

defined( 'ABSPATH' ) || exit;

/**
 * WPBakery Param => Top_Right_Bottom_Left.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */
final class Top_Right_Bottom_Left {

	public static function output( $settings, $value ) {

		$defaults = array(
			'top'    => '',
			'right'  => '',
			'bottom' => '',
			'left'   => '',
		);

		// Convert none multi_attribute to multi_attribute
		if ( false === strpos( $value, ':' ) ) {

			$array = explode( ' ', $value );
			$count = count( $array );

			if ( $array ) {

				if ( 1 == $count ) {
					$field_values = array(
						'top'    => $array[0],
						'right'  => $array[0],
						'bottom' => $array[0],
						'left'   => $array[0],
					);
				} elseif ( 2 == $count ) {
					$field_values = array(
						'top'    => $array[0] ?? '',
						'right'  => $array[1] ?? '',
						'bottom' => $array[0] ?? '',
						'left'   => $array[1] ?? '',
					);
				} else {
					$field_values = array(
						'top'    => $array[0] ?? '',
						'right'  => $array[1] ?? '',
						'bottom' => $array[2] ?? '',
						'left'   => $array[3] ?? '',
					);
				}

			}

		} else {

			$field_values = vcex_parse_multi_attribute( $value, $defaults );

		}

		$output = '<div class="vcex-trbl-param">';

			foreach( $field_values as $k => $v ) {

				$icon = $k;

				switch ( $icon ) {
					case 'top':
						$icon = 'up';
						break;
					case 'bottom':
						$icon = 'down';
						break;
				}

				$output .= '<span class="vcex-item"><span class="vcex-icon"><span class="dashicons dashicons-arrow-' . esc_attr( $icon ) . '-alt"></span></span><input class="vcex-input" name="' . esc_attr( $k ) . '" value="' . esc_attr( $v ) . '" type="text" placeholder="-"></span>';

			}

			$output .= '<input name="' . esc_attr( $settings['param_name'] ) . '" class="vcex-hidden-input wpb-input wpb_vc_param_value  ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_field" type="hidden" value="' . esc_attr( $value ) . '">';

		$output .= '</div>';

		return $output;

	}

}