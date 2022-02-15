<?php
/**
 * vcex_login_form shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_login_form', $atts ) ) {
	return;
}

// Get and extract shortcode attributes
$atts = vcex_shortcode_atts( 'vcex_login_form', $atts, $this );

// Check if user is logged in.
$is_user_logged_in = ( is_user_logged_in() && ! vcex_vc_is_inline() ) ? true : false;

// Define output.
$output = '';

// Get classes.
$wrap_class = array(
	'vcex-module',
	'vcex-login-form',
	'wpex-clr',
);

switch ( $atts['style'] ) {
	case 'boxed':
		$wrap_class[] = 'wpex-boxed';
		break;
	case 'bordered':
	default:
		$wrap_class[] = 'wpex-bordered';
		break;
}

if ( $atts['form_style'] ) {
	$wrap_class[] = 'wpex-form-' . sanitize_html_class( $atts['form_style'] );
}

if ( $atts['width'] ) {
	$wrap_class[] = 'wpex-max-w-100';
	switch ( $atts['float'] ) {
		case 'left':
			$wrap_class[] = 'wpex-float-left';
			break;
		case 'right':
			$wrap_class[] = 'wpex-float-right';
			break;
		case 'center':
		default:
			$wrap_class[] = 'wpex-m-auto';
			break;
	}
}

if ( $is_user_logged_in ) {
	$wrap_class[] = 'logged-in';
}

$extra_classes = vcex_get_shortcode_extra_classes( $atts, 'vcex_login_form' );

if ( $extra_classes ) {
	$wrap_class = array_merge( $wrap_class, $extra_classes );
}

// Apply filters.
$wrap_class = vcex_parse_shortcode_classes( $wrap_class, 'vcex_login_form', $atts );

// Wrap style.
$wrap_style = vcex_inline_style( array(
	'width'              => $atts['width'],
	'color'              => $atts['text_color'],
	'font_size'          => $atts['text_font_size'],
	'background_color'   => $atts['background_color'],
	'border_color'       => $atts['border_color'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
) );

// Begin output.
$output .= '<div class="' . esc_attr( $wrap_class ) . '"' . $wrap_style . vcex_get_unique_id( $atts['unique_id'] ) . '>';

	// Check if user is logged in and not in front-end editor.
	if ( $is_user_logged_in ) :

		$output .= do_shortcode( $content );

	// If user is not logged in display login form.
	else :

		$output .= wp_login_form( array(
			'echo'           => false,
			'redirect'       => $atts['redirect'] ? esc_url( $atts['redirect'] ) : esc_url( wpex_get_current_url() ),
			'form_id'        => 'vcex-loginform',
			'label_username' => $atts['label_username'] ?: esc_html__( 'Username', 'total' ),
			'label_password' => $atts['label_password'] ?: esc_html__( 'Password', 'total' ),
			'label_remember' => $atts['label_remember'] ?: esc_html__( 'Remember Me', 'total' ),
			'label_log_in'   => $atts['label_log_in'] ?: esc_html__( 'Log In', 'total' ),
			'remember'       => vcex_validate_boolean( $atts['remember'] ),
			'value_username' => NULL,
			'value_remember' => false,
		) );

		if ( 'true' == $atts['register'] || 'true' == $atts['lost_password'] ) {

			$output .= '<div class="vcex-login-form-nav wpex-clr">';

				if ( 'true' == $atts['register'] ) {

					$register_label = $atts['register_label'] ?: esc_html__( 'Register', 'total' );
					$register_url = $atts['register_url'] ?: wp_registration_url();

					$output .= '<a href="' . esc_url( $register_url ) . '" class="vcex-login-form-register">' . esc_html( $register_label ) . '</a>';

				}

				if ( 'true' == $atts['register'] && 'true' == $atts['lost_password'] ) {
					$output .= '<span class="pipe">|</span>';
				}

				if ( 'true' == $atts['lost_password'] ) {

					$lost_password_label = $atts['lost_password_label'] ?:  esc_html__( 'Lost Password?', 'total' );

					$output .= '<a href="' . esc_url( wp_lostpassword_url( get_permalink() ) ) . '" class="vcex-login-form-lost">' . esc_html( $lost_password_label ) . '</a>';
				}

			$output .= '</div>';

		}

	endif;

$output .= '</div>';

if ( $atts['width'] && 'center' !== $atts['float'] ) {
	$output .= '<div class="vcex-clear--login_form wpex-clear"></div>';
}

// @codingStandardsIgnoreLine
echo $output;