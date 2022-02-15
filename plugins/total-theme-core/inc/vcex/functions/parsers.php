<?php
/**
 * Parsing functions.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

/*-------------------------------------------------------------------------------*/
/* [ Table of contents ]
/*-------------------------------------------------------------------------------*

	# General
	# HTML Attributes
	# Lightbox Data
	# Classnames
	# Old Params

/*-------------------------------------------------------------------------------*/
/* [ General ]
/*-------------------------------------------------------------------------------*/

/**
 * Parses a color to return correct value.
 */
function vcex_parse_color( $color = '' ) {
	if ( function_exists( 'wpex_parse_color' ) ) {
		$color = wpex_parse_color( $color );
	}
	return $color;
}

/**
 * Parses a direction for RTL compatibility.
 */
function vcex_parse_direction( $direction = '' ) {
	if ( ! $direction ) {
		return;
	}
	if ( is_rtl() ) {
		switch ( $direction ) {
			case 'left' :
				$direction = 'right';
			break;
			case 'right' :
				$direction = 'left';
			break;
		}
	}
	return $direction;
}

/**
 * Parses multi attribute setting.
 */
function vcex_parse_multi_attribute( $value = '', $default = array() ) {
	$result = $default;
	$params_pairs = explode( '|', $value );
	if ( ! empty( $params_pairs ) ) {
		foreach ( $params_pairs as $pair ) {
			$param = preg_split( '/\:/', $pair );
			if ( ! empty( $param[0] ) && isset( $param[1] ) ) {
				if ( 'http' == $param[1] && isset( $param[2] ) ) {
					$param[1] = rawurlencode( 'http:' . $param[2] ); // fix for incorrect urls that are not encoded
				}
				$result[ $param[0] ] = rawurldecode( $param[1] );
			}
		}
	}
	return $result;
}

/**
 * Parses textarea HTML.
 */
function vcex_parse_textarea_html( $html = '' ) {
	if ( $html && base64_decode( $html, true ) ) {
		return rawurldecode( base64_decode( strip_tags( $html ) ) );
	}
	return $html;
}



/**
 * Parses the font_control / typography param (used for mapper and front-end).
 */
function vcex_parse_typography_param( $value ) {
	$defaults = array(
		'tag'               => '',
		'text_align'        => '',
		'font_size'         => '',
		'line_height'       => '',
		'color'             => '',
		'font_style_italic' => '',
		'font_style_bold'   => '',
		'font_family'       => '',
		'letter_spacing'    => '',
		'font_family'       => '',
	);
	if ( ! function_exists( 'vc_parse_multi_attribute' ) ) {
		return $defaults;
	}
	$values = wp_parse_args( vc_parse_multi_attribute( $value ), $defaults );
	return $values;
}


/*-------------------------------------------------------------------------------*/
/* [ HTML Attributes ]
/*-------------------------------------------------------------------------------*/

/**
 * Takes array of html attributes and converts into a string.
 *
 * @param array $attrs Array of attributes
 */
function vcex_parse_html_attributes( $attrs = array() ) {
	if ( function_exists( 'wpex_parse_attrs' ) ) {
		$html = wpex_parse_attrs( $attrs );
		return ' ' . trim( $html ); // always include space at the front.
	}

	if ( empty( $attrs ) || ! is_array( $attrs ) ) {
		return $attrs; // return $attrs incase it's a string already.
	}

	// Add noopener noreferrer automatically to nofollow links if rel attr isn't set.
	if ( isset( $attrs['href'] )
		&& isset( $attrs['target'] )
		&& in_array( $attrs['target'], array( '_blank', 'blank' ) )
	) {

		/**
		 * Filters targeted link rel value.
		 *
		 * @param string $value
		 * @param string href
		 */
		$rel = apply_filters( 'wpex_targeted_link_rel', 'noopener noreferrer', $attrs['href'] );

		if ( ! empty( $rel ) ) {
			if ( ! empty( $attrs['rel'] ) ) {
				$attrs['rel'] .= ' ' . $rel;
			} else {
				$attrs['rel'] = $rel;
			}
		}
	}

	// Define output var.
	$output = '';

	// Loop through attributes.
	foreach ( $attrs as $key => $val ) {

		// Attributes used for other things, we can skip these.
		if ( 'content' === $key ) {
			continue;
		}

		// If the attribute is an array convert to string.
		if ( is_array( $val ) ) {
			$val = array_map( 'trim', $val );
			$val = implode( ' ', $val );
		}

		// Sanitize specific attributes.
		switch ( $key ) {
			case 'href':
				$val = esc_url( $val );
				break;
			case 'id':
				$val = trim( str_replace( '#', '', $val ) );
				$val = str_replace( ' ', '', $val );
				break;
			case 'target':
				if ( ! in_array( $val, array( '_blank', 'blank', '_self', '_parent', '_top' ) ) ) {
					$val = '';
				} elseif ( 'blank' === $val ) {
					$val = '_blank';
				}
				break;
		}

		// Add attribute to output if value exists or is a string equal to 0.
		if ( $val || '0' === $val ) {

			switch ( $key ) {

				// Attributes that don't have values.
				case 'download':

					$safe_attr = preg_replace( '/[^a-z0-9_\-]/', '', $val );
					$output .= ' ' . trim( $safe_attr ); // Used for example on total button download attribute.

					break;

				// Attributes with values.
				default:

					$needle = ( 'data' === $key ) ? 'data-' : esc_attr( $key ) . '=';

					// Tag is already included in the value.
					if ( strpos( $val, $needle ) !== false ) {
						$output .= ' ' . trim( wp_strip_all_tags( $val ) );
					}

					// Tag not included in the value.
					else {
						$safe_attr = preg_replace( '/[^a-z0-9_\-]/', '', $key );
						$output .= ' ' . trim( $safe_attr ) . '="' . esc_attr( trim( $val ) ) . '"';
					}

					break;
			}

		}

		// Items with empty vals.
		else {

			// Empty alts are allowed.
			if ( 'alt' === $key ) {
				$output .= ' alt=""';
			}

			// Empty data attributes.
			elseif ( strpos( $key, 'data-' ) !== false ) {
				$safe_attr = preg_replace( '/[^a-z0-9_\-]/', '', $key );
				$output .= ' ' . trim( $safe_attr );
			}

		}

	} // end loop.

	// Return output.
	return trim( $output );


}

/*-------------------------------------------------------------------------------*/
/* [ Lightbox Data ]
/*-------------------------------------------------------------------------------*/

/**
 * Parses the inline gallery lightbox html.
 */
function vcex_parse_inline_lightbox_gallery( $attachements = '' ) {
	if ( function_exists( 'wpex_parse_inline_lightbox_gallery' ) ) {
		return wpex_parse_inline_lightbox_gallery( $attachements );
	}
}

/**
 * Parses lightbox dimensions.
 */
function vcex_parse_lightbox_dims( $dims = '', $return = '' ) {
	if ( ! $dims ) {
		return;
	}

	// Parse data.
	$dims = explode( 'x', $dims );
	$w    = isset( $dims[0] ) ? absint( $dims[0] ) : null;
	$h    = isset( $dims[1] ) ? absint( $dims[1] ) : null;

	// Width and height required.
	if ( ! $w || ! $h ) {
		return;
	}

	switch ( $return ) {
		case 'width' :
			return $w;
			break;
		case 'height' :
			return $h;
			break;
		case 'array' :
			return array(
				'width'  => $w,
				'height' => $h,
			);
			break;
		default :
			return 'width:' . esc_attr( $w ) . ',height:' . esc_attr( $h ); // old deprecated return.
			break;
	}

}

/*-------------------------------------------------------------------------------*/
/* [ Classnames ]
/*-------------------------------------------------------------------------------*/

/**
 * Parses the wpbakery custom css filter tag.
 */
function vcex_parse_shortcode_classes( $class = '', $shortcode_base = '', $shortcode_attributes = '' ) {
	if ( is_array( $class ) ) {
		$class = array_filter( $class );
		$class = trim( implode( ' ', $class ) );
	}

	if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) ) {
		/**
		 * Filters the shortcode class when WPBakery is active.
		 *
		 * @param string $class
		 * @param string $shortcode_base
		 * @param array $shortcode_attributes
		 */
		$class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class, $shortcode_base, $shortcode_attributes );
	}

	/**
	 * Filters the shortcode class.
	 *
	 * @param string $class
	 * @param string $shortcode_base
	 * @param array $shortcode_attributes
	 */
	$class = apply_filters( 'vcex_shortcodes_css_class', $class, $shortcode_base, $shortcode_attributes );

	return $class;
}

/**
 * Parses text_align class.
 */
function vcex_parse_text_align_class( $align = '' ) {
	if ( ! $align ) {
		return;
	}
	switch ( $align ) {
		case 'left':
			return 'wpex-text-left';
			break;
		case 'center':
			return 'wpex-text-center';
			break;
		case 'right':
			return 'wpex-text-right';
			break;
	}
}

/**
 * Parses justify_content class.
 */
function vcex_parse_justify_content_class( $position = '', $prefix = '' ) {

	if ( ! $position ) {
		return;
	}

	$justify_class = '';

	switch ( $position ) {
		case 'left':
		case 'start':
			$justify_class = 'justify-start';
			break;
		case 'center':
			$justify_class = 'justify-center';
			break;
		case 'right':
		case 'end':
			$justify_class = 'justify-end';
			break;
		case 'between':
		case 'space-between':
			$justify_class = 'justify-between';
			break;
		case 'around':
		case 'space-around':
			$justify_class = 'justify-around';
			break;
		case 'evenly':
		case 'space-evenly':
			$justify_class = 'justify-evenly';
			break;
	}

	if ( ! $justify_class ) {
		return;
	}

	if ( $prefix ) {
		return 'wpex-' . sanitize_html_class( $prefix ) . '-' . sanitize_html_class( $justify_class );
	} else {
		return 'wpex-' . sanitize_html_class( $justify_class );
	}

}

/**
 * Parses align items class.
 */
function vcex_parse_align_items_class( $align = '', $prefix = '' ) {
	if ( ! $align ) {
		return;
	}

	$align_class = '';

	switch ( $align ) {
		case 'left':
		case 'start':
		case 'flex-start':
			$align_class = 'items-start';
			break;
		case 'middle':
		case 'center':
			$align_class = 'items-center';
			break;
		case 'right':
		case 'end':
		case 'flex-end':
			$align_class = 'items-end';
			break;
		case 'stretch':
			$align_class = 'items-stretch';
			break;
		case 'baseline':
			$align_class = 'items-baseline';
			break;
	}

	if ( ! $align_class ) {
		return;
	}

	if ( $prefix ) {
		return 'wpex-' . sanitize_html_class( $prefix ) . '-' . sanitize_html_class( $align_class );
	} else {
		return 'wpex-' . sanitize_html_class( $align_class );
	}

}

/**
 * Parses font_size class.
 */
function vcex_parse_font_size_class( $size = '' ) {
	if ( $size && function_exists( 'wpex_sanitize_utl_font_size' ) ) {
		return wpex_sanitize_utl_font_size( $size );
	}
}

/**
 * Parses visibility class.
 */
function vcex_parse_visibility_class( $class = '' ) {
	if ( function_exists( 'wpex_visibility_class' ) ) {
		return wpex_visibility_class( $class );
	}
	return sanitize_html_class( $class );
}

/**
 * Parses padding class.
 */
function vcex_parse_padding_class( $padding = '', $sides = 'all' ) {
	if ( ! $padding || ! in_array( $padding, vcex_padding_choices() ) ) {
		return;
	}

	switch ( $sides ) {
		case 'y':
			$prefix = 'wpex-py-';
			break;
		case 'x':
			$prefix = 'wpex-px-';
			break;
		case 'top':
			$prefix = 'wpex-pt-';
			break;
		case 'bottom':
			$prefix = 'wpex-pb-';
			break;
		case 'left':
			$prefix = 'wpex-pl-';
			break;
		case 'right':
			$prefix = 'wpex-pr-';
			break;
		case 'all':
		default:
			$prefix = 'wpex-p-';
	}

	return sanitize_html_class( $prefix . absint( $padding ) );

}

/**
 * Parses shadow class.
 */
function vcex_parse_shadow_class( $shadow = '', $prefix = '' ) {
	if ( $shadow && in_array( $shadow, vcex_shadow_choices() ) ) {
		if ( $prefix ) {
			return 'wpex-' . sanitize_html_class( $prefix ) . '-' . sanitize_html_class( $shadow );
		} else {
			return 'wpex-' . sanitize_html_class( $shadow );
		}
	}
}

/**
 * Parses border_radius class.
 */
function vcex_parse_border_radius_class( $border_radius = '' ) {
	if ( $border_radius && 'none' !== $border_radius && in_array( $border_radius, vcex_border_radius_choices() ) ) {
		return 'wpex-' . sanitize_html_class( $border_radius );
	}
}

/**
 * Parses border_width class.
 */
function vcex_parse_border_width_class( $border_width = '', $sides = 'all' ) {
	if ( ! $border_width || ! in_array( $border_width, vcex_border_width_choices() ) ) {
		return;
	}

	$border_width = absint( $border_width );

	if ( 1 === $border_width ) {
		return 'wpex-border';
	}

	switch ( $sides ) {
		case 'top':
			$prefix = 'wpex-border-t-';
			break;
		case 'bottom':
			$prefix = 'wpex-border-b-';
			break;
		case 'left':
			$prefix = 'wpex-border-l-';
			break;
		case 'right':
			$prefix = 'wpex-border-r-';
			break;
		case 'all':
		default:
			$prefix = 'wpex-border-';
	}

	return sanitize_html_class( $prefix . $border_width );

}

/**
 * Parses border_radius class.
 */
function vcex_parse_border_style_class( $border_style = '' ) {
	if ( $border_style && in_array( $border_style, vcex_border_style_choices() ) ) {
		return 'wpex-border-' . sanitize_html_class( $border_style );
	}
}

/**
 * Parses margin class.
 */
function vcex_parse_margin_class( $margin = '', $sides = 'all' ) {

	if ( ! $margin || ! in_array( $margin, vcex_margin_choices() ) ) {
		return;
	}

	if ( 0 === strpos( $sides, 'wpex-m' ) ) {
		$prefix = $sides; // fallback added in v1.2.8
	} else {
		switch ( $sides ) {
			case 'top':
				$prefix = 'wpex-mt-';
				break;
			case 'bottom':
				$prefix = 'wpex-mb-';
				break;
			case 'left':
				$prefix = 'wpex-ml-';
				break;
			case 'right':
				$prefix = 'wpex-mr-';
				break;
			case 'x':
				$prefix = 'wpex-mx-';
				break;
			case 'y':
				$prefix = 'wpex-my-';
				break;
			case 'all':
			default:
				$prefix = 'wpex-m-';
		}
	}

	return sanitize_html_class( $prefix . absint( $margin ) );

}

/**
 * Parses font weight class.
 */
function vcex_parse_font_weight_class( $font_weight = '' ) {

	$choices = array(
		'normal',
		'semibold',
		'bold',
		'bolder',
		'100',
		'200',
		'300',
		'400',
		'500',
		'600',
		'700',
		'800',
		'900',
	);

	if ( ! $font_weight || ! in_array( $font_weight, $choices ) ) {
		return;
	}

	switch ( $font_weight ) {
		case '100':
			$font_weight = 'hairline';
			break;
		case '200':
			$font_weight = 'thin';
			break;
		case '300':
			$font_weight = 'light';
			break;
		case '400':
			$font_weight = 'normal';
			break;
		case '500':
			$font_weight = 'medium';
			break;
		case '600':
			$font_weight = 'semibold';
			break;
		case '700':
			$font_weight = 'bold';
			break;
		case '800':
			$font_weight = 'extrabold';
			break;
		case '900':
			$font_weight = 'black';
			break;
	}

	return sanitize_html_class( 'wpex-font-' . $font_weight );

}

/*-------------------------------------------------------------------------------*/
/* [ Old Params ]
/*-------------------------------------------------------------------------------*/

/**
 * Parses icon parameter to make sure the icon & icon_type is set properly
 */
function vcex_parse_icon_param( $atts, $icon_param = 'icon', $icon_type_param = 'icon_type' ) {
	$icon = ! empty( $atts[$icon_param] ) ? $atts[$icon_param] : '';
	if ( $icon && empty( $atts[$icon_type_param] ) ) {
		$get_icon_type = vcex_get_icon_type_from_class( $icon );
		$atts[$icon_type_param] = ( 'ticons' == $get_icon_type ) ? '' : $get_icon_type;
		if ( 'fontawesome' === $get_icon_type ) {
			$atts[$icon_param . '_fontawesome'] = $icon;
		} elseif ( 'ticons' === $get_icon_type ) {
			$atts[$icon_param] = str_replace( 'fa fa-', 'ticon ticon-', $icon );
		} elseif ( ! $get_icon_type ) {
			$atts[$icon_param] = vcex_add_default_icon_prefix( $icon );
		}
	}
	return $atts;
}

/**
 * Sets the default image size to "full" if it's set to custom but img height and width are empty.
 *
 * @deprecated 1.1
 * @todo remove completely.
 */
function vcex_parse_image_size( $atts ) {
	$img_size = ( isset( $atts['img_size'] ) && 'wpex_custom' == $atts['img_size'] ) ? 'wpex_custom' : '';
	$img_size = empty( $atts['img_size'] ) ? 'wpex_custom' : '';
	if ( 'wpex_custom' == $img_size && empty( $atts['img_height'] ) && empty( $atts['img_width'] ) ) {
		$atts['img_size'] = 'full';
	}
	return $atts;
}

/**
 * Parses old content CSS params.
 *
 * IMPORTANT: For this to work there MUST be space between : and val in the CSS !!!
 */
function vcex_parse_deprecated_grid_entry_content_css( $atts ) {

	if ( empty( $atts['content_css'] ) ) {

		// Define css var.
		$css = '';

		// Background Color.
		if ( ! empty( $atts['content_background'] ) ) {
			$css .= 'background-color: ' . $atts['content_background'] . ';';
		}

		// Border.
		if ( ! empty( $atts['content_border'] ) ) {
			$border = $atts['content_border'];
			if ( '0px' == $border || 'none' == $border ) {
				$css .= 'border: 0px none rgba(255,255,255,0.01);'; // reset border
			} else {
				$css .= 'border: ' . $border . ';';
			}
		}

		// Padding.
		if ( ! empty( $atts['content_padding'] ) ) {
			$css .= 'padding: ' . $atts['content_padding'] . ';';
		}

		// Margin.
		if ( ! empty( $atts['content_margin'] ) ) {
			$css .= 'margin: ' . $atts['content_margin'] . ';';
		}

		// Update css var.
		if ( $css ) {
			$atts['content_css'] = '.temp{' . wp_strip_all_tags( $css ) . '}';
		}

		// Unset old vars.
		unset( $atts['content_background'] );
		unset( $atts['content_padding'] );
		unset( $atts['content_margin'] );
		unset( $atts['content_border'] );

	}

	return $atts;

}