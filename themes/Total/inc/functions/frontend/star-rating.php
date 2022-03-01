<?php
/**
 * Star rating functions.
 *
 * @package TotalTheme
 * @subpackage Functions
 * @version 5.3.1
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get star rating.
 *
 * @since 4.0
 */
if ( ! function_exists( 'wpex_get_star_rating' ) ) {
	function wpex_get_star_rating( $rating = '', $post_id = '', $before = '', $after = '' ) {

		// Post id.
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		// Define rating.
		if ( ! $rating ) {
			$rating = get_post_meta( $post_id, 'wpex_post_rating', true );
		}

		// Return if no rating.
		if ( empty( $rating ) ) {
			return false;
		}

		// Store original rating.
		$og_rating = $rating;

		// Sanitize rating.
		$rating = abs( $rating );

		// Define html output.
		$html = '';

		// Star fonts.
		$full_star  = wpex_get_theme_icon_html( 'star' );
		$half_star  = wpex_get_theme_icon_html( 'star-half-empty' );
		$empty_star = wpex_get_theme_icon_html( 'star-empty' );

		/**
		 * Filters the star ratings max value.
		 *
		 * @param int $max_value
		 */
		$max_rating = (int) apply_filters( 'wpex_star_rating_max_value', 5, $post_id );

		// Integers.
		if ( ( is_numeric( $rating ) && ( intval( $rating ) == floatval( $rating ) ) ) ) {
			$html = str_repeat( $full_star, $rating );
			if ( $rating < $max_rating ) {
				$html .= str_repeat( $empty_star, $max_rating - $rating );
			}

		// Fractions.
		} else {
			$rating = intval( $rating );
			$html = str_repeat( $full_star, $rating );
			$html .= $half_star;
			if ( $rating < $max_rating ) {
				$html .= str_repeat( $empty_star, ( $max_rating - 1 ) - $rating );
			}
		}

		// Add screen-reader text.
		$html .= '<span class="screen-reader-text">' . esc_html__( 'Rating', 'total' ) . ': ' . esc_html( $og_rating ) . '</span>';

		/**
		 * Filters the start rating html.
		 *
		 * @param string $html
		 */
		$html = apply_filters( 'wpex_get_star_rating', $html, $rating );

		// Return star rating html.
		if ( $html ) {
			return $before . $html . $after;
		}

	}

}