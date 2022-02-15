<?php
namespace TotalThemeCore\Shortcodes;

defined( 'ABSPATH' ) || exit;

/**
 * WP Login Link Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3
 */

final class Shortcode_WP_Login_Link {

	public function __construct() {

		if ( ! shortcode_exists( 'wp_login_url' ) ) {
			add_shortcode( 'wp_login_url', __CLASS__ . '::output' );
		}

	}

	public static function output( $atts, $content = '' ) {

		if ( is_admin() && ! wp_doing_ajax() ) {
			return; // !important check because shortcode functions are only loaded on the front-end
		}

		extract( shortcode_atts( array(
			'login_url'       => '',
			'url'             => '',
			'text'            => esc_html__( 'Login', 'total-theme-core' ),
			'logout_text'     => esc_html__( 'Log Out', 'total-theme-core' ),
			'target'          => '',
			'logout_redirect' => '',
			'icon'            => '',
		), $atts, 'wp_login_url' ) );

		// Target.
		if ( 'blank' === $target || '_blank' === $target ) {
			$target = ' target="_blank" rel="noopener"';
		} else {
			$target = '';
		}

		// Define login url.
		if ( $url ) {
			$login_url = $url;
		} elseif ( $login_url ) {
			$login_url = $login_url;
		} else {
			$login_url = wp_login_url();
		}

		// Logout redirect.
		if ( ! $logout_redirect ) {
			$permalink = get_permalink();
			if ( $permalink ) {
				$logout_redirect = $permalink;
			} else {
				$logout_redirect = home_url( '/' );
			}
		}

		// Logged in link.
		if ( is_user_logged_in() ) {
			$href    = wp_logout_url( $logout_redirect );
			$class   = 'wpex_logout';
			$content = wp_strip_all_tags( $logout_text );
		}

		// Non-logged in link.
		else {
			$href    = esc_url( $login_url );
			$class   = 'login';
			$content = wp_strip_all_tags( $text );
		}

		$output = '';

		$output .= '<a href="' . esc_url( $href ) . '" class="' . esc_attr( $class ) . '"' . $target . '>';

			if ( $icon ) {

				if ( 0 !== strpos( 'ticon', $icon ) && in_array( $icon, wpex_ticons_list() ) ) {
					$icon = 'ticon ticon-' . $icon;
				}

				$output .= '<span class="' . esc_attr( $icon ) . '" aria-hidden="true"></span>';

			}

			$output .= $content;

		$output .= '</a>';

		return $output;

	}

}