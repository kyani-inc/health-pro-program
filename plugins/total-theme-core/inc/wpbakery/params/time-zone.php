<?php
namespace TotalThemeCore\WPBakery\Params;

defined( 'ABSPATH' ) || exit;

/**
 * WPBakery Param => Text Transform.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */
final class Time_Zone {

	public static function output( $settings, $value ) {

		$output = '<select name="'
				. $settings['param_name']
				. '" class="wpb_vc_param_value wpb-input wpb-select vcex-chosen '
				. $settings['param_name']
				. ' ' . $settings['type'] .'">';

		$output .= '<option value="" '. selected( $value, '', false ) .'>' . esc_html__( 'Default', 'total' ) . '</option>';

		if ( function_exists( 'timezone_identifiers_list' ) ) {

			$zones = timezone_identifiers_list();

			foreach ( $zones as $zone ) {

				$output .= '<option value="'. esc_attr( $zone )  .'" '. selected( $value, $zone, false ) .'>'. esc_attr( $zone ) .'</option>';

			}

		}

		$output .= '</select>';

		return $output;

	}

}