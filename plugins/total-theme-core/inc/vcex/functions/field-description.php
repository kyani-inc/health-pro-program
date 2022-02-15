<?php
/**
 * Return commonly used field descriptions.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 *
 * @todo rename to remove _vc_map_ from the name (maybe vcex_param_description).
 */

defined( 'ABSPATH' ) || exit;

function vcex_shortcode_param_description( $param_type = '' ) {

	switch ( $param_type ) :

		case 'header_style':
			return sprintf( esc_html__( 'Select your custom heading style. You can select your global style in %sthe Customizer%s.', 'total-theme-core' ), '<a href="' . esc_url( admin_url( '/customize.php?autofocus[section]=wpex_theme_heading' ) ) . '" target="_blank" rel="noopener noreferrer">', '</a>' );
			break;

		case 'unique_id':
			return sprintf( esc_html__( 'Enter element ID (Note: make sure it is unique and valid according to %sw3c specification%s).', 'total-theme-core' ), '<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank" rel="noopener noreferrer">', '</a>' );
			break;

		case 'el_class':
			return esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-theme-core' );
			break;

		case 'text':
			return esc_html__( 'Allowed:', 'total-theme-core' ) . ' ' . esc_html__( 'text', 'total-theme-core' ) . ', ' . esc_html__( 'shortcodes', 'total-theme-core' ) . '';
			break;

		case 'text_html':
			return esc_html__( 'Allowed:', 'total-theme-core' ) . ' ' . esc_html__( 'text', 'total-theme-core' ) . ', ' . esc_html__( 'shortcodes', 'total-theme-core' ) . ', HTML';
			break;

		case 'px':
			return esc_html__( 'Allowed:', 'total-theme-core' ) . ' px';
			break;

		case 'border_radius':
			return esc_html__( 'Allowed:', 'total-theme-core' ) . ' px, rem, %';
			break;

		case 'width':
			return esc_html__( 'Allowed:', 'total-theme-core' ) . ' px, rem, vw, vmin, vmax, %';
			break;

		case 'height':
			return esc_html__( 'Allowed:', 'total-theme-core' ) . ' px, rem, vh, vmin, vmax, %';
			break;

		case 'border_width':
			return esc_html__( 'Allowed:', 'total-theme-core' ) . ' px, rem, em, thin, medium, thick';
			break;

		case 'padding':
		case 'margin':
			return esc_html__( 'Allowed:', 'total-theme-core' ) . ' px, rem, %';
			break;

		case 'margin_shorthand':
			return esc_html__( 'Please use the following format: top right bottom left.', 'total-theme-core' );
			break;

		case 'line_height':
			return esc_html__( 'Allowed:', 'total-theme-core' ) . ' ' .  esc_html__( 'text', 'total-theme-core' ) . ', ' .  esc_html__( 'number', 'total-theme-core' ) . ', px, %';
			break;

		case 'letter_spacing':
			return esc_html__( 'Allowed:', 'total-theme-core' ) . ' px, rem, vmin, vmax';
			break;

		case 'opacity':
			return esc_html__( 'Enter a decimal or percentage value.', 'total-theme-core' );
			break;

		case 'icon_size':
			return esc_html__( 'Allowed:', 'total-theme-core' ) . ' px, rem, vw, vmin, vmax';
			break;

		case 'ms':
			return esc_html__( 'Enter a value in milliseconds.', 'total-theme-core' );
			break;

		case 'font_size':
			return esc_html__( 'Allowed:', 'total-theme-core' ) . ' px, em, rem, vw, vmin, vmax';
			break;
		default:
			break;

	endswitch;

}