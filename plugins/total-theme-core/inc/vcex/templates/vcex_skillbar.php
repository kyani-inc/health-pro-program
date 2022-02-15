<?php
/**
 * vcex_skillbar shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_skillbar', $atts ) ) {
	return;
}

// Get and extract shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_skillbar', $atts, $this );
extract( $atts );

// Define & sanitize vars.
$style = $style ?: 'default';
$title_position = ( 'default' == $style ) ? 'inside' : 'outside';
$border_radius_class = vcex_parse_border_radius_class( $border_radius );

// Define output var.
$output = '';

// Get percentage based on source.
$source = $source ?: 'custom';
if ( 'custom' !== $source ) {
	$percentage = vcex_get_source_value( $source, $atts );
}

// Allow shortcodes for percentage.
$percentage = do_shortcode( $percentage );

if ( 'custom' !== $source && empty( $percentage ) ) {
	return;
}

// Classes.
$shortcode_class = array(
	'vcex-module',
	'vcex-skillbar-wrap',
	'wpex-mb-10',
);

$shortcode_class[] = 'vcex-skillbar-style-' . sanitize_html_class( $style );

if ( $visibility ) {
    $shortcode_class[] = vcex_parse_visibility_class( $visibility );
}

if ( $css_animation_class = vcex_get_css_animation( $css_animation ) ) {
	$shortcode_class[] = $css_animation_class;
}

if ( $bottom_margin_class = vcex_sanitize_margin_class( $bottom_margin, 'wpex-mb-' ) ) {
	$shortcode_class[] = $bottom_margin_class;
}

if ( $el_class = vcex_get_extra_class( $classes ) ) {
	$shortcode_class[] = $el_class;
}

$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, 'vcex_skillbar', $atts );

$shortcode_style = vcex_inline_style( array(
	'animation_delay' => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
) );

// Start shortcode output.
$output .= '<div class="' . esc_attr( $shortcode_class ) . '"' . vcex_get_unique_id( $unique_id ) . $shortcode_style . '>';

	// Generate icon output if defined.
	if ( vcex_validate_boolean( $show_icon ) ) {

		$icon = vcex_get_icon_class( $atts, 'icon' );

		if ( $icon ) {

			vcex_enqueue_icon_font( $icon_type, $icon );

			$icon_class = 'vcex-icon-wrap';
			$icon_margin = $icon_margin ?: 10;

			if ( $icon_margin ) {
				$icon_class .= ' wpex-mr-' . sanitize_html_class( absint( $icon_margin ) );
			}

			$icon_output = '<span class="' . esc_attr( $icon_class ) . '">';

				$icon_output .= '<span class="' . esc_attr( $icon ) . '" aria-hidden="true"></span>';

			$icon_output .= '</span>';

		}
	}

	// Generate percent output.
	if ( vcex_validate_boolean( $show_percent ) ) {

		$percentage_class = array(
			'vcex-skill-bar-percent',
			'wpex-absolute',
			'wpex-right-0',
		);

		switch ( $title_position ) {
			case 'inside':
				$percentage_class[] = 'wpex-mr-15';
				break;
			case 'outside':
				$percentage_class[] = 'wpex-text-sm';
				$percentage_class[] = 'wpex-top-50';
				$percentage_class[] = '-wpex-translate-y-50';
				$percentage_class[] = 'wpex-mr-10';
				break;
		}

		$percentage_style = vcex_inline_style( array(
			'color' => $percentage_color,
			'font_size' => $percentage_font_size,
		) );

		$percent_output = '<div class="' . esc_attr( implode( ' ', $percentage_class ) ) . '"' . $percentage_style . '>' . intval( $percentage ) . '&#37;</div>';

	}

	/*
	 * Title (outside of skillbar).
	 */
	if ( 'alt-1' === $style ) {

		$label_class = array(
			'vcex-skillbar-title',
			'wpex-font-semibold',
			'wpex-mb-5',
		);

		$label_style = array();

		$label_style = vcex_inline_style( array(
			'font_size' => $font_size,
			'color'     => $label_color,
		) );

		$output .= '<div class="' . esc_attr( implode( ' ', $label_class ) ) . '"' . $label_style . '>';

			if ( ! empty( $icon_output ) ) {
				$output .= $icon_output;
			}

			$output .= wp_kses_post( do_shortcode( $title ) );

		$output .= '</div>';

	}

	/*
	 * Inner wrap open.
	 *
	 */
	$inner_class = array(
		'vcex-skillbar',
	);

	if ( 'true' == $animate_percent ) {
		$inner_class[] = 'vcex-skillbar--animated';
	}

	$inner_class[] = 'wpex-block';
	$inner_class[] = 'wpex-relative';

	switch ( $title_position ) {
		case 'inside':
			$inner_class[] = 'wpex-bg-gray-100';
			if ( vcex_validate_boolean( $box_shadow ) ) {
		  		$inner_class[] = 'wpex-shadow-inner';
			}
			$inner_class[] = 'wpex-text-white';
			break;
		case 'outside':
			$inner_class[] = 'wpex-bg-gray-200';
			$inner_class[] = 'wpex-text-gray-600';
			$inner_class[] = 'wpex-font-semibold';
			break;
	}

	if ( $border_radius_class ) {
		$inner_class[] = $border_radius_class;
		$inner_class[] = 'wpex-overflow-hidden';
	}

	$inner_style = array(
		'background'     => $background,
		'height_px'      => $container_height,
		'line_height_px' => $container_height,
	);

	if ( 'inside' === $title_position ) {
		$inner_style['font_size'] = $font_size;
	}

	$inner_style = vcex_inline_style( $inner_style, false );

	$inner_attrs = array(
		'class' => $inner_class,
		'style' => $inner_style,
	);

	if ( 'true' == $animate_percent && $percentage )  {
		$this->enqueue_scripts();
		$inner_attrs['data-percent'] = intval( $percentage ) . '&#37;';
		if ( 'true' == $animate_percent_onscroll ) {
			$inner_attrs['data-animate-on-scroll'] = 'true';
		}
	}

	$output .= '<div' . vcex_parse_html_attributes( $inner_attrs ) . '>';

		/*
		 * Percentage.
		 */
		if ( $percentage ) {

			$bar_class = 'vcex-skillbar-bar wpex-relative wpex-w-0 wpex-h-100 wpex-bg-accent';

			if ( 'true' == $animate_percent ) {
				$bar_class .= ' wpex-transition-width wpex-duration-700';
			}

			if ( $border_radius_class ) {
				$bar_class .= ' ' . sanitize_html_class( $border_radius_class );
			}

			$bar_style = vcex_inline_style( array(
				'background' => $color,
				'width'      => ( 'true' !== $animate_percent ) ? intval( $percentage ) . '%' : '',
			) );

			$output .= '<div class="' . esc_attr( $bar_class ) . '"' . $bar_style . '>';

				if ( 'inside' === $title_position && ! empty( $percent_output ) ) {
					$output .= $percent_output;
				}

			$output .= '</div>';

		}

		/*
		 * Title
		 */
		if ( 'inside' === $title_position ) {

			$dir = is_rtl() ? 'right' : 'left';

			$title_style = vcex_inline_style( array(
				'background'      => $color,
				'padding_' . $dir => $container_padding_left,
				'color'           => $label_color,
			) );

			$output .= '<div class="vcex-skillbar-title wpex-absolute wpex-top-0 wpex-left-0"' . $title_style . '>';

				$output .= '<div class="vcex-skillbar-title-inner wpex-px-15">';

					// Display Icon.
					if ( ! empty( $icon_output ) ) {
						$output .= $icon_output;
					}

					// Title.
					if ( 'default' === $style ) {
						$output .= wp_kses_post( do_shortcode( $title ) );
					}

				$output .= '</div>';

			$output .= '</div>';

		}

		// Display percent outside of colored background.
		if ( 'outside' === $title_position && ! empty( $percent_output ) ) {
			$output .= $percent_output;
		}

	$output .= '</div>';

$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;