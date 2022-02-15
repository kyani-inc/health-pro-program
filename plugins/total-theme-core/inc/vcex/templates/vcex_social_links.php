<?php
/**
 * vcex_social_links shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_social_links', $atts ) ) {
	return;
}

/**
 * Fallbacks for when you could do something like facebook="" or twitter="" for the social links.
 * Must run before vcex_shortcode_atts.
 *
 * @since 1.3.1
 */
$has_social_links = ! empty( $atts['social_links'] );

if ( ! $has_social_links ) {
	$og_atts = $atts;
}

// Get and extract shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_social_links', $atts, $this );

extract( $atts );

// Get social profiles array | Used for fallback method and to grab icon styles.
$social_profiles = (array) vcex_social_links_profiles();

// Social profile array can't be empty.
if ( ! $social_profiles ) {
	return;
}

// Define output var.
$output = '';

// Sanitize style.
$style = $style ? $style : 'flat';
$expand = vcex_validate_boolean( $expand );

// Get current author social links.
if ( 'true' == $author_links ) {

	$post_tmp    = get_post( vcex_get_the_ID() );
	$post_author = $post_tmp->post_author;

	if ( ! $post_author ) {
		return;
	}

	$loop = array();
	$social_settings = wpex_get_user_social_profile_settings_array();

	foreach ( $social_settings as $id => $label ) {

		if ( $url = get_the_author_meta( 'wpex_'. $id, $post_author ) ) {

			$loop[$id] = $url;

		}

	}

	$post_tmp = '';

} else {

	// Display custom social links.
	// New method since 3.5.0 | must check $atts value due to fallback and default var
	if ( $has_social_links ) {
		$social_links = (array) vcex_vc_param_group_parse_atts( $social_links );
		$loop = array();
		foreach ( $social_links as $key => $val ) {
			$loop[$val['site']] = isset( $val['link'] ) ? do_shortcode( $val['link'] ) : '';
		}
	} else {
		$loop = array();
		foreach( $social_profiles as $key => $val ) {
			$loop[$key] = '';
		}
	}

}

// Loop is required.
if ( ! is_array( $loop ) ) {
	return;
}

// Wrap attributes.
$wrap_attrs = array(
	'id'   => $unique_id,
	'data' => '',
);

// Wrap classes.
$wrap_classes = array( 'vcex-module' );
$wrap_classes[] = 'wpex-social-btns vcex-social-btns';

if ( $expand ) {
	$wrap_classes[] = 'wpex-flex';
	$wrap_classes[] = 'wpex-flex-wrap';
}

if ( $bottom_margin_class = vcex_sanitize_margin_class( $bottom_margin, 'wpex-mb-' ) ) {
	$wrap_classes[] = $bottom_margin_class;
}

if ( $align ) {
	$wrap_classes[] = 'text' . sanitize_html_class( $align );
}

if ( $visibility ) {
	$wrap_classes[] = vcex_parse_visibility_class( $visibility );
}

if ( $css_animation_class = vcex_get_css_animation( $css_animation ) ) {
	$wrap_classes[] = $css_animation_class;
}

if ( $el_class = vcex_get_extra_class( $classes ) ) {
	$wrap_classes[] = $el_class;
}

$wrap_classes[] = 'wpex-last-mr-0';

// Wrap style.
$wrap_style = vcex_inline_style( array(
	'font_size' => $size,
	'border_radius' => $border_radius,
	'animation_delay' => $animation_delay,
	'animation_duration' => $animation_duration,
), false );

// Link Classes.
$link_class   = array();
$link_class[] = vcex_get_social_button_class( $style );
$spacing = $spacing ? absint( $spacing ) : '5';
$link_class[] = 'wpex-mb-' . sanitize_html_class( $spacing );
$link_class[] = 'wpex-mr-' . sanitize_html_class( $spacing );

if ( 'none' === $style && $border_radius_class = vcex_parse_border_radius_class( $border_radius ) ) {
	$link_class[] = $border_radius_class;
}

if ( $height ) {
	$link_class[] = 'wpex-inline-flex';
	$link_class[] = 'wpex-flex-column';
	$link_class[] = 'wpex-items-center';
	$link_class[] = 'wpex-justify-center';
	$link_class[] = 'wpex-leading-none';
}

if ( $color ) {
	$link_class[] = 'wpex-has-custom-color';
}

$a_style = vcex_inline_style( array(
	'min_width'    => $width,
	'height'       => $height,
	'background'   => $bg,
	'color'        => $color,
	'border_color' => $border_color,
), false );

// Reset social button widths/paddings.
if ( $expand || 'true' == $show_label ) {

	$link_class[] = 'wpex-flex-grow';
	$link_class[] = 'wpex-w-auto';
	$link_class[] = 'wpex-h-auto';

	if ( ! $height ) {
		$link_class[] = 'wpex-leading-normal';
	}

	if ( empty( $padding_y ) ) {
		$padding_y = '5';
	}

	if ( empty( $padding_x ) ) {
		$padding_x = '15';
	}

}

// Vertical padding.
if ( $padding_y ) {
	$link_class[] = 'wpex-py-' . absint( $padding_y );
}

// Horizontal padding.
if ( $padding_x ) {
	$link_class[] = 'wpex-px-' . absint( $padding_x );
}

if ( $hover_animation ) {
	$link_class[] = vcex_hover_animation_class( $hover_animation );
	vcex_enque_style( 'hover-animations' );
}

if ( $css ) {
	$link_class[] = vcex_vc_shortcode_custom_css_class( $css );
}

// Hover data.
$a_hover_data = array();

if ( $hover_bg ) {
	$a_hover_data['background'] = esc_attr( vcex_parse_color( $hover_bg ) );
}

if ( $hover_color ) {
	$a_hover_data['color'] = esc_attr( vcex_parse_color( $hover_color ) );
}

$a_hover_data = $a_hover_data ? htmlspecialchars( wp_json_encode( $a_hover_data ) ) : '';

// Responsive styles.
$unique_classname = vcex_element_unique_classname();

$el_responsive_styles = array(
	'font_size' => $atts['size'],
);

$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

if ( $responsive_css ) {
	$wrap_classes[] = $unique_classname;
	$output .= '<style>' . $responsive_css . '</style>';
}

// Add attributes to array.
$wrap_attrs['class'] = vcex_parse_shortcode_classes( $wrap_classes, 'vcex_social_links', $atts );
$wrap_attrs['style'] = $wrap_style;

// Begin output.
$output .= '<div' . vcex_parse_html_attributes(  $wrap_attrs ) . '>';

	// Loop through social profiles.
	foreach ( $loop as $key => $val ) {

		// Google plus was shut down.
		if ( 'googleplus' === $key || 'google-plus' === $key ) {
			continue;
		}

		// Sanitize classname.
		$profile_class = $key;

		// Get URL.
		if ( 'true' != $author_links && false === $has_social_links && ! empty( $og_atts ) ) {
			$url = isset( $og_atts[$key] ) ? $og_atts[$key] : '';
		} else {
			$url = $val;
		}

		// Link output.
		if ( $url ) {

			$a_attrs = array(
				'href'   => esc_url( do_shortcode( $url ) ),
				'class'  => esc_attr( implode( ' ', $link_class ) . ' wpex-' . $profile_class ),
				'target' => $link_target,
			);

			if ( ! empty( $a_style ) ) {
				$a_attrs['style'] = $a_style;
			}

			if ( $a_hover_data ) {
				$a_attrs['data-wpex-hover'] = $a_hover_data;
			}

			$output .= '<a '. vcex_parse_html_attributes( $a_attrs ) .'>';

				$icon_class = $social_profiles[$key]['icon_class'];

				if ( 'true' == $show_label ) {
					$icon_class .= ' wpex-mr-10';
				}

				$output .= '<span class="' . esc_attr( $icon_class ) . '" aria-hidden="true"></span>';

				if ( 'true' == $show_label ) {
					$output .= '<span class="vcex-label">' . ucfirst( wp_strip_all_tags( $key ) ) . '</span>';
				} else {
					$output .= '<span class="screen-reader-text">' . ucfirst( wp_strip_all_tags( $key ) ) . '</span>';
				}

			$output .= '</a>';
		}

	}

$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;