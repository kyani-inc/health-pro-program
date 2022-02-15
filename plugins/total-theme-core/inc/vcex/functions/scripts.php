<?php
/**
 * Enqueue Total Theme Core Scripts for Custom Elements.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue lightbox scripts.
 * This is a Total exclusive script.
 */
function vcex_enqueue_lightbox_scripts() {
	if ( function_exists( 'wpex_enqueue_lightbox_scripts' ) ) {
		wpex_enqueue_lightbox_scripts();
	} elseif ( function_exists( 'wpex_enqueue_ilightbox_scripts' ) ) {
		wpex_enqueue_ilightbox_scripts();
	}
}

/**
 * Enqueue slider scripts.
 */
function vcex_enqueue_slider_scripts( $noCarouselThumbnails = false ) {
	if ( function_exists( 'wpex_enqueue_slider_pro_scripts' ) ) {
		wpex_enqueue_slider_pro_scripts( $noCarouselThumbnails );
	}
}

/**
 * Enqueue carousel scripts.
 */
function vcex_enqueue_carousel_scripts() {
	wp_enqueue_style( 'wpex-owl-carousel' );
	wp_enqueue_script( 'wpex-owl-carousel' );
	wp_enqueue_script( 'imagesloaded' );
	wp_enqueue_script( 'vcex-carousels' );
}

/**
 * Enqueue isotope scripts.
 */
function vcex_enqueue_isotope_scripts() {
	wp_enqueue_script( 'imagesloaded' );
	wp_enqueue_script( 'isotope' );
	wp_enqueue_script( 'vcex-isotope-grids' );
}

/**
 * Enqueue Google Fonts.
 */
function vcex_enqueue_google_font( $font_family = '' ) {
	if ( $font_family && function_exists( 'wpex_enqueue_google_font' ) ) {
		wpex_enqueue_google_font( $font_family );
	}
}

/**
 * Enqueue Fonts.
 */
function vcex_enqueue_font( $font_family = '' ) {
	if ( $font_family && function_exists( 'wpex_enqueue_font' ) ) {
		wpex_enqueue_font( $font_family );
	}
}

/**
 * Enqueue justified gallery scripts.
 */
function vcex_enqueue_justified_gallery_scripts() {
	wp_enqueue_script( 'justifiedGallery' );
	wp_enqueue_script( 'vcex-justified-gallery' );
	wp_enqueue_style( 'vcex-justified-gallery' );
}