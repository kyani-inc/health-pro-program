<?php
/**
 * vcex_post_terms shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_post_terms', $atts ) ) {
	return;
}

// Renamed atts @todo move to parse_deprecated_attributes method.
if ( empty( $atts['archive_link_target'] ) && ! empty( $atts['target'] ) ) {
	$atts['archive_link_target'] = $atts['target'];
}

// Get shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_post_terms', $atts, $this );

// Extract atts.
extract( $atts );

// Locate taxonomy if one isn't defined.
if ( empty( $taxonomy ) && function_exists( 'wpex_get_post_primary_taxonomy' ) ) {
	$taxonomy = wpex_get_post_primary_taxonomy();
}

// Taxonomy is required.
if ( empty( $taxonomy ) || ! taxonomy_exists( $taxonomy ) ) {
	return;
}

// Get module style.
$module_style = ! empty( $style ) ? $style : 'buttons';

// Define terms.
$terms = array();

// Get featured term.
if ( 'true' == $first_term_only && function_exists( 'wpex_get_post_primary_term' ) ) {
	$primary_term = wpex_get_post_primary_term( '', $taxonomy );
	if ( $primary_term ) {
		$terms = array( $primary_term );
	}
}

// If terms is empty lets query them.
if ( ! $terms ) {

	// Query arguments.
	$query_args = array(
		'order'   => $order,
		'orderby' => $orderby,
		'fields'  => 'all',
	);

	// Apply filters to query args.
	$query_args = apply_filters( 'vcex_post_terms_query_args', $query_args, $atts );

	// Get terms.
	$terms = wp_get_post_terms( vcex_get_the_ID(), $taxonomy, $query_args );

	// Get first term only.
	if ( 'true' == $first_term_only ) {
		$terms = array( $terms[0] );
	}

}

// Terms needed.
if ( ! $terms || is_wp_error( $terms ) ) {
	return;
}

// Define output var.
$output = '';

// Wrap classes.
$shortcode_class = array(
	'vcex-post-terms',
	'wpex-clr',
);

if ( 'center' === $button_align && 'buttons' === $style ) {
	$shortcode_class[] = 'textcenter';
	$shortcode_class[] = 'wpex-last-mr-0';
}

if ( $button_color && 'buttons' !== $style ) {
	$shortcode_class[] = 'wpex-child-inherit-color';
}

// Alignment
if ( ! empty( $atts['max_width'] ) ) {

	switch ( $atts['align'] ) {
		case 'left':
			$shortcode_class[] = 'wpex-mr-auto';
			break;
		case 'right':
			$shortcode_class[] = 'wpex-ml-auto';
			break;
		case 'center':
		default:
			$shortcode_class[] = 'wpex-mx-auto';
			break;
	}

}

// Add extra classes.
$extra_classes = vcex_get_shortcode_extra_classes( $atts, 'vcex_post_terms' );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, 'vcex_post_terms', $atts );

// Wrap style.
$wrap_style_args = array(
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
	'max_width'          => $atts['max_width'],
);

if ( 'buttons' !== $module_style ) {
	$wrap_style_args['font_family']    = $button_font_family;
	$wrap_style_args['color']          = $button_color;
	$wrap_style_args['font_size']      = $button_font_size;
	$wrap_style_args['font_weight']    = $button_font_weight;
	$wrap_style_args['text_transform'] = $button_text_transform;
}

$wrap_style = vcex_inline_style( $wrap_style_args );

// Begin output.
$output .= '<div class="' . esc_attr( $shortcode_class ) . '"' . vcex_get_unique_id( $unique_id ) . $wrap_style . '>';

	// Define link vars
	$link_style = '';
	$link_class = array();

	// Button Style Classes and inline styles.
	if ( 'buttons' === $module_style ) {

		$link_class = array();

		$link_class[] = vcex_get_button_classes(
			$button_style,
			$button_color_style,
			$button_size,
			$button_align
		);

		$spacing = $spacing ?: '5';
		$spacing_direction = ( 'right' === $button_align ) ? 'l' : 'r';

		$link_class[] = 'wpex-m' . $spacing_direction . '-' . sanitize_html_class( absint( $spacing ) );
		$link_class[] = 'wpex-mb-' . sanitize_html_class( absint( $spacing ) );

		if ( 'false' == $archive_link || ! $archive_link ) {
			$link_class[] = 'wpex-cursor-default';
		}

		// Button Style.
		$link_style_args = array(
			'margin'         => $button_margin,
			'color'          => ( 'term_color' !== $button_color ) ? $button_color : '',
			'background'     => ( 'term_color' !== $button_background ) ? $button_background : '',
			'padding'        => $button_padding,
			'font_size'      => $button_font_size,
			'font_weight'    => $button_font_weight,
			'border_radius'  => $button_border_radius,
			'text_transform' => $button_text_transform,
			'font_family'    => $button_font_family,
			'letter_spacing' => $button_letter_spacing,
		);

		$link_style = vcex_inline_style( $link_style_args );

	}

	// Get child_of value.
	if ( ! empty( $child_of ) ) {
		$get_child_of = get_term_by( 'slug', trim( $child_of ), $taxonomy );
		if ( $get_child_of ) {
			$child_of_id = $get_child_of->term_id;
		}
	}

	// Get excluded terms.
	if ( ! empty( $exclude_terms ) ) {
		$exclude_terms = preg_split( '/\,[\s]*/', $exclude_terms );
	} else {
		$exclude_terms = array();
	}

	// Before Text.
	if ( 'inline' === $module_style && ! empty( $before_text ) ) {
		$output .= '<span class="vcex-label">' . do_shortcode( wp_strip_all_tags( $before_text ) ) . '</span> ';
	}

	// Open UL list.
	elseif ( 'ul' === $module_style ) {
		$output .= '<ul>';
	}

	// Open OL list.
	elseif ( 'ol' === $module_style ) {
		$output .= '<ol>';
	}

	// Loop through terms.
	$terms_count = 0;
	$first_run = true;
	foreach ( $terms as $term ) :

		// Set link class in loop to prevent issues with added term classes.
		$item_link_class = $link_class;

		// Skip items that aren't a child of a specific parent..
		if ( ! empty( $child_of_id ) && $term->parent != $child_of_id ) {
			continue;
		}

		// Skip excluded terms.
		if ( in_array( $term->slug, $exclude_terms ) ) {
			continue;
		}

		// Add to counter.
		$terms_count ++;

		// Add li tags.
		if ( in_array( $module_style, array( 'ul', 'ol' ) ) ) {
			$output .= '<li>';
		}

		// Hover styles
		$link_hover_data = array();
		if ( ! empty( $atts['button_hover_background'] ) ) {
			$button_hover_background = $atts['button_hover_background'];
			if ( 'term_color' === $atts['button_hover_background'] ) {
				$button_hover_background = vcex_get_term_color( $term );
			}
			$link_hover_data['background'] = esc_attr( vcex_parse_color( $button_hover_background ) );
		}

		if ( ! empty( $atts['button_hover_color'] ) ) {
			$button_hover_color = $atts['button_hover_color'];
			if ( 'term_color' === $atts['button_hover_color'] ) {
				$button_hover_color = vcex_get_term_color( $term );
			}
			$link_hover_data['color'] = esc_attr( vcex_parse_color( $button_hover_color ) );
		}

		$link_hover_data = $link_hover_data ? htmlspecialchars( wp_json_encode( $link_hover_data ) ) : '';

		// Add term colors.
		if ( 'term_color' === $button_background ) {
			$item_link_class[] = 'has-term-' . sanitize_html_class( $term->term_id ) . '-background-color';
		}
		if ( 'term_color' === $button_color ) {
			$item_link_class[] = 'has-term-' . sanitize_html_class( $term->term_id ) . '-color';
		}

		// Add filter to link class and sanitize
		$item_link_class = apply_filters( 'vcex_post_terms_link_class', $item_link_class, $term, $atts );

		// Open term element.
		if ( 'true' == $atts['archive_link'] ) {

			$output .= '<a' . vcex_parse_html_attributes( array(
				'href'            => esc_url( get_term_link( $term, $taxonomy ) ),
				'class'           => $item_link_class,
				'style'           => $link_style,
				'target'          => $archive_link_target,
				'data-wpex-hover' => $link_hover_data,
			) ) . '>';

		} else {

			$output .= '<span' . vcex_parse_html_attributes( array(
				'class' => $item_link_class,
				'style' => $link_style,
				'data-wpex-hover' => $link_hover_data,
			) ) . '>';

		}

		// Display title.
		$output .= esc_html( $term->name );

		// Close term element.
		if ( 'true' == $archive_link ) {
			$output .= '</a>';
		} else {
			$output .= '</span>';
		}

		// Add spacer for inline style.
		if ( 'inline' === $module_style && $terms_count < count( $terms ) ) {

			$custom_spacer = apply_filters( 'vcex_post_terms_default_spacer', $spacer );

			if ( $custom_spacer ) {
				$output .= ' ';
				$spacer = $custom_spacer;
			} else {
				$spacer = '&comma;';
			}

			$output .= '<span class="vcex-spacer">' . do_shortcode( wp_strip_all_tags( $spacer ) ) . '</span> ';

		}

		// Close li tags.
		if ( in_array( $module_style, array( 'ul', 'ol' ) ) ) {
			$output .= '</li>';
		}

		$first_run = false;

	endforeach;

	// Close UL list.
	if ( 'ul' === $module_style ) {
		$output .= '</ul>';
	}

	// Open OL list
	elseif ( 'ol' === $module_style ) {
		$output .= '</ol>';
	}

// Close main wrapper.
$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;