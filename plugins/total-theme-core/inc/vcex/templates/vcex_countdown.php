<?php
/**
 * VCEX Countdown.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_countdown', $atts ) ) {
	return;
}

// Define vars.
$output = '';

// Get and extract shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_countdown', $atts, $this );

// Load js.
$this->enqueue_scripts( $atts ); // @todo this could be added in the VCEX_Countdown_Shortcode class instead.

// Get end date data.
$end_year  = ! empty( $atts['end_year'] ) ? intval( $atts['end_year'] ) : date( 'Y' );
$end_month = intval( $atts['end_month'] );
$end_day   = intval( $atts['end_day'] );

// Sanitize data to make sure input is not crazy.
if ( $end_month > 12 ) {
	$end_month = '';
}
if ( $end_day > 31 ) {
	$end_day = '';
}

// Define end date.
if ( $end_year && $end_month && $end_day ) {
	$end_date = $end_year . '-' . $end_month . '-' . $end_day;
} else {
	$end_date = '2018-12-15';
}

// Add end time.
$atts['end_time'] = $atts['end_time'] ?: '00:00';
$end_date = $end_date . ' ' . esc_html( $atts['end_time'] );

// Make sure date is in correct format.
$end_date = date( 'Y-m-d H:i', strtotime( $end_date ) );

// Countdown data.
$data = array();
$data['data-countdown'] = $end_date;
$data['data-days']      = $atts['days'] ?: esc_html__( 'Days', 'total' );
$data['data-hours']     = $atts['hours'] ?: esc_html__( 'Hours', 'total' );
$data['data-minutes']   = $atts['minutes'] ?: esc_html__( 'Minutes', 'total' );
$data['data-seconds']   = $atts['seconds'] ?: esc_html__( 'Seconds', 'total' );

if ( $atts['timezone'] ) {
	$data['data-timezone'] = esc_attr( $atts['timezone'] );
}

$data = apply_filters( 'vcex_countdown_data', $data, $atts ); // Apply filters for translations

// Define wrap attributes.
$shortcode_attrs = array(
	'data' => ''
);

// Main classes.
$shortcode_class = array(
	'vcex-module',
	'vcex-countdown-wrap'
);

$extra_classes = vcex_get_shortcode_extra_classes( $atts, 'vcex_countdown' );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

// Style.
$styles = array(
	'background_color'   => $atts['background_color'],
	'border_color'       => $atts['border_color'],
	'color'              => $atts['color'],
	'font_family'        => $atts['font_family'],
	'font_size'          => $atts['font_size'],
	'letter_spacing'     => $atts['letter_spacing'],
	'font_weight'        => $atts['font_weight'],
	'text_align'         => $atts['text_align'],
	'line_height'        => $atts['line_height'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
);

if ( 'true' == $atts['italic'] ) {
	$styles['font_style'] = 'italic';
}

$shortcode_style = vcex_inline_style( $styles, false );

// Responsive styles.
$unique_classname = vcex_element_unique_classname();

$el_responsive_styles = array(
	'font_size' => $atts['font_size'],
);

$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

if ( $responsive_css ) {
	$shortcode_class[] = $unique_classname;
	$output .= '<style>' . $responsive_css . '</style>';
}

// Add to attributes.
$shortcode_attrs['class'] = vcex_parse_shortcode_classes( implode( ' ', $shortcode_class ), 'vcex_countdown', $atts );
$shortcode_attrs['style'] = $shortcode_style;

// Output.
$output .= '<div' . vcex_parse_html_attributes( $shortcode_attrs ) . '>';

	$inner_class = array( 'vcex-countdown' );

	$output .= '<div class="' . esc_attr( implode( ' ', $inner_class ) ) . '"';

		foreach ( $data as $name => $value ) {
			$output .= ' ' . $name . '=' . '"' . esc_attr( $value ) . '"';
		}

	$output .= '>';

	$output .='</div>';

$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;