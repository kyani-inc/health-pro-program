<?php
namespace TotalThemeCore\WPBakery\Params;

defined( 'ABSPATH' ) || exit;

/**
 * WPBakery Param => Select Card Style.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */
final class Select_Card_Style {

	public static function output( $settings, $value ) {

		if ( function_exists( 'wpex_card_select' ) ) {

			return wpex_card_select( array(
				'echo'     => 0,
				'name'     => $settings['param_name'],
				'selected' => $value,
				'class'    => 'wpb_vc_param_value wpb-input vcex-chosen wpb-select ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ), // add vcex-chosen for chosen select
			) );

		}

	}

}