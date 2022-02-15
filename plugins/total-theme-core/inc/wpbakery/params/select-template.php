<?php
namespace TotalThemeCore\WPBakery\Params;
use WP_Query;

defined( 'ABSPATH' ) || exit;

/**
 * WPBakery Param => Select Template.
 *
 * @package TotalThemeCore
 * @version 1.2.10
 */
final class Select_Template {

	public static function output( $settings, $value ) {

		if ( ! is_numeric( $value ) || empty( $value ) ) {
			$value = '';
		}

		$output = '<select name="'
			. esc_attr( $settings['param_name'] )
			. '" class="wpb_vc_param_value wpb-input wpb-select '
			. esc_attr( $settings['param_name'] )
			. ' ' . esc_attr( $settings['type'] ) . '">';

		$output .= '<option value="" ' . selected( $value, '', false ) . '>' . esc_html__( 'Default', 'total' ) . '</option>';

		$templates = get_posts( array(
			'posts_per_page' => -1,
			'post_type'      => 'templatera',
			'fields'         => 'ids',
		) );

		if ( is_array( $templates ) && ! is_wp_error( $templates ) ) {

			foreach ( $templates as $template ) {

				$output .= '<option value="' . esc_attr( $template )  . '" ' . selected( $value, $template, false ) . '>' . esc_html( get_the_title( $template ) ) . '</option>';

			}

		}

		$output .= '</select>';

		return $output;

	}

}