<?php
/**
 * vcex_post_series shortcode output
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.1
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_post_series', $atts ) ) {
    return;
}

$atts = vcex_shortcode_atts( 'vcex_post_series', $atts, $this );

$shortcode_class = array(
    'vcex-module',
    'vcex-post-series',
);

if ( ! empty( $atts['max_width'] ) ) {

    switch ( $atts['align'] ) {
        case 'left':
            $shortcode_class[] = 'wpex-mr-auto';
            break;
        case 'right':
            $shortcode_class[] = 'wpex-ml-auto';
            break;
        case 'center':
        default:
            $shortcode_class[] = 'wpex-mx-auto';
            break;
    }

}

$shortcode_class = vcex_parse_shortcode_classes( implode( ' ', $shortcode_class ), 'vcex_post_series', $atts );

$shortcode_style = vcex_inline_style( array(
    'max_width' => $atts['max_width'],
) );

$output = '<div class="' . esc_attr( $shortcode_class ) . '"' . $shortcode_style . '>';

    if ( function_exists( 'wpex_get_template_part' ) ) {
        ob_start();
            wpex_get_template_part( 'post_series' );
        $output .= ob_get_clean();
    }

$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;