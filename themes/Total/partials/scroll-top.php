<?php
/**
 * Scroll back to top button.
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 5.3.1
 */

defined( 'ABSPATH' ) || exit;

// Get button style.
$style = get_theme_mod( 'scroll_top_style' ) ?: 'default';

// Define arrow style.
$arrow = (string) get_theme_mod( 'scroll_top_arrow', 'chevron-up' );

if ( empty( $arrow ) ) {
	$arrow = 'chevron-up';
}

// Define reveel offset.
$reveal_offset = get_theme_mod( 'local_scroll_reveal_offset' );
$reveal_offset = ( $reveal_offset || '0' === $reveal_offset ) ? absint( $reveal_offset ) : 100;

// Define classnames.
$class = array(
	'wpex-block',
	'wpex-fixed',
	'wpex-round',
	'wpex-text-center',
	'wpex-box-content',
	'wpex-transition-all',
	'wpex-duration-200',
	'wpex-bottom-0',
	'wpex-right-0',
	'wpex-mr-25',
	'wpex-mb-25',
	'wpex-no-underline',
);

// Add style based classes.
switch ( $style ) {
	case 'default':
		$class[] = 'wpex-bg-gray-100';
		$class[] = 'wpex-text-gray-500';
		$class[] = 'wpex-hover-bg-accent';
		$class[] = 'wpex-hover-text-white';
	break;
	case 'black':
		$class[] = 'wpex-bg-black';
		$class[] = 'wpex-text-white';
		$class[] = 'wpex-hover-bg-accent';
		$class[] = 'wpex-hover-text-white';
	break;
	case 'accent':
		$class[] = 'wpex-bg-accent';
		$class[] = 'wpex-text-white';
		$class[] = 'wpex-hover-bg-accent_alt';
		$class[] = 'wpex-hover-text-white';
	break;
	case 'icon':
		// No extra classes needed for this style.
	break;
}

// Add shadow class.
if ( $shadow = get_theme_mod( 'scroll_top_shadow', null ) ) {
	$class[] = 'wpex-' . sanitize_html_class( $shadow );
}

// Hide arrow if reveal offset isn't 0.
if ( 0 !== $reveal_offset ) {
	$class[] = 'wpex-invisible';
	$class[] = 'wpex-opacity-0';
}

/**
 * Filters the scroll to top link class.
 *
 * @param array $class
 */
$class = (array) apply_filters( 'wpex_scroll_top_class', $class );

// Get local scroll speed.
$speed = get_theme_mod( 'scroll_top_speed' );
$speed = ( $speed || '0' === $speed ) ? absint( $speed ) : wpex_get_local_scroll_speed();

// Open breakpoint wrapper.
if ( $breakpoint = get_theme_mod( 'scroll_top_breakpoint' ) ) {
	echo '<div class="' . wpex_utl_visibility_class( 'hide', $breakpoint ) . '">';
}

// Define link attributes.
$link_attrs = array(
	'href'               => '#outer-wrap',
	'id'                 => 'site-scroll-top',
	'class'              => array_map( 'sanitize_html_class', $class ),
	'data-scroll-speed'  => strval( $speed ),
	'data-scroll-offset' => strval( $reveal_offset ),
);

if ( $easing = wpex_get_local_scroll_easing() ) {
	$link_attrs['data-scroll-easing'] = esc_attr( $easing );
}

?>

<a <?php echo trim( wpex_parse_attrs( $link_attrs ) ); ?><?php wpex_aria_landmark( 'scroll_top' ); ?>><?php

	// Display Icon
	wpex_theme_icon_html( $arrow );

	// Screen reader text
	wpex_screen_reader_text( esc_html__( 'Back To Top', 'total' ) );

?></a>

<?php
// Close breakpoint wrapper.
if ( $breakpoint ) {
	echo '</div>';
}