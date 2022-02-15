<?php
namespace TotalThemeCore\WPBakery\Params;

defined( 'ABSPATH' ) || exit;

/**
 * WPBakery Param => Menu.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */
final class Menu {

	public static function output( $settings, $value ) {

		$output = '<select name="'
				. esc_attr( $settings['param_name'] )
				. '" class="wpb_vc_param_value wpb-input wpb-select '
				. esc_attr( $settings['param_name'] )
				. ' ' . esc_attr( $settings['type'] ) . '">';

		$output .= '<option value="" ' . selected( $value, '', false ) . '>' . esc_html( 'Select', 'total-theme-core' ) . '</option>';

		$menus_array = array();
		$menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
		if ( $menus && is_array( $menus ) ) {
			foreach ( $menus as $menu ) {
				$output .= '<option value="' . esc_attr( $menu->term_id ) . '" ' . selected( $value, $menu->term_id, false ) . '>' . esc_attr( $menu->name ) . '</option>';
			}
		}

		$output .= '</select>';

		return $output;

	}

}