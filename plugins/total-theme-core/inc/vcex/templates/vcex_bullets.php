<?php
/**
 * vcex_bullets shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_bullets', $atts ) ) {
	return;
}

// Return if no content.
if ( empty( $content ) ) {
	return;
}

// Escape content early to prevent issues with RGBA colors not working with wp_kses_post.
$content_escaped = wp_kses_post( $content );

// Define output.
$output = '';

// Get shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_bullets', $atts, $this );

// Check if icon is enabled.
$has_icon = isset( $atts['has_icon'] ) && 'true' == $atts['has_icon'] ? true : false;

// Define wrap attributes.
$shortcode_attrs = array(
	'id'   => vcex_get_unique_id( $atts['unique_id'] ),
	'data' => '',
);

// Wrap classes.
$shortcode_class = array(
	'vcex-module',
	'vcex-bullets',
);

if ( 'horizontal' === $atts['alignment'] ) {
	$shortcode_class[] = 'vcex-bullets--horizontal';
	if ( ! empty( $atts['gap'] ) ) {
		$shortcode_class[] = 'wpex-gap-' . sanitize_html_class( absint( $atts['gap'] ) );
	} else {
		$shortcode_class[] = 'wpex-gap-20';
	}
	if ( $justify = vcex_parse_justify_content_class( $atts['justify'] ) ) {
		$shortcode_class[] = $justify;
	}
}

if ( $has_icon ) {

	// Pre-defined bullet styles.
	if ( $atts['style'] && ! $atts['icon_type'] ) {
		$shortcode_class[] = 'vcex-bullets-' . sanitize_html_class( $atts['style'] );
	}

	// Custom Icon.
	elseif ( $icon = vcex_get_icon_class( $atts, 'icon' )  ) {

		// Enqueue icon font.
		vcex_enqueue_icon_font( $atts['icon_type'], $icon );

		// Icon inline style.
		$icon_style = vcex_inline_style( array(
			'color' => $atts['icon_color']
		) );

		// Icon HTML.
		if ( ! empty( $atts['icon_spacing'] ) ) {
			$icon_spacing = 'wpex-mr-' . sanitize_html_class( absint( $atts['icon_spacing'] ) );
		} else {
			$icon_spacing = 'wpex-mr-10';
		}

		$add_icon = '<div class="vcex-bullets-ci-wrap wpex-inline-flex"><span class="vcex-icon-wrap ' . $icon_spacing . '"><span class="vcex-icon '. $icon .'" aria-hidden="true"'. $icon_style .'></span></span><div class="vcex-content wpex-flex-grow">';

		// Standard bullets search/replace.
		$content = $content_escaped;
		$content = str_replace( '<li>', '<li>' . $add_icon, $content );

		// Fix bugs with inline center align (lots of customers centered the bullets before align option was added).
		$content = str_replace( '<li style="text-align:center">', '<li style="text-align:center;">', $content );
		$content = str_replace( '<li style="text-align: center">', '<li style="text-align:center;">', $content );
		$content = str_replace( '<li style="text-align: center;">', '<li style="text-align:center;">', $content );
		$content = str_replace( '<li style="text-align:center;">', '<li style="text-align:center;">' . $add_icon, $content );

		// Close elements.
		$content = str_replace( '</li>', '</div></div></li>', $content );
		$content_escaped = $content;

		// Add custom icon wrap class.
		$shortcode_class[] = 'custom-icon';
	}

} else {
	$shortcode_class[] = 'vcex-bullets-ni';
}

// Wrap Style.
$shortcode_attrs['style'] = vcex_inline_style( array(
	'background_color'   => $atts['background_color'],
	'border_color'       => $atts['border_color'],
	'color'              => $atts['color'],
	'font_family'        => $atts['font_family'],
	'font_size'          => $atts['font_size'],
	'letter_spacing'     => $atts['letter_spacing'],
	'font_weight'        => $atts['font_weight'],
	'line_height'        => $atts['line_height'],
	'text_transform'     => $atts['text_transform'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
) );

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

// Get extra classes.
$extra_classes = vcex_get_shortcode_extra_classes( $atts, 'vcex_bullets' );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

// Turn shortcode classes array into string.
$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, 'vcex_bullets', $atts );

// Add filters to shortcode classes and add to attributes.
$shortcode_attrs['class'] = esc_attr( $shortcode_class );

// Begin html output.
$output .= '<div' . vcex_parse_html_attributes( $shortcode_attrs ) . '>';

	$output .= do_shortcode( $content_escaped );

$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;