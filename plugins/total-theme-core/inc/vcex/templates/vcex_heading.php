<?php
/**
 * vcex_heading shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_heading', $atts ) ) {
	return;
}

// Get and extract shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_heading', $atts, $this );
extract( $atts );

/*-------------------------------------------------*/
/* [ Get Heading Text ]
/*-------------------------------------------------*/
if ( $atts['source'] && 'custom' !== $atts['source'] ) {
	$text = vcex_get_source_value( $atts['source'], $atts );
} else {
	$text = trim( vcex_vc_value_from_safe( $atts['text'] ) );
	$text = do_shortcode( $text );
}

// Apply filters to the heading text.
$text = apply_filters( 'vcex_heading_text', $text );

// Return if no heading.
if ( empty( $text ) ) {
	return;
}

/*-------------------------------------------------*/
/* [ Define main variables ]
/*-------------------------------------------------*/
$output               = '';
$heading_attrs        = array( 'class' => '' );
$default_tag          = ( $default_tag = get_theme_mod( 'vcex_heading_default_tag', 'div' ) ) ?: 'div';
$tag_escaped          = $tag ? tag_escape( $tag ) : tag_escape( apply_filters( 'vcex_heading_default_tag', $default_tag ) );
$custom_css           = vcex_vc_shortcode_custom_css_class( $css );
$icon                 = vcex_get_icon_class( $atts, 'icon' );
$default_border_width = ( 'side-border' === $style ) ? 3 : 1;
$border_width         = ! empty( $atts['border_width'] ) ? absint( $atts['border_width'] ) : $default_border_width;
$border_style         = ! empty( $atts['border_style'] ) ? $atts['border_style'] : 'solid';

if ( 'plain' === $style || 'side-border' === $style || 'bottom-border' === $style ) {
	$add_css_to_inner = vcex_validate_boolean( $add_css_to_inner );
} else {
	$add_css_to_inner = false;
}

if ( 'plain' !== $style ) {
	$atts['padding_all'] = $atts['background_color'] = $atts['border_radius'] = '' ;
}

/*-------------------------------------------------*/
/* [ Parse Link ]
/*-------------------------------------------------*/
$has_link = false;
$link = vcex_build_link( $link );
if ( $link && isset( $link['url'] ) ) {
	$link['url'] = do_shortcode( $link['url'] );
	if ( ! empty( $link['url'] ) ) {
		$has_link = true;
	}
}

/*-------------------------------------------------*/
/* [ Parse Icon ]
/*-------------------------------------------------*/
if ( $icon ) {

	vcex_enqueue_icon_font( $icon_type, $icon );

	$icon_side_margin = ! empty( $icon_side_margin ) ? absint( $icon_side_margin ) : 15;

	switch ( $icon_position ) {
		case 'right':
			$icon_margin_dir = 'l';
			break;
		default:
			$icon_margin_dir = 'r';
			break;
	}

	$icon_class = array(
		'vcex-icon-wrap',
		'wpex-m' . $icon_margin_dir . '-' . sanitize_html_class( $icon_side_margin )
	);

	$icon_attrs = array(
		'class' => $icon_class,
		'style' => vcex_inline_style( array(
			'color' => $icon_color,
		) )
	);

	$icon_output = '<span' . vcex_parse_html_attributes( $icon_attrs ) . '>';

		$icon_output .= '<span class="' . esc_attr( $icon ) . '" aria-hidden="true"></span>';

	$icon_output .= '</span>';

	// Add icon to heading.
	if ( 'left' === $icon_position ) {
		$icon_left_escaped = $icon_output;
	} else {
		$icon_right_escaped = $icon_output;
	}

}

/*-------------------------------------------------*/
/* [ Heading Classes ]
/*-------------------------------------------------*/
$heading_classes = array(
	'vcex-heading',
	'vcex-module',
	'wpex-text-2xl',
	'wpex-font-normal',
	'wpex-m-auto',
	'wpex-max-w-100',
);

if ( $style ) {
	$heading_classes[] = 'vcex-heading-' . sanitize_html_class( $style );
}

if ( wp_validate_boolean( get_theme_mod( 'vcex_heading_typography_tag_styles', false ) )
	&& in_array( $tag_escaped, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) )
) {
	$heading_classes[] = 'wpex-' . sanitize_html_class( $tag_escaped );
}

if ( 'side-border' === $style ) {
	$heading_classes[] = 'wpex-flex';
	$heading_classes[] = 'wpex-items-center';
} else {
	$heading_classes[] = 'wpex-block';
}

if ( ! $add_css_to_inner ) {

	if ( $padding_class = vcex_parse_padding_class( $atts['padding_all'] ) ) {
		$heading_classes[] = $padding_class;
	}

	if ( $border_radius_class = vcex_parse_border_radius_class( $atts['border_radius'] ) ) {
		$heading_classes[] = $border_radius_class;
	}

	if ( $shadow_class = vcex_parse_shadow_class( $atts['shadow'] ) ) {
		$heading_classes[] = $shadow_class;
	}

}

if ( $bottom_margin_class = vcex_parse_margin_class( $atts['bottom_margin'], 'wpex-mb-' ) ) {
	$heading_classes[] = $bottom_margin_class;
}

if ( $text_align_class = vcex_parse_text_align_class( $atts['text_align'] ) ) {
	$heading_classes[] = $text_align_class;
}

if ( $atts['align'] ) {
	$heading_classes[] = 'align' . sanitize_html_class( $atts['align'] );
}

if ( $css_animation_class = vcex_get_css_animation( $atts['css_animation'] ) ) {
	$heading_classes[] = $css_animation_class;
}

if ( 'true' == $atts['italic'] ) {
	$heading_classes[] = 'wpex-italic';
}

if ( $visibility ) {
	$heading_classes[] = vcex_parse_visibility_class( $atts['visibility'] );
}

if ( $custom_css && ! $add_css_to_inner ) {
	$heading_classes[] = $custom_css;
}

// Auto responsive Text.
if ( 'true' == $responsive_text && $font_size ) {

	// Convert em font size to pixels.
	if ( strpos( $font_size, 'em' ) !== false && strpos( $font_size, '|' ) === false ) {
		$font_size = str_replace( 'em', '', $font_size );
		$font_size = $font_size * absint( vcex_get_body_font_size() );
	}

	// Convert em min-font size to pixels.
	if ( strpos( $min_font_size, 'em' ) !== false ) {
		$min_font_size = str_replace( 'em', '', $min_font_size );
		$min_font_size = $min_font_size * absint( vcex_get_body_font_size() );
	}

	// Add wrap classes and data.
	if ( $font_size && $min_font_size ) {
		$heading_classes[] = 'wpex-responsive-txt';
		$heading_attrs['data-max-font-size'] = absint( $font_size );
		$min_font_size  = $min_font_size ?: '21px'; // 21px = default heading font size
		$min_font_size  = apply_filters( 'wpex_vcex_heading_min_font_size', $min_font_size );
		$heading_attrs['data-min-font-size'] = absint( $min_font_size );

		// Enqueue scripts.
		wp_enqueue_script( 'vcex-responsive-text' );
	}

}

/*-------------------------------------------------*/
/* [ Tweak classes based on heading style ]
/*-------------------------------------------------*/
switch ( $style ) {
	case 'graphical':
		break;
	case 'bottom-border-w-color':
		$heading_classes[] = 'wpex-border-b-2';
		$heading_classes[] = 'wpex-border-solid';
		$heading_classes[] = 'wpex-border-gray-200';
		break;
	case 'bottom-border':
		if ( $border_width > 1 ) {
			$heading_classes[] = 'wpex-border-b-' . sanitize_html_class( $border_width );
		} else {
			$heading_classes[] = 'wpex-border-b';
		}
		$heading_classes[] = 'wpex-border-' . sanitize_html_class( $border_style  );
		$heading_classes[] = 'wpex-border-main';
		break;
	case 'side-border':
		if ( 'right' === $text_align ) {
			$heading_classes[] = 'wpex-flex-row-reverse';
		}
		break;
}

/*-------------------------------------------------*/
/* [ Hover Data Attributes ]
/*-------------------------------------------------*/
$hover_data = array();

if ( $color_hover ) {
	$hover_data['color'] = esc_attr( vcex_parse_color( $color_hover ) );
}

if ( $atts['background_hover'] ) {
	$heading_classes[] = 'transition-all';
	$hover_data['background'] = esc_attr( vcex_parse_color( $atts['background_hover'] ) );
}

if ( $hover_data && ! $add_css_to_inner ) {
	$heading_attrs['data-wpex-hover'] = htmlspecialchars( wp_json_encode( $hover_data ) );
}

/*-------------------------------------------------*/
/* [ Responsive CSS ]
/*-------------------------------------------------*/
$unique_classname = vcex_element_unique_classname();

$el_responsive_styles = array(
	'font_size' => $atts['font_size'],
);

$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

if ( $responsive_css ) {
	$heading_classes[] = $unique_classname;
	$output .= '<style>' . $responsive_css . '</style>';
}

/*-------------------------------------------------*/
/* [ Parse Heading Attributes ]
/*-------------------------------------------------*/

// Add custom classes last.
if ( $el_class = vcex_get_extra_class( $el_class ) ) {
	$heading_classes[] = $el_class;
}

// Turn wrap classes into string and apply filter.
$heading_classes = vcex_parse_shortcode_classes( implode( ' ', $heading_classes ), 'vcex_heading', $atts );

// Add classes to attributes array.
$heading_attrs['class'] = $heading_classes;

// Add inline style.
$heading_attrs['style'] = vcex_inline_style( array(
	'background_color'    => ! $add_css_to_inner ? $atts['background_color'] : NULL,
	'color'               => $atts['color'],
	'font_family'         => $atts['font_family'],
	'font_size'           => $atts['font_size'],
	'letter_spacing'      => $atts['letter_spacing'],
	'font_weight'         => $atts['font_weight'],
	'text_transform'      => $atts['text_transform'],
	'line_height'         => $atts['line_height'],
	'border_bottom_color' => $atts['border_color'] ?: $atts['inner_bottom_border_color_main'],
	'width'               => $atts['width'],
	'animation_delay'     => $atts['animation_delay'],
	'animation_duration'  => $atts['animation_duration'],
), false );

/*-------------------------------------------------*/
/* [ Parse HTMl for side border ]
/*-------------------------------------------------*/
if ( 'side-border' === $style ) {

	$side_border_classes = array(
		'vcex-heading-side-border',
		'wpex-flex-grow',
		'wpex-h-0',
		'wpex-border-' . sanitize_html_class( $border_style ),
		'wpex-border-gray-900',
	);

	$border_side_margin = $border_side_margin ? absint( $border_side_margin ) : 15;
	$border_side_margin_escaped = sanitize_html_class( $border_side_margin );

	switch ( $text_align ) {
		case 'right':
			$side_border_classes['margin'] = 'wpex-mr-' . $border_side_margin_escaped;
			break;
		default:
			$side_border_classes['margin'] = 'wpex-ml-' . $border_side_margin_escaped;
			break;
	}

	if ( $border_width > 1 ) {
		$side_border_classes[] = 'wpex-border-b-' . sanitize_html_class( $border_width );
	} else {
		$side_border_classes[] = 'wpex-border-b';
	}

	$side_border_style = vcex_inline_style( array(
		'border_color' => $border_color ?: $color,
	) );

	$side_border_classes = apply_filters( 'vcex_heading_side_border_class', $side_border_classes, $atts );

	// Default side border.
	$side_border_out = '<span class="' . esc_attr( implode( ' ', $side_border_classes ) ) . '"' . $side_border_style . '></span>';

	// Left side border for center text_align.
	if ( 'center' == $text_align ) {
		unset( $side_border_classes['margin'] );
		$side_border_classes['margin'] = 'wpex-mr-' . $border_side_margin_escaped;
		$side_border_left_out = '<span class="' . esc_attr( implode( ' ', $side_border_classes ) ) . '"' . $side_border_style . '></span>';
	}

}

/*-------------------------------------------------*/
/* [ Heading Output Starts here ]
/*-------------------------------------------------*/
$output .= '<' . $tag_escaped . '' . vcex_parse_html_attributes( $heading_attrs ) . '>';

	// Extra side border for center text_align
	if ( ! empty( $side_border_left_out ) ) {
		$output .= $side_border_left_out;
	}

	/*-------------------------------------------------*/
	/* [ Open Link Element ]
	/*-------------------------------------------------*/
	if ( $has_link ) {

		$link_attrs = array(
			'href'   => esc_url( $link['url'] ),
			'title'  => $link['title'] ?? '',
			'target' => $link['target'] ?? '',
			'rel'    => $link['rel'] ?? '',
		);

		$link_classes = array(
			'wpex-no-underline',
			'wpex-inherit-color',
		);

		if ( 'true' === $link_local_scroll ) {
			$link_classes[] = 'local-scroll-link';
		}

		if ( $atts['background_hover'] && ! $add_css_to_inner ) {
			$link_classes[] = 'wpex-block';
		}

		if ( $link_classes ) {
			$link_attrs[ 'class' ] = $link_classes;
		}

		$output .= '<a' . vcex_parse_html_attributes( $link_attrs ) . '>';
	}

	/*-------------------------------------------------*/
	/* [ Inner Span ]
	/*-------------------------------------------------*/
	$inner_class = array(
		'vcex-heading-inner',
		'wpex-inline-block',
		'wpex-clr',
	);

	switch ( $style ) {
		case 'bottom-border-w-color':
			$inner_class[] = 'wpex-relative';
			$inner_class[] = 'wpex-pb-5';
			$inner_class[] = 'wpex-border-b-2';
			$inner_class[] = 'wpex-border-solid';
			$inner_class[] = 'wpex-border-accent';
			break;
	}

	if ( $add_css_to_inner ) {

		if ( $padding_class = vcex_parse_padding_class( $atts['padding_all'] ) ) {
			$inner_class[] = $padding_class;
		}

		if ( $border_radius_class = vcex_parse_border_radius_class( $atts['border_radius'] ) ) {
			$inner_class[] = $border_radius_class;
		}

		if ( $shadow_class = vcex_parse_shadow_class( $atts['shadow'] ) ) {
			$inner_class[] = $shadow_class;
		}

		if ( $custom_css ) {
			$inner_class[] = $custom_css;
		}

	}

	$inner_style = vcex_inline_style( array(
		'border_color'     => $inner_bottom_border_color,
		'background_color' => $add_css_to_inner ? $background_color : NULL,
	) );

	$inner_attrs = array(
		'class' => $inner_class,
		'style' => $inner_style,
	);

	if ( $hover_data && $add_css_to_inner ) {
		$inner_attrs['data-wpex-hover'] = htmlspecialchars( wp_json_encode( $hover_data ) );
	}

	$output .= '<span' . vcex_parse_html_attributes( $inner_attrs ) . '>';

		// Left Icon.
		if ( ! empty( $icon_left_escaped ) ) {
			$output .= $icon_left_escaped;
		}

		// The heading Text.
		$output .= wp_kses_post( $text );

		// Right Icon.
		if ( ! empty( $icon_right_escaped ) ) {
			$output .= $icon_right_escaped;
		}

		// Badge.
		if ( ! empty( $badge ) ) {

			$badge_style = vcex_inline_style( array(
					'background_color' => $badge_background_color,
				), true );

			$output .= ' <span class="wpex-badge"' . $badge_style . '>' . do_shortcode( wp_strip_all_tags( $badge ) ) . '</span>';

		}

	$output .= '</span>';

	if ( $has_link ) {
		$output .= '</a>';
	}

	// Side border for left/right text_align.
	if ( ! empty( $side_border_out ) ) {
		$output .= $side_border_out;
	}

$output .= '</' . $tag_escaped . '>';

if ( ! vcex_validate_boolean( $float ) ) {
	$output .= '<div class="wpex-clear"></div>';
}

// @codingStandardsIgnoreLine
echo $output;