<?php
/**
 * vcex_toggle shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_toggle', $atts ) ) {
	return;
}

$atts = vcex_shortcode_atts( 'vcex_toggle', $atts, get_class() );

if ( empty( $content ) || empty( $atts['heading'] ) ) {
	return;
}

$is_open = ( 'open' === $atts['state'] );

$aria_expanded = $is_open ? 'true' : 'false';

$faq_microdata = ( 'true' === $atts['faq_microdata'] );

// Define unique content ID.
if ( ! empty( $atts['content_id'] ) ) {
	$content_id = trim( sanitize_title( $atts['content_id'] ) );
} elseif ( $faq_microdata ) {
	$content_id = trim( sanitize_title( $atts['heading'] ) );
} else {
	$content_id = uniqid( 'vcex_' );
}

// Define element classes.
$shortcode_class = array(
	'vcex-module',
	'vcex-toggle',
);

if ( $is_open ) {
	$shortcode_class[] = 'vcex-toggle--active';
}

$extra_classes = vcex_get_shortcode_extra_classes( $atts, 'vcex_toggle' );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, 'vcex_toggle', $atts );

$shortcode_style = vcex_inline_style( array(
	'animation_delay' => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
), false );

$animate = ( isset( $atts['animate'] ) && 'false' == $atts['animate'] ) ? 'false' : 'true';

$shortcode_html_attrs = array(
	'class'         => trim( $shortcode_class ),
	'style'         => $shortcode_style,
	'data-animate'  => esc_attr( $animate ),
	'data-duration' => '300',
);

if ( $faq_microdata ) {
	$shortcode_html_attrs['itemscope'] = 'itemscope';
	$shortcode_html_attrs['itemprop']  = 'mainEntity';
	$shortcode_html_attrs['itemtype']  = 'https://schema.org/Question';
}

$output = '<div' . vcex_parse_html_attributes( $shortcode_html_attrs ) . '>';

	// Heading
	$heading_class = 'vcex-toggle__heading';

	// Heading css.
	$heading_css = vcex_inline_style( array(
		'font_size' => $atts['heading_font_size'],
		'font_weight' => $atts['heading_font_weight'],
		'font_family' => $atts['heading_font_family'],
	) );

	// Heading responsive css.
	$heading_uniqid = vcex_element_unique_classname();

	$responsive_css = vcex_element_responsive_css( array(
		'font_size' => $atts['heading_font_size'],
	), $heading_uniqid );

	if ( $responsive_css ) {
		$heading_class .= ' ' . $heading_uniqid;
		$output .= '<style>' . $responsive_css . '</style>';
	}

	$output .= '<div class="' . esc_attr( $heading_class ) . '"' . $heading_css . '>';

		// Trigger
		$trigger_class = 'vcex-toggle__trigger wpex-flex wpex-items-center wpex-transition-colors wpex-duration-200';

		if ( 'right' === $atts['icon_position'] ) {
			$trigger_class .= ' wpex-flex-row-reverse';
		}

		$trigger_css = vcex_inline_style( array(
			'color' => $atts['heading_color'],
		) );

		// Trigger hover color
		$hover_data = array();

		if ( $atts['heading_color_hover'] ) {
			$hover_data['color'] = esc_attr( vcex_parse_color( $atts['heading_color_hover'] ) );
		}

		if ( $hover_data ) {
			$hover_data = ' data-wpex-hover="' . htmlspecialchars( wp_json_encode( $hover_data ) ) . '"';
		} else {
			$hover_data = '';
		}

		$output .= '<a href="#' . esc_attr( $content_id ) . '" class="' . esc_attr( $trigger_class ) . '"' . $trigger_css . $hover_data . ' aria-expanded="' . esc_attr( $aria_expanded ) . '" aria-controls="' . esc_attr( $content_id ) . '">';

			// Icon
			$icon_class = 'vcex-toggle__icon';

			$icon_spacing = $atts['icon_spacing'] ? absint( $atts['icon_spacing'] ) : '10';

			if ( 'right' === $atts['icon_position'] ) {
				$icon_class .= ' wpex-ml-' . sanitize_html_class( $icon_spacing );
			} else {
				$icon_class .= ' wpex-mr-' . sanitize_html_class( $icon_spacing );
			}

			$icon_size = '1.5em';

			if ( $atts['icon_size'] ) {
				$icon_size = $atts['icon_size'];
				if ( is_numeric( $icon_size ) ) {
					$icon_size = $icon_size . 'px';
				} else {
					$icon_size_unit = preg_replace( '/[^0-9.]/', '', $icon_size );
					$icon_size_unit = trim( str_replace( $icon_size_unit, '', $icon_size ) );
					$allowed_units = array( 'px', 'em', 'rem', 'vw', 'vmin', 'vmax', 'vh' );
					if ( in_array( $icon_size_unit, $allowed_units ) ) {
						$icon_size = esc_attr( $icon_size );
					} else {
						$icon_size = abs( floatval( $icon_size ) ) . 'px';
					}
				}
			}

			$icon_css = vcex_inline_style( array(
				'color' => $atts['icon_color'],
			) );

			$output .= '<div class="' . esc_attr( $icon_class ) . '" aria-hidden="true"' . $icon_css . '>';

				$icon_type = $atts['icon_type'] ?: 'plus';

				// Open Icon
				$output .= '<div class="vcex-toggle__icon-open wpex-flex wpex-flex-col wpex-items-center">';

					switch ( $icon_type ) {
						case 'angle':
							// Angle down
							$output .= '<svg xmlns="http://www.w3.org/2000/svg" height="' . esc_attr( $icon_size ) . '" viewBox="0 0 24 24" width="' . esc_attr( $icon_size ) . '" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M24 24H0V0h24v24z" fill="none" opacity=".87"/><path d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6-1.41-1.41z"/></svg>';
							break;
						case 'plus':
						default:
							// Plus
							$output .= '<svg xmlns="http://www.w3.org/2000/svg" height="' . esc_attr( $icon_size ) . '" viewBox="0 0 24 24" width="' . esc_attr( $icon_size ) . '" fill="currentColor" class="vcex-toggle__icon-open"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>';
							break;
					}

				$output .= '</div>';

				// Close icon
				$output .= '<div class="vcex-toggle__icon-close wpex-flex wpex-flex-col wpex-items-center">';

					switch ( $icon_type ) {
					case 'angle':
						// Angle Up
						$output .= '<svg xmlns="http://www.w3.org/2000/svg" height="' . esc_attr( $icon_size ) . '" viewBox="0 0 24 24" width="' . esc_attr( $icon_size ) . '" fill="currentColor" class="vcex-toggle__icon-close"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 8l-6 6 1.41 1.41L12 10.83l4.59 4.58L18 14l-6-6z"/></svg>';
						break;
					case 'plus':
					default:
						// Minus
						$output .= '<svg xmlns="http://www.w3.org/2000/svg" height="' . esc_attr( $icon_size ) . '" viewBox="0 0 24 24" width="' . esc_attr( $icon_size ) . '" fill="currentColor" class="vcex-toggle__icon-close"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 13H5v-2h14v2z"/></svg>';
						break;
				}

				$output .= '</div>';

			$output .= '</div>';

			// Title
			$heading_tag_escaped = tag_escape( $atts['heading_tag'] );
			$heading_class = 'vcex-toggle__title';

			if ( 'right' === $atts['icon_position'] ) {
				$heading_class .= ' wpex-mr-auto';
			}

			$output .= '<' . $heading_tag_escaped . ' class="' . esc_attr( $heading_class ) . '"';

				if ( $faq_microdata ) {
					$output .= ' itemprop="name"';
				}

			$output .= '>';

				$output .= do_shortcode( wp_kses_post( $atts['heading'] ) );

			$output .= '</' . $heading_tag_escaped . '>';

		$output .= '</a>';

	$output .= '</div>'; // heading close

	// Content
	$content_class = 'vcex-toggle__content wpex-last-mb-0 wpex-my-10 wpex-clr';

	if ( $atts['animation_speed'] ) {
		$content_class .= ' wpex-duration-' . sanitize_html_class( absint( $atts['animation_speed'] ) );
	}

	$content_css = vcex_inline_style( array(
		'font_size' => $atts['content_font_size'],
		'color' => $atts['content_color'],
	) );

	// Content responsive css.
	$content_uniqid = vcex_element_unique_classname();

	$responsive_css = vcex_element_responsive_css( array(
		'font_size' => $atts['content_font_size'],
	), $content_uniqid );

	if ( $responsive_css ) {
		$content_class .= ' ' . $content_uniqid;
		$output .= '<style>' . $responsive_css . '</style>';
	}

	$output .= '<div id="' . esc_attr( $content_id ) . '" class="' . esc_attr( $content_class ) . '"';

		if ( $content_css ) {
			$output .= $content_css;
		}

		if ( $faq_microdata ) {
			$output .= ' itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"';
		}

	$output .= '>';

		if ( $faq_microdata ) {
			$output .= '<div itemprop="text">';
		}

			$output .= wpautop( do_shortcode( wp_kses_post( $content ) ) );

		if ( $faq_microdata ) {
			$output .= '</div>';
		}

	$output .= '</div>';

$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;