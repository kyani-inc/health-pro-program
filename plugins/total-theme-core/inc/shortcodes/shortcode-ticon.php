<?php
namespace TotalThemeCore\Shortcodes;

defined( 'ABSPATH' ) || exit;

final class Shortcode_Ticon {

	public function __construct() {

		if ( ! shortcode_exists( 'font_awesome' ) ) {
			add_shortcode( 'font_awesome', __CLASS__ . '::output' );
		}

		if ( ! shortcode_exists( 'ticon' ) ) {
			add_shortcode( 'ticon', __CLASS__ . '::output' );
		}

	}

	public static function output( $atts, $content = '' ) {

		if ( is_admin() && ! wp_doing_ajax() ) {
			return;
		}

		extract( shortcode_atts( array(
			'icon'          => '',
			'link'          => '',
			'link_title'    => '',
			'link_target'   => '',
			'link_rel'      => '',
			'margin_right'  => '',
			'margin_left'   => '',
			'margin_top'    => '',
			'margin_bottom' => '',
			'color'         => '',
			'size'          => '',
			'link'          => '',
			'class'         => '',
		), $atts ) );

		if ( empty( $icon ) ) {
			return;
		}

		// Enqueue font icon stylesheet.
		wp_enqueue_style( 'ticons' );

		// Sanitize vars.
		$link       = esc_url( $link );
		$icon       = esc_attr( $icon );
		$link_title = $link_title ? esc_attr( $link_title ) : '';

		// Sanitize $icon
		// @Todo check if part of Ticons array if not enqueue fontawesome and use that.
		if ( apply_filters( 'wpex_font_awesome_shortcode_parse_fa', false ) ) {
			$icon = str_replace( 'fa ', 'ticon ', $icon );
			$icon = str_replace( 'fa-', 'ticon-', $icon );
		}

		// Generate inline styles.
		$style = array();
		$style_escaped = '';
		if ( $color ) {
			$style[] = 'color:' . esc_attr( $color ) . ';';
		}
		if ( $margin_left ) {
			$style[] = 'margin-left:' . intval( $margin_left ) . 'px;';
		}
		if ( $margin_right ) {
			$style[] = 'margin-right:' . intval( $margin_right ) . 'px;';
		}
		if ( $margin_top ) {
			$style[] = 'margin-top:' . intval( $margin_top ) . 'px;';
		}
		if ( $margin_bottom ) {
			$style[] = 'margin-bottom:' . intval( $margin_bottom ) . 'px;';
		}
		if ( $size ) {
			$style[] = 'font-size:' . intval( $size ) . 'px;';
		}
		$style = implode( '', $style );

		if ( $style ) {
			$style = wp_kses( $style, array() ); // @todo Do we need this?
			$style_escaped = ' style="' . esc_attr( $style ) . '"';
		}

		// Display icon with link.
		if ( $link ) {

			$a_attrs = array(
				'href'   => $link,
				'title'  => $link_title,
				'target' => $link_target,
				'rel'    => $link_rel,
			);

			$output = '<a';

				foreach ( $a_attrs as $a_attrs_k => $a_attrs_v ) {
					$output .= ' ' . $a_attrs_k . '=' . '"' . esc_attr( $a_attrs_v ) . '"';
				}

			$output .= '>';

				if ( defined( 'TOTAL_THEME_ACTIVE' ) ) {

					$output .= '<span class="ticon ticon-' . esc_attr( $icon ) . '"' . $style_escaped . '></span>';

				} else {

					wp_enqueue_style( 'font-awesome' );

					$output .= '<span class="fa fa-' . esc_attr( $icon ) . '"' . $style_escaped . '></span>';

				}

			$output .= '</a>';



		}

		// Display icon without link.
		else {

			if ( defined( 'TOTAL_THEME_ACTIVE' ) ) {

				$output = '<span class="ticon ticon-' . esc_attr( $icon ) . '"' . $style_escaped . '></span>';

			} else {

				wp_enqueue_style( 'font-awesome' );

				$output = '<span class="fa fa-' . esc_attr( $icon ) . '"' . $style_escaped . '></span>';

			}


		}

		// Return shortcode output.
		return $output;

	}

}