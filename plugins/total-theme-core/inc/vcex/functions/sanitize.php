<?php
/**
 * Sanitization functions.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sanitize border radius.
 */
function vcex_sanitize_border_radius( $input = '' ) {
	switch ( $input ) {
		case '5px':
			$input = 'rounded-sm';
			break;
		case '10px':
			$input = 'rounded';
			break;
		case '15px':
			$input = 'rounded-md';
			break;
		case '20px':
			$input = 'rounded-lg';
			break;
		case '9999px':
		case '50%':
			$input = 'rounded-full';
			break;
	}
	return sanitize_html_class( $input );
}

/**
 * Sanitize margin class.
 *
 * @deprecated 1.2.8
 */
function vcex_sanitize_margin_class( $margin = '', $prefix = '' ) {
	return vcex_parse_margin_class( $margin, $prefix );
}