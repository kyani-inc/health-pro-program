<?php
namespace TotalTheme\Integration\WPBakery;

defined( 'ABSPATH' ) || exit;

final class Deprecated_CSS_Params_Style {

	public static function generate_css( $atts = array(), $return = 'temp_class' ) {

		if ( empty( $atts ) || ! is_array( $atts ) ) {
			return;
		}

		$css = '';

		// Margin top
		if ( ! empty( $atts['margin_top'] ) ) {
			$css .= 'margin-top: ' . wpex_sanitize_data( $atts['margin_top'], 'px-pct' ) . ';';
		}

		// Margin bottom
		if ( ! empty( $atts['margin_bottom'] ) ) {
			$css .= 'margin-bottom: ' . wpex_sanitize_data( $atts['margin_bottom'], 'px-pct' ) . ';';
		}

		// Margin right
		if ( ! empty( $atts['margin_right'] ) ) {
			$css .= 'margin-right: ' . wpex_sanitize_data( $atts['margin_right'], 'px-pct' ) . ';';
		}

		// Margin left
		if ( ! empty( $atts['margin_left'] ) ) {
			$css .= 'margin-left: ' . wpex_sanitize_data( $atts['margin_left'], 'px-pct' ) . ';';
		}

		// Padding top
		if ( ! empty( $atts['padding_top'] ) ) {
			$css .= 'padding-top: ' . wpex_sanitize_data( $atts['padding_top'], 'px-pct' ) . ';';
		}

		// Padding bottom
		if ( ! empty( $atts['padding_bottom'] ) ) {
			$css .= 'padding-bottom: ' . wpex_sanitize_data( $atts['padding_bottom'], 'px-pct' ) . ';';
		}

		// Padding right
		if ( ! empty( $atts['padding_right'] ) ) {
			$css .= 'padding-right: ' . wpex_sanitize_data( $atts['padding_right'], 'px-pct' ) . ';';
		}

		// Padding left
		if ( ! empty( $atts['padding_left'] ) ) {
			$css .= 'padding-left: ' . wpex_sanitize_data( $atts['padding_left'], 'px-pct' ) . ';';
		}

		// Border
		if ( ! empty( $atts['border_width'] ) && ! empty( $atts['border_color'] ) ) {
			$border_width = explode( ' ', $atts['border_width'] );
			$border_style = isset( $atts['border_style'] ) ? $atts['border_style'] : 'solid';
			$bcount = count( $border_width );
			if ( 1 === $bcount ) {
				$css .= 'border: ' . esc_attr( $border_width[0] ) . ' ' . esc_attr( $border_style ) . ' ' . esc_attr( $atts['border_color'] ) . ';';
			} else {
				$css .= 'border-color: ' . esc_attr( $atts['border_color'] ) . ';';
				$css .= 'border-style: ' . esc_attr( $border_style ) . ';';
				if ( 2 === $bcount ) {
					$css .= 'border-top-width: ' . esc_attr( $border_width[0] ) . ';';
					$css .= 'border-bottom-width: ' . esc_attr( $border_width[0] ) .';';
					$bw = isset( $border_width[1] ) ? $border_width[1] : '0px';
					$css .= 'border-left-width: ' . esc_attr( $bw ) . ';';
					$css .= 'border-right-width: ' . esc_attr( $bw ) . ';';
				} else {
					$css .= 'border-top-width: ' . esc_attr( $border_width[0] ) .';';
					$bw = isset( $border_width[1] ) ? $border_width[1] : '0px';
					$css .= 'border-right-width: ' . esc_attr( $bw ) .';';
					$bw = isset( $border_width[2] ) ? $border_width[2] : '0px';
					$css .= 'border-bottom-width: ' . esc_attr( $bw ) .';';
					$bw = isset( $border_width[3] ) ? $border_width[3] : '0px';
					$css .= 'border-left-width: ' . esc_attr( $bw ) .';';
				}
			}
		}

		// Background image
		if ( ! empty( $atts['bg_image'] ) ) {
			if ( 'temp_class' == $return ) {
				$bg_image = wp_get_attachment_url( $atts['bg_image'] ) .'?id='. $atts['bg_image'];
			} elseif ( 'inline_css' == $return ) {
				if ( is_numeric( $atts['bg_image'] ) ) {
					$bg_image = wp_get_attachment_url( $atts['bg_image'] );
				} else {
					$bg_image = $atts['bg_image'];
				}
			}
		}

		// Background Image & Color
		if ( ! empty( $bg_image ) && ! empty( $atts['bg_color'] ) ) {
			$style = ! empty( $atts['bg_style'] ) ? $atts['bg_style'] : 'stretch';
			$position = '';
			$repeat   = '';
			$size     = '';
			if ( 'stretch' == $style ) {
				$position = 'center';
				$repeat   = 'no-repeat';
				$size     = 'cover';
			}
			if ( 'fixed' == $style ) {
				$position = '0 0';
				$repeat   = 'no-repeat';
			}
			if ( 'repeat' == $style ) {
				$position = '0 0';
				$repeat   = 'repeat';
			}
			$css .= 'background: ' . esc_attr( $atts['bg_color'] )  .' url(' . esc_url( $bg_image ) . ' );';
			if ( $position ) {
				$css .= 'background-position: ' . esc_attr( $position ) . ';';
			}
			if ( $repeat ) {
				$css .= 'background-repeat: ' . esc_attr( $repeat ) . ';';
			}
			if ( $size ) {
				$css .= 'background-size: ' . esc_attr( $size ) . ';';
			}
		}

		// Background Image - No Color
		if ( ! empty( $bg_image ) && empty( $atts['bg_color'] ) ) {
			$css .= 'background-image: url(' . esc_url( $bg_image ) . ' );'; // Add image
			$style = ! empty( $atts['bg_style'] ) ? $atts['bg_style'] : 'stretch'; // Generate style
			$position = '';
			$repeat   = '';
			$size     = '';
			if ( 'stretch' === $style ) {
				$position = 'center';
				$repeat   = 'no-repeat';
				$size     = 'cover';
			}
			if ( 'fixed' === $style ) {
				$position = '0 0';
				$repeat   = 'no-repeat';
			}
			if ( 'repeat' === $style ) {
				$position = '0 0';
				$repeat   = 'repeat';
			}
			if ( $position ) {
				$css .= 'background-position: ' . esc_attr( $position ) . ';';
			}
			if ( $repeat ) {
				$css .= 'background-repeat: ' . esc_attr( $repeat ) . ';';
			}
			if ( $size ) {
				$css .= 'background-size: ' . esc_attr( $size ) . ';';
			}
		}

		// Background Color - No Image
		if ( ! empty( $atts['bg_color'] ) && empty( $bg_image ) ) {
			$css .= 'background-color: ' . esc_attr( $atts['bg_color'] ) . ';';
		}

		// Return new css
		if ( $css ) {
			if ( 'temp_class' === $return ) {
				return '.temp{' . $css . '}';
			} elseif ( 'inline_css' === $return ) {
				return $css;
			}
		}

	}

}