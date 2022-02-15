<?php
/**
 * vcex_breadcrumbs shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_breadcrumbs', $atts ) ) {
	return;
}

// Define empty vars.
$crumbs = $aria = $schema = '';

// Custom crumbs check.
$is_custom = false;

// Yoast breadcrumbs.
if ( function_exists( 'yoast_breadcrumb' )
	&& current_theme_supports( 'yoast-seo-breadcrumbs' )
	&& get_theme_mod( 'enable_yoast_breadcrumbs', true )
) {
	$crumbs = yoast_breadcrumb( '', '', false );
	$is_custom = true;
}

// Custom breadcrumbs.
elseif ( $custom_breadcrumbs = apply_filters( 'wpex_custom_breadcrumbs', null ) ) {
	$crumbs = wp_kses_post( $custom_breadcrumbs );
	$is_custom = true;
}

// Theme breadcrumbs.
elseif ( class_exists( 'WPEX_Breadcrumbs' ) ) {
	$crumbs = new WPEX_Breadcrumbs();
	$crumbs = $crumbs->generate_crumbs(); // needs to generate it's own to prevent issues with theme stuff.
}

// Return if no crumbs.
if ( ! $crumbs ) {
	return;
}

// Get and extract shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_breadcrumbs', $atts, $this );

// Shortcode classes.
$shortcode_class = array( 'vcex-breadcrumbs' );

$extra_classes = vcex_get_shortcode_extra_classes( $atts, 'vcex_breadcrumbs' );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}


// Responsive settings.
$unique_classname = vcex_element_unique_classname();

$el_responsive_styles = array(
	'font_size' => $atts['font_size'],
);

$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

if ( $responsive_css ) {
	$shortcode_class[] = $unique_classname;
	echo '<style>' . $responsive_css . '</style>';
}

$shortcode_class = vcex_parse_shortcode_classes( implode( ' ', $shortcode_class ), 'vcex_breadcrumbs', $atts );

// Get inline styles.
$shortcode_style = vcex_inline_style( array(
	'color'              => $atts['color'],
	'font_size'          => $atts['font_size'],
	'font_family'        => $atts['font_family'],
	'text_align'         => $atts['align'],
	'line_height'        => $atts['line_height'],
	'letter_spacing'     => $atts['letter_spacing'],
	'background_color'   => $atts['background_color'],
	'border_color'       => $atts['border_color'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
), false );

// Define wrap attributes
$shortcode_attrs = array(
	'class' => $shortcode_class,
	'style' => $shortcode_style,
);

// Get aria tag.
if ( function_exists( 'wpex_get_aria_landmark' ) ) {
	$aria = wpex_get_aria_landmark( 'breadcrumbs' );
}

if ( ! $is_custom ) {
	$schema = ' itemscope itemtype="http://schema.org/BreadcrumbList"';
}

// Display breadcrumbs.
echo '<nav' . vcex_parse_html_attributes( $shortcode_attrs ) . $aria . $schema . '>' . $crumbs . '</nav>';