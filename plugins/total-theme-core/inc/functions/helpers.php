<?php
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'ttc_validate_boolean' ) ) {
	function ttc_validate_boolean( $var ) {
		if ( is_bool( $var ) ) {
			return $var;
	    }

		if ( is_string( $var ) && ( 'false' === strtolower( $var ) || 'off' === strtolower( $var ) ) ) {
			return false;
		}

		return (bool) $var;
	}
}

if ( ! function_exists( 'ttc_sanitize_data' ) ) {
	function ttc_sanitize_data( $data = '', $type = '' ) {
		if ( ! $data ) {
			return '';
		}
		if ( function_exists( 'wpex_sanitize_data' ) ) {
			return wpex_sanitize_data( $data, $type );
		}
		return wp_strip_all_tags( $data );
	}
}