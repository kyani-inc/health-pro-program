<?php
/**
 * Displays the header logo.
 *
 * @package TotalTheme
 * @subpackage Partials
 * @version 5.3.1
 */

defined( 'ABSPATH' ) || exit;

$output     = '';
$logo_url   = wpex_header_logo_url();
$logo_img   = wpex_header_logo_img();
$logo_title = wpex_header_logo_title();

if ( wpex_has_sticky_header() ) {
	$sticky_logo = wpex_sticky_header_logo_img();
}

// Get overlay/transparent header logo when enabled conditionally.
if ( wpex_has_overlay_header() ) {
	$overlay_logo = wpex_overlay_header_logo_img();
}

// Display image logo.
if ( ! empty( $logo_img ) || ! empty( $overlay_logo ) ) {

	// Define logo image attributes.
	$img_attrs = array(
		'src'            => esc_url( $logo_img ),
		'alt'            => $logo_title,
		'class'          => wpex_header_logo_img_class(),
		'width'          => wpex_header_logo_img_width(),
		'height'         => wpex_header_logo_img_height(),
		'data-no-retina' => '',
		'data-skip-lazy' => '',
	);

	/**
	 * Change src to overlay logo if defined.
	 *
	 * @todo we should perhaps move this to wpex_header_logo_img()
	 */
	if ( ! empty( $overlay_logo ) ) {
		$img_attrs['src'] = esc_url( $overlay_logo );
	}

	// Add retina logo if set.
	$retina_logo = wpex_header_logo_img_retina();

	if ( $retina_logo ) {
		$img_attrs['srcset'] = $img_attrs['src'] . ' 1x,' . esc_url( $retina_logo ) . ' 2x';
	}

	if ( ! empty( $sticky_logo ) ) {
		$img_attrs['data-nonsticky-logo'] = '';
	}

	/**
	 * Filters the header logo image attributes.
	 *
	 * @param array $img_attrs
	 */
	$img_attrs = apply_filters( 'wpex_header_logo_img_attrs', $img_attrs );

	// Standard logo html.
	$img_html = '<img ' . wpex_parse_attrs( $img_attrs ) . '>';

	// Sticky logo html.
	if ( ! empty( $sticky_logo ) ) {

		$sticky_img_attrs = array(
			'src'              => esc_url( $sticky_logo ),
			'alt'              => $img_attrs['alt'],
			'class'            => $img_attrs['class'],
			'width'            => wpex_sticky_header_logo_img_width(),
			'height'           => wpex_sticky_header_logo_img_height(),
			'data-no-retina'   => '',
			'data-skip-lazy'   => '',
			'data-sticky-logo' => '',
		);

		if ( $sticky_logo_retina = wpex_sticky_header_logo_img_retina() ) {
			$sticky_img_attrs['srcset'] = $sticky_img_attrs['src'] . ' 1x,' . esc_url( $sticky_logo_retina ) . ' 2x';
		}

		$img_html .= '<img ' . wpex_parse_attrs( $sticky_img_attrs ) . '>';
	}

	/**
	 * Filters the header logo img html.
	 *
	 * @param string $html.
	 */
	$img_html = apply_filters( 'wpex_header_logo_img_html', $img_html );

	/**
	 * Custom header-overlay logo.
	 *
	 * @todo update to have new wpex_header_logo_link_class() so we don't have to write dup html here.
	 */
	if ( ! empty( $overlay_logo ) ) {
		$output .= '<a id="site-logo-link" href="' . esc_url( $logo_url ) . '" rel="home" class="overlay-header-logo">';
			$output .= $img_html;
		$output .= '</a>';
	}

	// Standard site-wide image logo.
	elseif ( ! empty( $logo_img ) ) {
		$output .= '<a id="site-logo-link" href="' . esc_url( $logo_url ) . '" rel="home" class="main-logo">';
			$output .= $img_html;
		$output .= '</a>';
	}

}

// Display text logo.
else {

	$output .= '<a id="site-logo-link" href="' . esc_url( $logo_url ) . '" rel="home" class="' . esc_attr( wpex_header_logo_txt_class() ) . '">';

		// Display logo icon if defined.
		$output .= wpex_header_logo_icon();

		// Display logo text.
		$output .= esc_html( $logo_title );

	$output .= '</a>';

}

// Apply filters and display logo.
echo apply_filters( 'wpex_header_logo_output', $output );