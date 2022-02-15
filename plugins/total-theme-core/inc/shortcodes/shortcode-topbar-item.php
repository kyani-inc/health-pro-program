<?php
namespace TotalThemeCore\Shortcodes;

defined( 'ABSPATH' ) || exit;

final class Shortcode_Topbar_Item {

	public function __construct() {

		if ( ! shortcode_exists( 'topbar_item' ) ) {
			add_shortcode( 'topbar_item', __CLASS__ . '::output' );
		}

	}

	public static function output( $atts, $content = '' ) {

		$atts = shortcode_atts( array(
			'type'            => '',
			'icon'            => '',
			'icon_logged_in'  => '',
			'text'            => '',
			'text_logged_in'  => '',
			'link'            => '',
			'link_target'     => '',
			'link_rel'        => '',
			'login_redirect'  => '',
			'logout_redirect' => '',
			'spacing'         => '20',
			'class'           => '',
		), $atts, 'topbar_item' );

		$user_logged_in = is_user_logged_in();

		// Get item content/text.
		if ( empty( $content ) && ! empty( $atts['text'] ) ) {
			$content = $atts['text'];
		}

		// Login type item.
		if ( ! empty( $atts['type'] ) && 'login' === $atts['type'] ) {

			if ( $user_logged_in ) {

				$logout_redirect = ! empty( $atts['logout_redirect'] ) ? $atts['logout_redirect'] : home_url( '/' );
				$atts['link'] = wp_logout_url( $logout_redirect );

			} else {

				if ( empty( $atts['link'] ) ) {
					$atts['link'] = wp_login_url( $atts['login_redirect'] );
				}

			}

		}

		// Custom logged in icon, text, etc.
		if ( $user_logged_in ) {

			if ( ! empty( $atts['text_logged_in'] ) ) {
				$content = $atts['text_logged_in'];
			}

			if ( ! empty( $atts['icon_logged_in'] ) ) {
				$atts['icon'] = $atts['icon_logged_in'];
			}

		}

		// Item content is required.
		if ( ! $content ) {
			return;
		}

		// Get topbar style.
		$topbar_style = function_exists( 'wpex_topbar_style' ) ? wpex_topbar_style() : '';

		// Start output.
		$html = '';

		// Add icon.
		if ( ! empty( $atts['icon'] ) && function_exists( 'wpex_theme_icon_html' ) ) {
			$html .= wpex_get_theme_icon_html( $atts['icon'], 'wpex-mr-10' );
		}

		// Open link.
		if ( ! empty( $atts['link'] ) ) {

			$html .= '<a href="' . esc_url( trim( $atts['link'] ) ) . '"';
			if ( ! empty( $atts['link_target'] ) ) {
				$html .= 'target="' . esc_attr( trim( $atts['link_target'] )  ) . '"';
				if ( 'blank' === $atts['link_target'] || '_blank' === $atts['link_target'] ) {
					if ( empty( $atts['link_rel'] ) ) {
						$atts['link_rel'] = 'noopener';
					} elseif ( is_string( $atts['link_rel'] ) && false === strpos( $atts['link_rel'], 'noopener' ) ) {
						$atts['link_rel'] .= ' noopener';
					}
				}
			}
			if ( ! empty( $atts['link_rel'] ) ) {
				$html .= 'rel="' . esc_attr( trim( $atts['link_rel'] )  ) . '"';
			}
			$html .= '>';
		}

		// Add content/text.
		if ( $content ) {
			$html .= wp_kses_post( do_shortcode( $content ) );
		}

		// Close link.
		if ( ! empty( $atts['link'] ) ) {
			$html .= '</a>';
		}

		// Item wrap classes.
		$shortcode_class = 'top-bar-item wpex-inline-block';

		if ( isset( $atts['spacing'] ) ) {
			$spacing_escaped = absint( $atts['spacing'] );
			if ( 0 !== $spacing_escaped ) {
				switch ( $topbar_style ) {
					case 'one':
						$shortcode_class .= ' wpex-mr-' . $spacing_escaped;
						break;
					case 'two':
						$shortcode_class .= ' wpex-ml-' . $spacing_escaped;
						break;
					case 'three':
						$shortcode_class .= ' wpex-mx-' . $spacing_escaped;
						break;
				}
			}
		}

		if ( ! empty( $atts['class'] ) ) {
			$shortcode_class .= ' ' . $atts['class'];
		}

		// Return shortcode final html.
		return '<div class="' . esc_attr( $shortcode_class )  . '">' . $html . '</div>';

	}

}