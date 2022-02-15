<?php
/**
 * Flex Container shortcode template.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3
 */

defined( 'ABSPATH' ) || exit;

$atts = vcex_shortcode_atts( 'vcex_flex_container', $atts, get_class() );

$output           = '';
$unique_class     = vcex_element_unique_classname( 'vcex-flex-container' );
$flex_direction   = ! empty( $atts['flex_direction'] ) ? $atts['flex_direction'] : 'row';
$stack_breakpoint = $atts['row_stack_bp'];
$breakpoints      = array( 'xl', 'lg', 'md', 'sm' );
$will_stack       = ( $atts['row_stack_bp'] && in_array( $atts['row_stack_bp'], $breakpoints ) );

$classes = array(
	'vcex-flex-container',
	'vcex-module',
	'wpex-flex',
	'wpex-gap-20',
);

if ( ! empty( $atts['width'] ) ) {
	$classes[] = 'wpex-mx-auto';
}

if ( 'true' == $atts['flex_wrap'] ) {
	$classes[] = 'wpex-flex-wrap';
}

if ( 'true' == $atts['flex_grow'] ) {
	$classes[] = 'vcex-flex-container--items_grow';
}

if ( 'column' === $flex_direction ) {
	$classes[] = 'wpex-flex-col';
} elseif ( $will_stack ) {
	if ( 'true' == $atts['row_stack_reverse'] ) {
		$classes[] = 'wpex-flex-col-reverse';
	} else {
		$classes[] = 'wpex-flex-col';
	}
	$classes[] = 'wpex-' . sanitize_html_class( $atts['row_stack_bp'] ) . '-flex-row';
}

if ( $atts['align_items'] ) {

	$align_items_bk = ( $will_stack ) ? $atts['row_stack_bp'] : '';

	if ( $align_items_class = vcex_parse_align_items_class( $atts['align_items'], $align_items_bk ) ) {
		$classes[] = $align_items_class;
	}

}

if ( $atts['justify_content'] ) {

	$justify_content_bk = ( $will_stack ) ? $atts['row_stack_bp'] : '';

	if ( $justify_content_class = vcex_parse_justify_content_class( $atts['justify_content'], $justify_content_bk ) ) {
		$classes[] = $justify_content_class;
	}

}

if ( $shadow_class = vcex_parse_shadow_class( $atts['shadow'] ) ) {
	$classes[] = $shadow_class;
}

if ( ! empty( $atts['el_class'] ) ) {
	$classes[] = vcex_get_extra_class( $atts['el_class'] );
}

if ( $css_class = vcex_vc_shortcode_custom_css_class( $atts['css'] ) ) {
	$classes[] = $css_class;
}

$style = '';

$parent_style = vcex_inline_style( array(
	'gap' => $atts['gap'],
	'max_width' => $atts['width'],
), false );

if ( $parent_style ) {
	$style .= '.' . $unique_class . '{' . $parent_style . '}';
}

// Define breakpoints.
switch ( $atts['row_stack_bp'] ) {
	case 'xl':
		$stack_bp = '1280px';
		break;
	case 'lg':
		$stack_bp = '1024px';
		break;
	case 'md':
		$stack_bp = '768px';
		break;
	case 'sm':
		$stack_bp = '640px';
		break;
}

// Add flex basis CSS.
if ( 'row' === $flex_direction && ! empty( $atts['flex_basis'] ) ) {
	$flex_basis = $atts['flex_basis'];
	if ( $will_stack ) {
		$style .= '@media only screen and (min-width: ' . $stack_bp . ') {';
	}
	if ( is_string( $flex_basis ) && false !== strpos( $flex_basis, ',' ) ) {
		$flex_basis = explode( ',', $flex_basis );
		$count = 0;
		foreach ( $flex_basis as $flex_basis_item ) {
			$count++;
			$flex_basis_item = trim( $flex_basis_item );
			$flex_basis_item_shrink_grow = ''; // reset for each item.
			if ( 'auto' !== $flex_basis_item ) {
				if ( is_numeric( $flex_basis_item ) && 0 !== $flex_basis_item ) {
					$flex_basis_item = $flex_basis_item . 'px';
				}
				$flex_basis_item_shrink_grow = 'flex-grow:0;flex-shrink:1;'; // must allow shrink to prevent issues on smaller devices when flex_wrap is enabled.
			}
			$style .= '.' . $unique_class . ' > *:nth-child(' . $count . ') {flex-basis:' . esc_attr( $flex_basis_item ) . ';' . $flex_basis_item_shrink_grow . '}';
		}

	} else {
		if ( 'auto' === $flex_basis ) {
			$style .= '.' . $unique_class . ' > * {flex-basis:' . esc_attr( $flex_basis ) . ';}';
		} else {
			$style .= '.' . $unique_class . ' > * {flex-basis:' . esc_attr( $flex_basis ) . ';flex-grow:0;flex-shrink:1;}';
		}
	}

	if ( $will_stack ) {
		$style .= '}';
	}

}

// Scacked CSS
if ( $will_stack ) {

	$stack_bp = absint( $stack_bp ) - 1 . 'px'; // remove 1px from breakpoint

	$stack_css = '';

	if ( $atts['row_stack_gap'] ) {
		if ( is_numeric( $atts['row_stack_gap'] ) ) {
			$atts['row_stack_gap'] = $atts['row_stack_gap'] . 'px';
		}
		$stack_css .= '.' . $unique_class . '{gap:' . esc_attr( $atts['row_stack_gap'] ) . ';}';
	}

	if ( $stack_css ) {
		$style .= '@media only screen and (max-width: ' . $stack_bp . ') {' . trim( $stack_css ) . '}';
	}

}

if ( $style ) {
	$classes[] = $unique_class;
	$output .= '<style>' . $style . '</style>';
}

$output .= '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">';

	$output .= do_shortcode( wp_kses_post( $content ) );

$output .= '</div>';

echo $output; // @codingStandardsIgnoreLine