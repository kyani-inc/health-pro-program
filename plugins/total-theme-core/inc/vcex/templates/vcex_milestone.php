<?php
/**
 * vcex_milestone shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_milestone', $atts ) ) {
	return;
}

// Get and extract shortcode attributes
$atts = vcex_shortcode_atts( 'vcex_milestone', $atts, $this );
extract( $atts );

// Define vars & define defaults.
$output = '';

// Milestone default args.
extract( apply_filters( 'vcex_milestone_settings', array(
	'separator' => $separator,
	'decimal'   => $decimal_separator,
) ) );

// Sanitize data.
if ( is_callable( $number ) ) {
	$number = intval( call_user_func( $number ) );
} else {
	$number = isset( $number ) ? do_shortcode( $number ) : '45';
}
$number = str_replace( ',', '', $number );
//$number = str_replace( '.', '', $number );

// Sanitize speed
if ( $speed = intval( $speed ) ) {
	$speed = $speed/1000; // turn into seconds
}

// Wrapper Classes
$wrap_class = array(
	'vcex-module',
	'vcex-milestone',
);

switch ( $style ) {
	case 'boxed':
		$wrap_class[] = 'wpex-boxed';
		break;
	case 'bordered':
		$wrap_class[] = 'wpex-bordered';
		break;
}

if ( $atts['hover_animation'] ) {
	$wrap_class[] = vcex_hover_animation_class( $atts['hover_animation'] );
	vcex_enque_style( 'hover-animations' );
} elseif ( $atts['shadow_hover'] ) {
	$wrap_class[] = 'wpex-transition-shadow';
	$wrap_class[] = 'wpex-duration-300';
}

// Wrap style.
$wrap_style = vcex_inline_style( array(
	'border_radius'    => $atts['border_radius'],
	'background_color' => $atts['background_color'],
	'border_color'     => $atts['border_color'],
) );

// Generate Icon if enabled.
if ( 'true' == $enable_icon ) {

	$icon_position = $icon_position ?: 'inline'; // default placement

	$wrap_class[] = 'vcex-ip-' . sanitize_html_class( $icon_position ); // add placement classname

	$icon_classes = array(
		'vcex-milestone-icon',
	);

	$icon_spacing = $icon_spacing ? absint( $icon_spacing ) : '15';

	switch ( $icon_position ) {
		case 'inline':
			$icon_tag = 'span';
			$icon_classes[] = 'wpex-mr-' . sanitize_html_class( $icon_spacing );
			$icon_classes[] = 'wpex-inline-block';
			break;
		case 'top':
			$icon_tag = 'span';
			if ( ! $icon_color ) {
				$icon_classes[] = 'wpex-text-gray-400';
			}
			$icon_classes[] = 'wpex-inline-block';
			$icon_classes[] = 'wpex-leading-none';
			$icon_classes[] = 'wpex-mb-' . sanitize_html_class( $icon_spacing );
			break;
		case 'left':
			$icon_tag = 'div';
			if ( ! $icon_color ) {
				$icon_classes[] = 'wpex-text-gray-400';
			}
			$icon_classes[] = 'wpex-leading-none';
			$icon_classes[] = 'wpex-mr-' . sanitize_html_class( $icon_spacing );
			break;
		case 'right':
			$icon_tag = 'div';
			if ( ! $icon_color ) {
				$icon_classes[] = 'wpex-text-gray-400';
			}
			$icon_classes[] = 'wpex-leading-none';
			$icon_classes[] = 'wpex-ml-' . sanitize_html_class( $icon_spacing );
			break;
		default:
			$icon_tag = 'span';
			break;
	}

	$icon_attrs = array(
		'class' => $icon_classes,
	);

	$icon_attrs['style'] = vcex_inline_style( array(
		'color'     => $icon_color,
		'font_size' => $icon_size,
	), false );

	$icon_tag_escaped = tag_escape( $icon_tag );

	$icon_html = '<' . $icon_tag_escaped . vcex_parse_html_attributes( $icon_attrs ) . '>';

		if ( $icon_alternative_classes ) {

			$icon_html .= '<span class="' . esc_attr( do_shortcode( $icon_alternative_classes ) ) . '"></span>';

		} elseif ( $icon = vcex_get_icon_class( $atts, 'icon' ) ) {

			vcex_enqueue_icon_font( $icon_type, $icon );

			$icon_html .= '<span class="' . esc_attr( $icon ) . '" aria-hidden="true"></span>';

		}

	$icon_html .= '</' . $icon_tag_escaped . '>';

}

// Define URL attributes.
if ( $url ) {

	$url_classes = array(
		'wpex-inherit-color',
	);

	if ( 'true' == $atts['url_wrap'] ) {

		$url_classes[] = 'wpex-no-underline';

		if ( $visibility ) {
			$url_classes[] = vcex_parse_visibility_class( $visibility );
		}

	}

	$url_attrs = array(
		'href'   => esc_url( do_shortcode( $url ) ),
		'rel'    => $url_rel,
		'target' => $url_target,
		'class'  => $url_classes,
	);

}

// Check if the milestone is animated.
if ( 'true' == $animated || 'yes' === $animated ) {
	$this->enqueue_scripts();
}

// Get extra classes.
$extra_classes = vcex_get_shortcode_extra_classes( $atts, 'vcex_milestone' );

if ( $extra_classes ) {
	$wrap_class = array_merge( $wrap_class, $extra_classes );
}

// Parse wrap classes.
$wrap_class[] = 'wpex-clr';
$wrap_class = vcex_parse_shortcode_classes( $wrap_class, 'vcex_milestone', $atts );

/*--------------------------------*/
/* [ Begin Output ]
/*--------------------------------*/

// Open css_animation element (added in it's own element to prevent conflicts with inner styling).
if ( $atts['css_animation'] && 'none' !== $atts['css_animation'] ) {

	$animation_classes = array( trim( vcex_get_css_animation( $atts['css_animation'] ) ) );

	if ( $visibility ) {
		$animation_classes[] = vcex_parse_visibility_class( $visibility );
	}

	$animation_style = vcex_inline_style( array(
		'animation_delay'    => $atts['animation_delay'],
		'animation_duration' => $atts['animation_duration'],
	) );

	$output .= '<div class="' . esc_attr( implode( ' ', $animation_classes ) ) . '"' . $animation_style . '>';

}

// Open width wrap (use new wrapper to prevent issues with links being clickable inside white space).
if ( $atts['width'] ) {

	$width_class = 'vcex-width--milestone wpex-max-w-100';

	$width_style = vcex_inline_style( array(
		'width' => $atts['width'],
	) );

	if ( $visibility ) {
		$width_class .= ' ' . vcex_parse_visibility_class( $visibility );
	}

	switch ( $atts['float'] ) {
		case 'left':
			$width_class .= ' wpex-float-left';
			break;
		case 'right':
			$width_class .= ' wpex-float-right';
			break;
		case 'center':
		default:
			$width_class .= ' wpex-mx-auto';
			break;
	}

	$output .= '<div class="' . $width_class . '"' . $width_style . '>';
}

// Open link wrap.
if ( 'true' == $atts['url_wrap'] && $url ) {
	$output .= '<a ' . trim( vcex_parse_html_attributes( $url_attrs ) ) . '>';
}

// Open main div wrapper.
$output .= '<div class="' . esc_attr( $wrap_class ) . '"'
		. vcex_get_unique_id( $unique_id )
		. $wrap_style;
$output .= '>';

	// Open Inner wrapper.
	$inner_classes = array(
		'vcex-milestone-inner',
		'wpex-inline-block',
	);

	$output .= '<div class="' . esc_attr( implode( ' ', $inner_classes ) ) . '">';

		// Open flex container for left/right positioned icons.
		if ( 'left' === $icon_position || 'right' === $icon_position ) {

			$flex_classes = array(
				'wpex-flex',
				'wpex-items-center'
			);

			if ( 'right' === $icon_position ) {
				$flex_classes[] = 'wpex-flex-row-reverse';
			}

			$output .= '<div class="' . esc_attr( implode( ' ', $flex_classes ) ) . '">';
		}

		// Add icon for top/left/right positions.
		if ( ! empty( $icon_html ) && in_array( $icon_position, array( 'top', 'left', 'right' ) ) ) {
			$output .= $icon_html;
		}

		// Desc classes.
		$desc_classes = array( 'vcex-milestone-desc' );

		// Open description element.
		$output .= '<div class="' . esc_attr( implode( ' ', $desc_classes ) ) . '">';

			// Number classes.
			$number_classes = array(
				'vcex-milestone-number',
				'wpex-leading-none',
			);

			if ( ! $number_weight ) {
				$number_classes[] = 'wpex-font-semibold';
			}

			if ( ! $number_color ) {
				$number_classes[] = 'wpex-text-gray-400';
			}

			$number_classes = (array) apply_filters( 'vcex_milestone_number_class', $number_classes );

			// Number Style
			$number_style = vcex_inline_style( array(
				'color'         => $number_color,
				'font_size'     => $number_size,
				'margin_bottom' => $number_bottom_margin,
				'font_weight'   => $number_weight,
				'font_family'   => $number_font_family,
			) );

			// Display number.
			$output .= '<div class="' . esc_attr( implode( ' ', $number_classes ) ) . '"' . $number_style . '>';

				if ( $before || 'true' == $enable_icon ) {

					$output .= '<span class="vcex-milestone-before">';

						if ( ! empty( $icon_html ) && 'inline' == $icon_position ) {
							$output .= $icon_html;
						}

						$output .= esc_html( do_shortcode( $before ) );

					$output .= '</span>';

				}

				// Get milestone js options.
				$startval = floatval( do_shortcode( $startval ) );
				$startval = $startval ?: 0;

				$settings = array(
					'startVal'        => $startval,
					'endVal'          => floatval( do_shortcode( $number ) ),
					'duration'        => $speed ?: 2.5,
					'decimals'        => intval( $decimals ),
					'separator'       => wp_strip_all_tags( $separator ),
					'decimal'         => wp_strip_all_tags( $decimal ),
					'animateOnScroll' => vcex_validate_boolean( $atts['animate_onscroll'] ),
				);

				// Output milestone number.
				if ( 'true' === $animated || 'yes' === $animated ) {

					$output .= '<span class="vcex-milestone-time vcex-countup" data-options="' . htmlspecialchars( wp_json_encode( $settings ) ) . '">' . $startval . '</span>';

				} else {

					$output .= '<span class="vcex-milestone-time">' . esc_html( do_shortcode( $number ) ) . '</span>';

				}

				// Display after text if defined.
				if ( $after ) {

					$output .= '<span class="vcex-milestone-after">' . esc_html( do_shortcode( $after ) ) . '</span>';

				}

			// Close number/after container.
			$output .= '</div>';

			// Display caption.
			if ( ! empty( $caption ) ) {

				// Caption classes.
				$caption_classes = array(
					'vcex-milestone-caption',
					'wpex-mt-5',
				);

				if ( ! $atts['caption_font'] ) {
					$caption_classes[] = 'wpex-font-light';
				}

				if ( ! $atts['caption_size'] ) {
					$caption_classes[] = 'wpex-text-lg';
				}

				if ( ! $atts['caption_color'] ) {
					$caption_classes[] = 'wpex-text-gray-500';
				}

				$caption_classes = (array) apply_filters( 'vcex_milestone_caption_class', $caption_classes );

				// Load custom font.
				if ( $atts['caption_font_family'] ) {
					vcex_enqueue_google_font( $atts['caption_font_family'] );
				}

				// Caption Style.
				$caption_style = vcex_inline_style( array(
					'font_family' => $atts['caption_font_family'],
					'color'       => $atts['caption_color'],
					'font_size'   => $atts['caption_size'],
					'font_weight' => $atts['caption_font'],
				) );

				// Display caption.
				$output .= '<div class="' . esc_attr( implode( ' ', $caption_classes ) ) . '"' . $caption_style . '>';

					// Open link around caption
					if ( $url && 'false' == $atts['url_wrap'] ) {
						$output .= '<a ' . trim( vcex_parse_html_attributes( $url_attrs ) ) . '>';
					}

					// Caption text.
					$output .= do_shortcode( wp_kses_post( $caption ) );

					// Close caption link.
					if ( $url && 'false' == $atts['url_wrap'] ) {
						$output .= '</a>';
					}

				$output .= '</div>';

			} // end caption.

		$output .= '</div>'; // end desc.

		// Close flex container.
		if ( 'left' === $atts['icon_position'] || 'right' === $atts['icon_position'] ) {
			$output .= '</div>';
		}

	$output .= '</div>';

$output .= '</div>';

// Close link wrap.
if ( 'true' == $atts['url_wrap'] && $url ) {
	$output .= '</a>';
}

// Clear floats.
if ( $atts['width'] ) {
	$output .= '</div>';
	if ( 'center' !== $atts['float'] ) {
		$output .= '<div class="vcex-clear--milestone wpex-clear"></div>';
	}
}

// Close animation.
if ( $atts['css_animation'] && 'none' !== $atts['css_animation'] ) {
	$output .= '</div>';
}

// @codingStandardsIgnoreLine
echo $output;