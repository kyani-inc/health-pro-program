<?php
namespace TotalThemeCore\WPBakery\Params;

defined( 'ABSPATH' ) || exit;

/**
 * WPBakery Param => Image Size.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */
final class Image_Size {

	public static function output( $settings, $value ) {

		$output = '<select name="'
				. esc_attr( $settings['param_name'] )
				. '" class="wpb_vc_param_value wpb-input wpb-select '
				. esc_attr( $settings['param_name'] )
				. ' ' . esc_attr( $settings['type'] ) .'">';

		$sizes = array(
			'wpex_custom' => esc_html__( 'Custom Size', 'total-theme-core' ),
		);

		if ( function_exists( 'get_intermediate_image_sizes' ) ) {

			$get_sizes = get_intermediate_image_sizes();
			array_unshift( $get_sizes, 'full' );
			$get_sizes = array_combine( $get_sizes, $get_sizes );
			$sizes     = array_merge( $sizes, $get_sizes );

			foreach ( $sizes as $size => $label ) {

				$output .= '<option value="' . esc_attr( $size ) . '" ' . selected( $value, $size, false ) . '>' . esc_attr( $label ) . '</option>';

			}

		}

		$output .= '</select>';

		return $output;

	}

}