<?php
/**
 * vcex_icon_box shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_icon_box', $atts ) ) {
	return;
}

// FALLBACK VARS => NEVER REMOVE!!
$padding          = ( isset( $atts['padding'] ) && empty( $atts['wpex_padding'] ) ) ? $atts['padding'] : '';
$background       = $atts['background'] ?? '';
$background_image = $atts['background_image'] ?? '';
$margin_bottom    = $atts['margin_bottom'] ?? '';
$border_color     = $atts['border_color'] ?? '';

// Get shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_icon_box', $atts, $this );

// Extract shortcode atts for easier usage.
extract( $atts );

// Sanitize data & declare main vars.
$output           = '';
$style            = ! empty( $style ) ? $style : 'one';
$has_lightbox     = false;
$has_icon         = false;
$has_side_icon    = in_array( $style, array( 'one', 'seven' ) );
$has_top_icon     = in_array( $style, array( 'two', 'three', 'four', 'five', 'six', 'eight' ) );
$clickable_boxes  = array( 'four', 'five', 'six' );
$heading          = $heading ? do_shortcode( $heading ) : '';
$url_wrap         = in_array( $style, $clickable_boxes ) ? 'true' : $url_wrap;
$url_wrap         = vcex_validate_boolean( $url_wrap );
$has_outer_wrap   = ! empty( $atts['width'] ); // add auter_wrap for custom widths
$icon_spacing     = ! empty( $atts['icon_spacing'] ) ? $atts['icon_spacing'] : '20px';
$stack_bk         = ! empty( $atts['stack_bk'] ) ? $atts['stack_bk'] : false;

// Check if a custom image is set.
switch ( $image_source ) {
	case 'external':
		$image = $external_image;
		$image_is_attachment = false;
		break;
	default:
		if ( 'attachment' === get_post_type( $image ) ) {
			$image_is_attachment = true;
		} else {
			$image = '';
		}
		break;
}

// Get icon.
if ( $image || $icon_alternative_classes || $icon_alternative_character ) {
	$icon = '';
} else {
	$icon = vcex_get_icon_class( $atts, 'icon' );
}

// Check if the element has an icon.
if ( $icon || $icon_alternative_classes || $icon_alternative_character ) {
	$has_icon = true;
}

// Disable URL wrap if the content has a link to prevent conflicts
if ( false !== strpos( $content, '<a href=' ) ) {
	$url_wrap = false;
}

// Get shortcode link attributes.
$onclick_attrs = vcex_get_shortcode_onclick_attributes( $atts, 'vcex_icon_box' );

// Parse link href.
$url = ! empty( $onclick_attrs['href'] ) ? do_shortcode( $onclick_attrs['href'] ) : '';

// Define link attributes.
if ( $url ) {

	if ( $url_wrap ) {

		$onclick_attrs['class'][] = 'vcex-icon-box-link-wrap';
		$onclick_attrs['class'][] = 'wpex-inherit-color';
		$onclick_attrs['class'][] = 'wpex-no-underline';

		if ( $visibility ) {
			$onclick_attrs['class'][] = vcex_parse_visibility_class( $visibility );
		}

	} else {

		$onclick_attrs['class'][] = 'vcex-icon-box-link';
		$onclick_attrs['class'][] = 'wpex-no-underline';

	}

}

// Define main wrap attributes.
$wrap_attrs = array(
	'id'    => trim( vcex_get_unique_id( $unique_id ) ),
	'class' => array(
		'vcex-module',
		'vcex-icon-box',
		'vcex-icon-box-' . sanitize_html_class( $style ),
		'wpex-relative',
	),
);

// Flex styles.
if ( $has_side_icon ) {
	if ( $stack_bk ) {
		$wrap_attrs['class'][] = 'wpex-flex wpex-flex-col wpex-' . sanitize_html_class( $stack_bk ) . '-flex-row';
		$wrap_attrs['class'][] = 'wpex-gap-' . absint( $icon_spacing );
	} else {
		$wrap_attrs['class'][] = 'wpex-flex';
	}
	if ( 'true' === $align_center ) {
		if ( $stack_bk ) {
			$wrap_attrs['class'][] = 'wpex-' . sanitize_html_class( $stack_bk ) . '-items-center';
		} else {
			$wrap_attrs['class'][] = 'wpex-items-center';
		}
	}
}

// Bottom Icon
if ( 'eight' === $style ) {
	$wrap_attrs['class'][] = 'wpex-flex wpex-flex-col wpex-flex-col-reverse';
}

// No icon class.
if ( ! $icon && ! $image && ! $icon_alternative_classes && ! $icon_alternative_character ) {
	$wrap_attrs['class'][] = 'vcex-icon-box-wo-icon';
}

// Shadow.
if ( $shadow ) {
	$wrap_attrs['class'][] = 'wpex-' . sanitize_html_class( $shadow );
}

// Shadow: Hover.
if ( $shadow_hover ) {
	$wrap_attrs['class'][] = 'wpex-hover-' . sanitize_html_class( $shadow_hover );
	if ( ( ! $hover_animation && ! $hover_background ) ) {
		$wrap_attrs['class'][] = 'wpex-transition-shadow';
		$wrap_attrs['class'][] = 'wpex-duration-300';
	}
}

// Bottom Margin.
if ( $bottom_margin ) {
	$wrap_attrs['class'][] = vcex_parse_margin_class( $bottom_margin, 'wpex-mb-' );
}

// Padding.
if ( $wpex_padding ) {
	$wrap_attrs['class'][] = vcex_parse_padding_class( $wpex_padding );
}

// Custom text align for Top/Bottom Icon only.
if ( $atts['alignment'] && ( 'two' === $style || 'eight' === $style ) ) {
	$wrap_attrs['class'][] = vcex_parse_text_align_class( $atts['alignment'] );
}

// Default alignments.
else {

	switch ( $style ) {
		case 'one':
			$wrap_attrs['class'][] = 'wpex-text-left';
			break;
		case 'seven':
			$wrap_attrs['class'][] = 'wpex-text-right';
			break;
		default:
			if ( $has_top_icon ) {
				$wrap_attrs['class'][] = 'wpex-text-center';
			}
			break;
	}

}

if ( 'true' == $hover_white_text ) {
	$wrap_attrs['class']['wpex-hover-white-text'] = 'wpex-hover-white-text';
}

if ( $visibility ) {
	$wrap_attrs['class'][] = vcex_parse_visibility_class( $visibility );
}

// Style specific classes.
switch ( $style ) {

	// Right Icon.
	case 'seven':
		if ( $stack_bk ) {
			$wrap_attrs['class'][] = 'wpex-' . sanitize_html_class( $stack_bk ) . '-flex-row-reverse';
		} else {
			$wrap_attrs['class'][] = 'wpex-flex-row-reverse';
		}
		break;

	// Top Icon Bordered.
	case 'four':
		if ( empty( $wpex_padding ) ) {
			$wrap_attrs['class'][] = 'wpex-p-30';
		}
		$wrap_attrs['class'][] = 'wpex-bordered';
		$border_width = ! empty( $border_width ) ? absint( $border_width ) : 1;
		if ( $border_width > 1 ) {
			$wrap_attrs['class'][] = 'wpex-border-' . sanitize_html_class( $border_width );
		}
		break;

	// Top Icon w Gray Background.
	case 'five':
		if ( empty( $wpex_padding ) ) {
			$wrap_attrs['class'][] = 'wpex-p-30';
		}
		$wrap_attrs['class'][] = 'wpex-bg-gray-100';
		break;

	// Black background.
	case 'six':
		$wrap_attrs['class'][] = 'wpex-bg-black';
		$wrap_attrs['class'][] = 'wpex-text-white';
		$wrap_attrs['class'][] = 'wpex-child-inherit-color';
		if ( empty( $wpex_padding ) ) {
			$wrap_attrs['class'][] = 'wpex-p-30';
		}
		break;

}

if ( $hover_animation ) {
	vcex_enque_style( 'hover-animations' );
	$wrap_attrs['class'][] = esc_attr( vcex_hover_animation_class( $hover_animation ) );
}

// Add Design Options CSS class to proper container.
if ( $css ) {
	$wrap_attrs['class'][] = vcex_vc_shortcode_custom_css_class( $css );
}

// Wrap Style.
$wrap_style = array();

if ( $background_color ) {
	$wrap_style['background_color'] = $background_color;
}

if ( $border_radius ) {
	$wrap_style['border_radius'] = $border_radius;
}

if ( 'four' == $style && $border_color ) {
	$wrap_style['border_color'] = $border_color;
}

// Fallback styles if $css is empty.
if ( empty( $css ) ) {
	if ( $padding ) {
		$wrap_style['padding'] = $padding;
	}
	if ( 'six' == $style && $background ) {
		$wrap_style['background_color'] = $background;
	}
	if ( $background && in_array( $style, $clickable_boxes ) ) {
		$wrap_style['background_color'] = $background;
	}
	if ( $background_image && in_array( $style, $clickable_boxes ) ) {
		$background_image = wp_get_attachment_url( $background_image );
		$wrap_style['background_image'] = $background_image;
		$wrap_attrs['class'][] = 'vcex-background-' . sanitize_html_class( $background_image_style );
	}
	if ( $margin_bottom ) {
		$wrap_style['margin_bottom'] = $margin_bottom;
	}
}

// Hover Background.
if ( $hover_background ) {
	$hover_data = array(
		'background' => vcex_parse_color( $hover_background ),
	);
	$wrap_attrs['data-wpex-hover'] = htmlspecialchars( wp_json_encode( $hover_data ) );
}

// Open outer wrap which is used when adding a custom width to limit the icon box size.
if ( $has_outer_wrap ) {

	$outer_wrap_class = array(
		'vcex-width--icon_box',
		//'vcex-icon-box-css-wrap', // @dperecated 5.0
	);

	if ( ! empty( $atts['width'] ) ) {
		$outer_wrap_class[] = 'wpex-max-w-100';
		switch ( $atts['float'] ) {
			case 'left':
				$outer_wrap_class[] = 'wpex-float-left';
				break;
			case 'right':
				$outer_wrap_class[] = 'wpex-float-right';
				break;
			case 'center':
			default:
				$outer_wrap_class[] = 'wpex-m-auto';
				break;
		}
	}

	if ( $visibility ) {
		$outer_wrap_class[] = vcex_parse_visibility_class( $visibility );
	}

	$outer_wrap_attrs = array(
		'class' => $outer_wrap_class,
		'style' => vcex_inline_style( array(
			'width' => $width,
		), false ),
	);

}

// Add style to wrap_attrs.
$wrap_attrs['style'] = vcex_inline_style( $wrap_style );

// Add custom classes last.
if ( $classes ) {
	$wrap_attrs['class'][] = vcex_get_extra_class( $classes );
}

// Make sure classes are unique.
$wrap_attrs['class'] = array_unique( $wrap_attrs['class'] );

// Apply filters to wrap class and add to wrap_attrs.
$wrap_attrs['class'] = trim( vcex_parse_shortcode_classes( implode( ' ', $wrap_attrs['class'] ), 'vcex_icon_box', $atts ) );

/*-------------------------------------------------------------------------------*/
/* [ Output Starts here ]
/*-------------------------------------------------------------------------------*/

// Open css_animation element (added in it's own element to prevent conflicts with inner styling).
if ( $css_animation && 'none' !== $css_animation ) {

	$css_animation_wrap_style = vcex_inline_style( array(
		'animation_duration' => $animation_duration,
		'animation_delay'    => $animation_delay,
	) );

	$animation_classes = array(
		trim( vcex_get_css_animation( $css_animation ) ),
	);

	if ( $visibility ) {
		$animation_classes[] = vcex_parse_visibility_class( $visibility );
	}

	$output .= '<div class="' . esc_attr( implode( ' ', $animation_classes ) ) . '"' . $css_animation_wrap_style . '>';
}

// Open outer_wrap if needed for custom widths.
if ( $has_outer_wrap ) {
	$output .= '<div' . vcex_parse_html_attributes( $outer_wrap_attrs ) . '>';
}

// Open link if url is defined and set to wrap the whole container.
if ( $url && $url_wrap ) {
	$output .= '<a' . vcex_parse_html_attributes( $onclick_attrs ) . '>';
}

/*-------------------------------------------------------------------------------*/
/* [ Inner vcex-icon-box element starts here ]
/*-------------------------------------------------------------------------------*/
$output .= '<div' . vcex_parse_html_attributes( $wrap_attrs ) . '>';

	/*-------------------------------------------------------------------------------*/
	/* [ Container for Icon/Image ]
	/*-------------------------------------------------------------------------------*/
	if ( $image || $has_icon ) {

		$symbol_classes = array(
			'vcex-icon-box-symbol'
		);

		$symbol_style = array();

		// Prevent flex shrink on side icon styles.
		if ( $has_side_icon ) {
			$symbol_classes[] = 'wpex-flex-shrink-0';
		}

		// Add icon spacing.
		if ( $icon_spacing ) {

			switch ( $style ) {
				case 'one':
					if ( ! $stack_bk ) {
						$symbol_classes[] = 'wpex-mr-' . sanitize_html_class( absint( $icon_spacing ) );
					}
					break;
				case 'seven':
					if ( ! $stack_bk ) {
						$symbol_classes[] = 'wpex-ml-' . sanitize_html_class( absint( $icon_spacing ) );
					}
					break;
				default:
					if ( $has_top_icon ) {
						if ( 'eight' === $style ) {
							$symbol_classes[] = 'wpex-mt-' . sanitize_html_class( absint( $icon_spacing ) );
						} else {
							$symbol_classes[] = 'wpex-mb-' . sanitize_html_class( absint( $icon_spacing ) );
						}
					}
					break;
			}

		}

		// Image specific style.
		if ( $image ) {
			// None needed yet.
		} elseif ( $has_icon ) {

			if ( $icon_width && $has_side_icon ) {
				$symbol_style['width'] = $icon_width; // add width to this container for left/right style icons
			}

			if ( $icon_bottom_margin && $has_top_icon ) {
				$symbol_style['margin_bottom'] = $icon_bottom_margin;
			}

		}

		// Apply filters to classes.
		$symbol_classes = (array) apply_filters( 'vcex_icon_box_symbol_class', $symbol_classes );

		// Open .vcex-icon-box-symbol element.
		$output .= '<div class="' . esc_attr( implode( ' ', $symbol_classes ) )  . '"' . vcex_inline_style( $symbol_style, true ) . '>';

		// Filter check to see if links should be added to symbols.
		$symbol_link = apply_filters( 'vcex_icon_box_symbol_link', $url, $atts );

		// Add link to symbol.
		if ( $symbol_link && ! $url_wrap ) {
			$symbol_link_attrs = $onclick_attrs;
			$symbol_link_attrs['href'] = esc_url( $symbol_link );
			$symbol_link_attrs['class'] = 'wpex-no-underline';
			$output .= '<a' . vcex_parse_html_attributes( $symbol_link_attrs ) . '>';
		}

	}

	/*-------------------------------------------------------------------------------*/
	/* [ Image ]
	/*-------------------------------------------------------------------------------*/
	if ( $image ) {

		$image_style = vcex_inline_style( array(
			'width'         => $image_width,
			'margin_bottom' => $image_bottom_margin,
		), false );

		$image_classes = array(
			'vcex-icon-box-image',
			'wpex-align-middle'
		);

		if ( $image_border_radius_class = vcex_parse_border_radius_class( $image_border_radius ) ) {
			$image_classes[] = $image_border_radius_class;
		}

		if ( apply_filters( 'vcex_icon_box_image_auto_alt', false ) && $heading ) {
			$image_alt = $heading;
		} else {
			$image_alt = vcex_get_attachment_data( $image, 'alt' );
		}

		// Image with custom resizing.
		if ( 'true' == $resize_image && $image_is_attachment ) {

			$output .= vcex_get_post_thumbnail( array(
				'size'       => 'wpex-custom',
				'attachment' => $image,
				'alt'        => $image_alt,
				'width'      => $image_width,
				'height'     => $image_height,
				'crop'       => 'center-center',
				'style'      => $image_style,
				'class'      => $image_classes,
			) );

		}

		// Image with inline sizing.
		else {

			$image_attributes = array(
				'src'   => '',
				'alt'   => $image_alt,
				'class' => $image_classes,
				'style' => '',
			);

			if ( $image_width ) {
				$image_attributes['style'] .= 'width:' . vcex_validate_px_pct( $image_width ) . ';';
			}

			if ( $image_height ) {
				$image_attributes['style'] .= 'height:' . vcex_validate_px_pct( $image_height ) . ';';
			}

			if ( $image_is_attachment ) {
				$image_src = wp_get_attachment_image_src( $image, 'full' );
				if ( ! empty( $image_src[0] ) ) {
					$image_attributes['src'] = esc_url( $image_src[0] );
				}
				if ( ! empty( $image_src[1] ) && 0 !== absint( $image_src[1] ) ) {
					$image_attributes['width'] = $image_src[1];
				}
				if ( ! empty( $image_src[2] ) && 0 !== absint( $image_src[1] ) ) {
					$image_attributes['height'] = $image_src[2];
				}
			} elseif ( is_string( $image ) ) {
				$image_attributes['src'] = esc_url( $image );
			}

			if ( ! empty( $image_attributes['src'] ) ) {
				$output .= '<img' . vcex_parse_html_attributes( $image_attributes ) . '>';
			}

		}

	}

	/*-------------------------------------------------------------------------------*/
	/* [ Icon ]
	/*-------------------------------------------------------------------------------*/
	elseif ( $has_icon ) {

		// Load icon family CSS.
		if ( $icon ) {
			vcex_enqueue_icon_font( $icon_type, $icon );
		}

		// Define icon attributes.
		$icon_classes = array(
			'vcex-icon-box-icon',
			'wpex-inline-flex',
			'wpex-items-center',
			'wpex-justify-center',
			'wpex-child-inherit-color',
			'wpex-text-center',
			'wpex-leading-none',
		);

		// Expand icon when needed.
		if ( $has_side_icon && $icon_width ) {
			$icon_classes[] = 'wpex-w-100';
		}

		// Add default icon color.
		if ( 'six' !== $style ) {
			$icon_classes[] = 'wpex-text-black';
		}

		// Icon border.
		if ( $icon_border_width_class = vcex_parse_border_width_class( $icon_border_width ) ) {
			$icon_classes[] = 'wpex-border-solid';
			$icon_classes[] = $icon_border_width_class;
			if ( ! $icon_width && ! $icon_height ) {
				$icon_classes[] = 'wpex-p-15';
			}
		}

		// Icon shadow.
		if ( $icon_shadow ) {
			$icon_classes[] = 'wpex-' . sanitize_html_class( $icon_shadow );
		}

		// Icon alt character classes.
		if ( $icon_alternative_character ) {
			if ( $icon_font_weight_class = vcex_parse_font_weight_class( $icon_font_weight ) ) {
				$icon_classes[] = $icon_font_weight_class;
			} else {
				$icon_classes[] = 'wpex-font-semibold';
			}
		}

		// Icon Style.
		$icon_style = array(
			'color'         => $atts['icon_color'],
			'font_size'     => $atts['icon_size'],
			'border_radius' => $atts['icon_border_radius'],
			'background'    => $atts['icon_background'],
		);

		// Add custom width for Top style icons.
		if ( $icon_width && $has_top_icon ) {
			$icon_style['width'] = $icon_width; // add width to this container for left/right style icons
		}

		// Add custom height.
		if ( $icon_height ) {
			$icon_style['height'] = $icon_height;
		}

		// Add padding when custom height is empty so custom backgrounds look ok.
		elseif ( ! empty( $icon_background ) || 'true' === $icon_background_accent ) {
			$icon_classes[] = 'wpex-p-15';
		}

		// Convert icon style array to inline style.
		$icon_style = vcex_inline_style( $icon_style );

		// Remove empty and classes.
		$icon_classes = array_unique( array_filter( $icon_classes ) );

		// Apply filters to icon classes.
		$icon_classes = (array) apply_filters( 'vcex_icon_box_icon_class', $icon_classes );

		// Display Icon.
		$output .= '<div class="' . esc_attr( implode( ' ', $icon_classes ) ) . '"' . $icon_style . '>';

			if ( $icon_alternative_classes ) {

				$output .= '<span class="' . esc_attr( do_shortcode( $icon_alternative_classes ) ) . '" aria-hidden="true"></span>';

			} elseif ( $icon_alternative_character ) {

				$output .= do_shortcode( wp_kses_post( $icon_alternative_character ) );

			} else {

				$output .= '<span class="' . esc_attr( $icon ) . '" aria-hidden="true"></span>';

			}

		$output .= '</div>';

	}

	// Close symbol link.
	if ( ! empty( $symbol_link ) && ! $url_wrap ) {
		$output .= '</a>';
	}

	// Close symbol div (icon/image).
	if ( $image || $has_icon ) {
		$output .= '</div>';
	}

	/*-------------------------------------------------------------------------------*/
	/* [ Container for Heading + Content ]
	/*-------------------------------------------------------------------------------*/

	$text_classes = array(
		'vcex-icon-box-text'
	);

	if ( $has_side_icon ) {
		$text_classes[] = 'wpex-flex-grow';
	}

	$output .= '<div class="' . esc_attr( implode( ' ', $text_classes ) )  . '">';

		/*-------------------------------------------------------------------------------*/
		/* [ Heading ]
		/*-------------------------------------------------------------------------------*/
		if ( $heading ) {

			if ( $url && ! $url_wrap ) {
				$output .= '<a' . vcex_parse_html_attributes( $onclick_attrs ) . '>';
			}

			$heading_tag = $heading_type ?: apply_filters( 'vcex_icon_box_heading_default_tag', 'h2' );
			$heading_tag_escaped = tag_escape( $heading_tag );

			$heading_attrs = array(
				'class' => apply_filters( 'vcex_icon_box_heading_class', array(
					'vcex-icon-box-heading',
					'wpex-heading',
					'wpex-text-md',
					'wpex-mb-10',
				) ),
			);

			switch ( $style ) {
				case 'six':
					$heading_attrs['class'][] = ! empty( $heading_color ) ? 'wpex-inherit-color' : 'wpex-inherit-color-important';
					break;
			}

			$heading_attrs['style'] = vcex_inline_style( array(
				'font_family'    => $heading_font_family,
				'font_weight'    => $heading_weight,
				'color'          => $heading_color,
				'font_size'      => $heading_size,
				'letter_spacing' => $heading_letter_spacing,
				'margin_bottom'  => $heading_bottom_margin,
				'text_transform' => $heading_transform,
				'line_height'    => $heading_line_height,
			), false );

			// Responsive styles.
			$unique_classname = vcex_element_unique_classname();

			$el_responsive_styles = array(
				'font_size' => $heading_size,
			);

			$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

			if ( $responsive_css ) {
				$heading_attrs['class'][] = $unique_classname;
				$output .= '<style>' . $responsive_css . '</style>';
			}

			/**
			 * Filters the icon box heading attributes.
			 *
			 * @param array $heading_attributes
			 * @param array $shortcode_attributes
			 */
			$heading_attrs = apply_filters( 'vcex_icon_box_heading_attrs', $heading_attrs, $atts );

			// Begin heading output.
			$output .= '<' . $heading_tag_escaped . vcex_parse_html_attributes( $heading_attrs ) . '>';

				// Heading text.
				$output .= wp_kses_post( $heading );

				// Badge.
				if ( ! empty( $heading_badge ) ) {

					$badge_style = vcex_inline_style( array(
						'background_color' => $heading_badge_background_color,
					), true );

					$output .= ' <span class="wpex-badge"' .  $badge_style . '>' . do_shortcode( wp_strip_all_tags( $heading_badge ) ) . '</span>';
				}

			$output .= '</' . $heading_tag_escaped . '>';

		} // End heading

		// Close link around heading and icon.
		if ( $url && ! $url_wrap ) {
			$output .= '</a>';
		}

		/*-------------------------------------------------------------------------------*/
		/* [ Content ]
		/*-------------------------------------------------------------------------------*/
		if ( $content ) {

			// Sanitize the content.
			$content_escaped = vcex_the_content( $content );

			// Define content attributes.
			$content_attrs = array(
				'class' => apply_filters( 'vcex_icon_box_content_class', array(
					'vcex-icon-box-content',
					'wpex-last-mb-0',
					'wpex-clr'
				) ),
			);

			// Content styles.
			$content_attrs['style'] = vcex_inline_style( array(
				'color'       => $font_color,
				'font_size'   => $font_size,
				'font_weight' => $font_weight,
			), false );

			// Content responsive css.
			$unique_classname = vcex_element_unique_classname();

			$el_responsive_styles = array(
				'font_size' => $font_size,
			);

			$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

			if ( $responsive_css ) {
				$content_attrs['class'][] = $unique_classname;
				$output .= '<style>' . $responsive_css . '</style>';
			}

			// Content output.
			$output .= '<div' . vcex_parse_html_attributes( $content_attrs ) . '>';

				$output .= $content_escaped;

			$output .= '</div>';

		}

	// Close heading/text wrapper.
	$output .= '</div>';

// Close Icon Box element.
$output .= '</div>';

// Close outer link wrap.
if ( $url && $url_wrap ) {
	$output .= '</a>';
}

// Close css wrapper for icon style one.
if ( $has_outer_wrap ) {
	$output .= '</div>';
}

// Clear floats.
if ( $has_outer_wrap && $atts['float'] && 'center' !== $atts['float'] ) {
	$output .= '<div class="vcex-clear--icon_box wpex-clear"></div>';
}

// Close animation wrapper.
if ( $css_animation && 'none' !== $css_animation ) {
	$output .= '</div>';
}

// @codingStandardsIgnoreLine.
echo $output;