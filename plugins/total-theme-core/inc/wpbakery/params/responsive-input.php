<?php
namespace TotalThemeCore\WPBakery\Params;

defined( 'ABSPATH' ) || exit;

/**
 * WPBakery Param => Responsive Input.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Responsive_Input {

	public static function output( $settings, $value ) {

		if ( $value && strpos( $value, ':' ) === false ) {
			$ogvalue = $value;
			$value = 'd:'. $value;
		}

		$medias = array(
			'd'  => array(
				'label' => esc_html__( 'Desktop', 'total-theme-core' ),
				'icon'  => 'dashicons dashicons-desktop',
			),
			'tl' => array(
				'label' => esc_html__( 'Tablet Landscape', 'total-theme-core' ),
				'icon'  => 'dashicons dashicons-tablet',
			),
			'tp' => array(
				'label' => esc_html__( 'Tablet Portrait', 'total-theme-core' ),
				'icon'  => 'dashicons dashicons-tablet',
			),
			'pl' => array(
				'label' => esc_html__( 'Phone Landscape', 'total-theme-core' ),
				'icon'  => 'dashicons dashicons-smartphone',
			),
			'pp' => array(
				'label' => esc_html__( 'Phone Portrait', 'total-theme-core' ),
				'icon'  => 'dashicons dashicons-smartphone',
			),
		);

		$defaults = array();

		foreach ( $medias as $key => $val ) {
			$defaults[$key] = '';
		}

		if ( function_exists( 'vcex_parse_multi_attribute' ) ) {
			$field_values = vcex_parse_multi_attribute( $value, $defaults );
		} else {
			$field_values = array();
			$params_pairs = explode( '|', $value );
			if ( ! empty( $params_pairs ) ) {
				foreach ( $params_pairs as $pair ) {
					$param = preg_split( '/\:/', $pair );
					if ( ! empty( $param[0] ) && isset( $param[1] ) ) {
						if ( 'http' == $param[1] && isset( $param[2] ) ) {
							$param[1] = rawurlencode( 'http:' . $param[2] ); // fix for incorrect urls that are not encoded
						}
						$field_values[ $param[0] ] = rawurldecode( $param[1] );
					}
				}
			}
		}

		$output = '<div class="vcex-rs-param vc_clearfix">';

		$count = 0;

		foreach ( $medias as $key => $val ) {

			$count++;

			$classes = 'vcex-item vcex-item-' . $count;

			if ( $count > 1 && ! $field_values['d'] ) {
				$classes .= ' vcex-hidden';
			}

			$output .= '<div class="' . $classes . '">';

				$icon_classes = 'vcex-icon';

				if ( 'pl' == $key || 'tl' == $key ) {
					$icon_classes .= ' vcex-flip';
				}

				$output .= '<span class="'. esc_attr( $icon_classes ) .'"><span class="'. esc_attr( $val['icon'] ) .'"></span></span>';

				$output .= '<input class="vcex-input" name="' . esc_attr( $key ) . '" value="'. esc_attr( $field_values[$key] ) .'" type="text" placeholder="-">';

			$output .= '</div>';

		}

		if ( ! empty( $ogvalue ) ) {
			$value = $ogvalue;
		}

		$output .= '<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value  ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_field" type="hidden" value="' . esc_attr( $value ) . '">';

		$output .= '</div>';

		return $output;

	}

}