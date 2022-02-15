<?php
/**
 * vcex_pricing shortcode output
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_pricing', $atts ) ) {
	return;
}

// Get and extract shortcode attributes
$atts = vcex_shortcode_atts( 'vcex_pricing', $atts, $this );
extract( $atts );

$style = $style ?: 'default';
$is_featured = vcex_validate_boolean( $featured );

// Define output var
$output = '';

// Define pricing item classes
$class = array(
	'vcex-module',
	'vcex-pricing',
	'vcex-pricing-style-' . esc_attr( $style ),
	'wpex-leading-normal',
);

switch ( $style ) {
	case 'default':
		$class[] = 'wpex-bg-white';
		break;
	case 'alt-1':
		$class[] = 'wpex-bg-white';
		$class[] = 'wpex-p-40';
		$class[] = 'wpex-text-center';
		$class[] = 'wpex-border-solid';
		if ( $is_featured ) {
			$class[] = 'wpex-border-2';
			$class[] = 'wpex-border-accent';
		} else {
			$class[] = 'wpex-border';
			$class[] = 'wpex-border-gray-300';
		}
		$class[] = 'wpex-last-mb-0';
		break;
	case 'alt-2':
		$class[] = 'wpex-bg-white';
		$class[] = 'wpex-p-20';
		$class[] = 'wpex-border-solid';
		if ( $is_featured ) {
			$class[] = 'wpex-border-2';
			$class[] = 'wpex-border-accent';
		} else {
			$class[] = 'wpex-border';
			$class[] = 'wpex-border-gray-300';
		}
		$class[] = 'wpex-last-mb-0';
		break;
	case 'alt-3':
		$class[] = 'wpex-p-25';
		$class[] = 'wpex-text-center';
		if ( $is_featured ) {
			$class[] = 'wpex-bg-accent';
			$class[] = 'wpex-text-white';
		} else {
			$class[] = 'wpex-bg-gray-100';
			$class[] = 'wpex-text-gray-900';
		}
		$class[] = 'wpex-last-mb-0';
		break;
}

if ( $is_featured ) {
	$class[] = 'featured';
}

if ( $shadow ) {
	$class[] = 'wpex-' . sanitize_html_class( $shadow );
}

if ( $bottom_margin ) {
	$class[] = vcex_sanitize_margin_class( $bottom_margin, 'wpex-mb-' );
}

if ( $el_class ) {
	$class[] = vcex_get_extra_class( $el_class );
}

if ( $visibility ) {
	$class[] = vcex_parse_visibility_class( $visibility );
}

if ( $hover_animation ) {
	$class[] = vcex_hover_animation_class( $hover_animation );
	vcex_enque_style( 'hover-animations' ); // @todo move to vcex_hover_animation_class
}

if ( $css ) {
	$class[] = vcex_vc_shortcode_custom_css_class( $css );
}

$class = vcex_parse_shortcode_classes( implode( ' ', $class ), 'vcex_pricing', $atts );

$wrap_attrs = array(
	'id'    => vcex_get_unique_id( $unique_id ),
	'class' => $class
);

/*-----------------------------------------------------*/
/* [ Begin output ]
/*-----------------------------------------------------*/
if ( $css_animation && 'none' !== $css_animation ) {

	$css_animation_style = vcex_inline_style( array(
		'animation_delay' => $atts['animation_delay'],
		'animation_duration' => $atts['animation_duration'],
	) );

	$animation_classes = array( trim( vcex_get_css_animation( $css_animation ) ) );

	if ( $visibility ) {
		$animation_classes[] = vcex_parse_visibility_class( $visibility );
	}

	$output .= '<div class="' . esc_attr( implode( ' ', $animation_classes ) ) . '"' . $css_animation_style . '>';
}

$output .= '<div' . vcex_parse_html_attributes( $wrap_attrs ) . '>';

	/*-----------------------------------------------------*/
	/* [ Plan ]
	/*-----------------------------------------------------*/
	if ( $plan ) {

		$plan_class = array(
			'vcex-pricing-plan',
			'vcex-pricing-header', // legacy class pre v5
		);

		switch ( $style ) {
			case 'alt-1':
				$plan_class[] = 'wpex-font-medium';
				$plan_class[] = 'wpex-text-3xl';
				$plan_class[] = 'wpex-text-gray-900';
				break;
			case 'alt-2';
				$plan_class[] = 'wpex-font-bold';
				$plan_class[] = 'wpex-text-2xl';
				$plan_class[] = 'wpex-text-gray-900';
				$plan_class[] = 'wpex-border-b-3';
				$plan_class[] = 'wpex-border-solid';
				$plan_class[] = 'wpex-border-accent';
				$plan_class[] = 'wpex-mb-10';
				$plan_class[] = 'wpex-pb-10';
				break;
			case 'alt-3':
				break;
			case 'default':
				if ( $is_featured ) {
					$plan_class[] = 'wpex-bg-accent';
					$plan_class[] = 'wpex-text-white';
					$plan_class[] = 'wpex-border-transparent';
				} else {
					$plan_class[] = 'wpex-bg-gray-200';
					$plan_class[] = 'wpex-text-gray-800';
					$plan_class[] = 'wpex-border-gray-300';
				}
				$plan_class[] = 'wpex-border';
				$plan_class[] = 'wpex-border-solid';
				$plan_class[] = 'wpex-py-15';
				$plan_class[] = 'wpex-px-20';
				$plan_class[] = 'wpex-text-center';
				$plan_class[] = 'wpex-uppercase';
				$plan_class[] = 'wpex-font-semibold';
				break;
		}

		// Responsive plan styles.
		$unique_classname = vcex_element_unique_classname();

		$el_responsive_styles = array(
			'font_size' => $atts['plan_size'],
		);

		$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

		if ( $responsive_css ) {
			$plan_class[] = $unique_classname;
			$output .= '<style>' . $responsive_css . '</style>';
		}

		// Filter plan classes.
		$plan_class = apply_filters( 'vcex_pricing_plan_class', $plan_class, $atts );

		// Inline plan styles.
		$plan_style = vcex_inline_style( array(
			'margin'         => $plan_margin,
			'padding'        => $plan_padding,
			'background'     => $plan_background,
			'color'          => $plan_color,
			'font_size'      => $plan_size,
			'font_weight'    => $plan_weight,
			'letter_spacing' => $plan_letter_spacing,
			'border'         => $plan_border,
			'text_transform' => $plan_text_transform,
			'font_family'    => $plan_font_family,
		), false );

		// Define plan attributes.
		$plan_attrs = array(
			'class' => $plan_class,
			'style' => $plan_style,
		);

		// Display pricing plan.
		$output .= '<div' . vcex_parse_html_attributes( $plan_attrs ) . '>';

			$output .= wp_kses_post( do_shortcode( $plan ) );

		$output .= '</div>';

	}

	/*-----------------------------------------------------*/
	/* [ Cost ]
	/*-----------------------------------------------------*/
	if ( $cost ) {

		// Set default cost classes.
		$cost_class = array(
			'vcex-pricing-cost',
		);

		// Custom priing style utility classes.
		switch ( $style ) {
			case 'alt-1':
				$cost_class[] = 'wpex-text-accent';
				$cost_class[] = 'wpex-text-2xl';
				$cost_class[] = 'wpex-font-medium';
				$cost_class[] = 'wpex-mt-5';
				$cost_class[] = 'wpex-mb-20';
				$cost_class[] = 'wpex-leading-normal';
				break;
			case 'alt-2':
				$cost_class[] = 'wpex-text-2xl';
				$cost_class[] = 'wpex-text-gray-900';
				$cost_class[] = 'wpex-font-medium';
				$cost_class[] = 'wpex-border-b';
				$cost_class[] = 'wpex-border-solid';
				$cost_class[] = 'wpex-border-gray-200';
				$cost_class[] = 'wpex-mb-20';
				$cost_class[] = 'wpex-pb-30';
				break;
			case 'alt-3':
				$cost_class[] = 'wpex-text-5xl';
				$cost_class[] = 'wpex-font-bold';
				if ( ! $is_featured ) {
					$cost_class[] = 'wpex-text-gray-900';
				}
				break;
			case 'default':
				$cost_class[] = 'wpex-bg-gray-100';
				$cost_class[] = 'wpex-p-20';
				$cost_class[] = 'wpex-border-x';
				$cost_class[] = 'wpex-border-solid';
				$cost_class[] = 'wpex-border-gray-300';
				$cost_class[] = 'wpex-text-center';
				break;
		}

		/**
		 * Filters the pricing table cost classes.
		 *
		 * @param array $cost_class
		 * @param $array $shortcode_atts
		 */
		$cost_class = apply_filters( 'vcex_pricing_cost_class', $cost_class, $atts );

		// Inline cost styles.
		$cost_style = vcex_inline_style( array(
			'background'  => $cost_background,
			'padding'     => $cost_padding,
			'border'      => $cost_border,
			'font_family' => $cost_font_family,
		) );

		// Define cost element html attributes.
		$cost_attrs = array(
			'class' => $cost_class,
			'style' => $cost_style,
		);

		// Display cost element.
		$output .= '<div' . vcex_parse_html_attributes( $cost_attrs ) . '>';

			/*-----------------------------------------------------*/
			/* [ Amount ]
			/*-----------------------------------------------------*/
			$amount_class = array(
				'vcex-pricing-ammount', // I know there is a typo, too late now :(
			);

			switch ( $style ) {
				case 'default':
					$amount_class[] = 'wpex-text-6xl';
					$amount_class[] = 'wpex-leading-tight';
					$amount_class[] = 'wpex-font-light';
					break;
			}

			// Responsive cost amount styles.
			$unique_classname = vcex_element_unique_classname();

			$el_responsive_styles = array(
				'font_size' => $atts['cost_size'],
			);

			$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

			if ( $responsive_css ) {
				$amount_class[] = $unique_classname;
				$output .= '<style>' . $responsive_css . '</style>';
			}

			/**
			 * Filters the pricing table cost amount classes.
			 *
			 * @param array $class
			 * @param array $shortcode_atts
			 */
			$amount_class = apply_filters( 'vcex_pricing_amount_class', $amount_class, $atts );

			// Inline styles for the cost amount element.
			$amount_style = vcex_inline_style( array(
				'color'       => $cost_color,
				'font_size'   => $cost_size,
				'font_weight' => $cost_weight,
			), false );

			// Define cost amount html attributes.
			$amount_attrs = array(
				'class' => $amount_class,
				'style' => $amount_style,
			);

			// Display cost amount element.
			$output .= '<span' . vcex_parse_html_attributes( $amount_attrs ) . '>';

				$output .= do_shortcode( wp_kses_post( $cost ) );

			$output .= '</span>';

			/*-----------------------------------------------------*/
			/* [ Per ]
			/*-----------------------------------------------------*/
			$per_class = array(
				'vcex-pricing-per',
			);

			switch ( $style ) {
				case 'default':
					$per_class[] = 'wpex-text-sm';
					$per_class[] = 'wpex-text-gray-500';
					$per_class[] = 'wpex-leading-none';
					break;
				case 'alt-2':
					$per_class[] = 'wpex-text-xs';
					$per_class[] = 'wpex-font-normal';
					$per_class[] = 'wpex-text-gray-500';
					$per_class[] = 'wpex-ml-5';
					break;
				case 'alt-3':
				$per_class[] = 'wpex-text-xs';
					break;
			}

			if ( 'block' === $per_display ) {
				$per_class[] = 'wpex-mt-10';
			}

			// Responsive styles for the per element.
			$unique_classname = vcex_element_unique_classname();

			$el_responsive_styles = array(
				'font_size' => $atts['per_size'],
			);

			$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

			if ( $responsive_css ) {
				$per_class[] = $unique_classname;
				$output .= '<style>' . $responsive_css . '</style>';
			}

			/**
			 * Filters the pricing table "per" classes.
			 *
			 * @param array $class
			 * @param array $shortcode_atts
			 */
			$per_class = apply_filters( 'vcex_pricing_per_class', $per_class, $atts );

			if ( $per ) {

				$per_style = vcex_inline_style( array(
					'display'        => $per_display,
					'font_size'      => $per_size,
					'color'          => $per_color,
					'font_weight'    => $per_weight,
					'text_transform' => $per_transform,
					'font_family'    => $per_font_family
				), false );

				$per_attrs = array(
					'class' => $per_class,
					'style' => $per_style,
				);

				$output .= '<span' . vcex_parse_html_attributes( $per_attrs ) . '>';

					$output .= do_shortcode( wp_kses_post( $per ) );

				$output .= '</span>';
			}

		$output .= '</div>';

	}

	/*-----------------------------------------------------*/
	/* [ Features ]
	/*-----------------------------------------------------*/
	if ( $content ) {

		$content_class = array(
			'vcex-pricing-content',
			'wpex-clr',
		);

		switch ( $style ) {
			case 'alt-1':
				$content_class[] = 'wpex-my-20';
				break;
			case 'alt-2':
				$content_class[] = 'wpex-my-20';
				break;
			case 'alt-3':
				$content_class[] = 'wpex-my-20';
				break;
			case 'default':
				$content_class[] = 'wpex-text-center';
				$content_class[] = 'wpex-p-20';
				$content_class[] = 'wpex-border';
				$content_class[] = 'wpex-border-solid';
				$content_class[] = 'wpex-border-gray-300';
				break;
		}

		// Responsive styles for the per element.
		$unique_classname = vcex_element_unique_classname();

		$el_responsive_styles = array(
			'font_size' => $atts['font_size'],
		);

		$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

		if ( $responsive_css ) {
			$content_class[] = $unique_classname;
			$output .= '<style>' . $responsive_css . '</style>';
		}

		/**
		 * Filters the pricing table content element classes.
		 *
		 * @param array $content_class
		 * @param array $shortcode_atts
		 */
		$content_class = apply_filters( 'vcex_pricing_content_class', $content_class, $atts );

		$content_style = vcex_inline_style( array(
			'padding'     => $features_padding,
			'background'  => $features_bg,
			'border'      => $features_border,
			'color'       => $font_color,
			'font_size'   => $font_size,
			'font_family' => $font_family,
			'line_height' => $line_height,
		), false );

		$content_attrs = array(
			'class' => $content_class,
			'style' => $content_style,
		);

		// Display the pricing content element.
		$output .= '<div' . vcex_parse_html_attributes( $content_attrs ) . '>';

			$output .= do_shortcode( wp_kses_post( $content ) );

		$output .= '</div>';

	}

	/*-----------------------------------------------------*/
	/* [ Button ]
	/*-----------------------------------------------------*/
	if ( $button_url && ! $custom_button ) {
		$button_url_temp = $button_url; // fallback for old option
		$button_url      = vcex_get_link_data( 'url', $button_url_temp );
	}

	if ( $button_url || $custom_button ) {

		$button_wrap_class = array(
			'vcex-pricing-button',
		);

		switch ( $style ) {
			case 'alt-1':
				break;
			case 'default':
				$button_wrap_class[] = 'wpex-p-20';
				$button_wrap_class[] = 'wpex-border';
				$button_wrap_class[] = 'wpex-border-t-0';
				$button_wrap_class[] = 'wpex-border-solid';
				$button_wrap_class[] = 'wpex-border-gray-300';
				$button_wrap_class[] = 'wpex-text-center';
				break;
		}

		/**
		 * Filters the pricing table button wrap element classes.
		 *
		 * @param array $class
		 * @param array $shortcode_atts
		 */
		$button_wrap_class = apply_filters( 'vcex_pricing_button_class', $button_wrap_class, $atts );

		// Button Wrap Style
		$button_wrap_style = vcex_inline_style( array(
			'padding'     => $button_wrap_padding,
			'border'      => $button_wrap_border,
			'background'  => $button_wrap_bg,
			'font_family' => $button_font_family,
		) );

		$button_wrap_args = array(
			'class' => $button_wrap_class,
			'style' => $button_wrap_style,
		);

		/**
		 * Extra checks needed due to button_url sanitization.
		 *
		 * @todo can this be done better?
		 */
		$button_url = $custom_button ? false : $button_url; // Set button url to false if custom_button isn't empty.

		if ( $button_url || $custom_button ) {

			$output .= '<div' . vcex_parse_html_attributes( $button_wrap_args ) . '>';

				/**
				 * Custom button.
				 */
				if ( $custom_button = vcex_parse_textarea_html( $custom_button ) ) {

					$output .= do_shortcode( $custom_button );

				}

				/**
				 * Theme button.
				 */
				elseif ( $button_url ) {

					if ( ! $button_style_color && 'alt-3' === $style && $is_featured ) {
						$button_style_color = 'white';
					}

					$button_title  = vcex_get_link_data( 'title', $button_url_temp );
					$button_target = vcex_get_link_data( 'target', $button_url_temp );
					$button_rel    = vcex_get_link_data( 'rel', $button_url_temp );
					$button_class  = array( vcex_get_button_classes( $button_style, $button_style_color ) );

					if ( 'default' !== $style ) {
						$button_class[] = 'wpex-p-10';
						$button_class[] = 'wpex-w-100';
						$button_class[] = 'wpex-text-center';
					}

					switch ( $style ) {
						case 'alt-1':
							$button_class[] = 'wpex-rounded-full';
							break;
						case 'alt-3':
							$button_class[] = 'wpex-font-bold';
							break;
					}

					// Custom Button Classes.
					if ( 'true' == $button_local_scroll ) {
						$button_class[] = 'local-scroll-link';
					}

					if ( $button_transform ) {
						$button_class[] = 'text-transform-' . esc_attr( $button_transform );
					}

					// Button responsive styles.
					$unique_classname = vcex_element_unique_classname();

					$el_responsive_styles = array(
						'font_size' => $atts['button_size'],
					);

					$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

					if ( $responsive_css ) {
						$button_class[] = $unique_classname;
						$output .= '<style>' . $responsive_css . '</style>';
					}

					// Define button attributes.
					$button_attrs = array(
						'href'   => esc_url( do_shortcode( $button_url ) ),
						'title'  => esc_attr( do_shortcode( $button_title ) ),
						'target' => $button_target,
						'rel'    => $button_rel,
						'class'  => $button_class
					);

					// Button Data attributes.
					$hover_data = array();

					if ( $button_hover_bg_color ) {
						$hover_data['background'] = esc_attr( vcex_parse_color( $button_hover_bg_color ) );
					}

					if ( $button_hover_color ) {
						$hover_data['color'] = esc_attr( vcex_parse_color( $button_hover_color ) );
					}

					if ( $hover_data ) {
						$button_attrs['data-wpex-hover'] = htmlspecialchars( wp_json_encode( $hover_data ) );
					}

					// Button Style.
					$button_border_color = ( 'outline' === $button_style ) ? $button_color : '';
					$button_css = vcex_inline_style( array(
						'background'     => $button_bg_color,
						'color'          => $button_color,
						'letter_spacing' => $button_letter_spacing,
						'font_size'      => $button_size,
						'padding'        => $button_padding,
						'border_radius'  => $button_border_radius,
						'font_weight'    => $button_weight,
						'border_color'   => $button_border_color,
						'border_width'   => $button_border_width,
						'text_transform' => $button_transform,
					), false );

					// Add parsed button attributes to array.
					$button_attrs['style']  = $button_css;

					$output .= '<a' . vcex_parse_html_attributes( $button_attrs ) . '>';

						// Get correct icon classes.
						$button_icon_left  = vcex_get_icon_class( $atts, 'button_icon_left' );
						$button_icon_right = vcex_get_icon_class( $atts, 'button_icon_right' );

						if ( $button_icon_left || $button_icon_right ) {
							vcex_enqueue_icon_font( $icon_type, $button_icon_left );
							vcex_enqueue_icon_font( $icon_type, $button_icon_right );
						}

						// Button Icon Left.
						if ( $button_icon_left ) {

							$attrs = array(
								'class' => array(
									'vcex-icon-wrap',
									'theme-button-icon-left',
								)
							);

							if ( $button_icon_left_transform ) {

								$attrs['class'][] = 'wpex-transition-transform wpex-duration-200';

								$attrs['data-wpex-hover'] = htmlspecialchars( wp_json_encode( array(
									'parent'    => '.theme-button',
									'transform' => 'translateX(' . vcex_validate_font_size( $button_icon_left_transform ) . ')',
								) ) );

							}

							$output .= '<span' . vcex_parse_html_attributes( $attrs ) . '>';

								$output .= '<span class="' . esc_attr( $button_icon_left ) . '"></span>';

							$output .= '</span>';

						}

						$output .= do_shortcode( $button_text );

						// Button Icon Right.
						if ( $button_icon_right ) {

							$attrs = array(
								'class' => array(
									'vcex-icon-wrap',
									'theme-button-icon-right',
								),
							);

							if ( $button_icon_right_transform ) {
								$attrs['class'][] = 'wpex-transition-transform wpex-duration-200';
								$attrs['data-wpex-hover'] = htmlspecialchars( wp_json_encode( array(
									'parent'    => '.theme-button',
									'transform' => 'translateX(' . vcex_validate_font_size( $button_icon_right_transform ) . ')',
								) ) );
							}

							$output .= '<span' . vcex_parse_html_attributes( $attrs ) . '>';

								$output .= '<span class="' . esc_attr( $button_icon_right ) . '"></span>';

							$output .= '</span>';

						}

					$output .= '</a>';

				}

			$output .= '</div>';

		}

	} // End button checks.

$output .= '</div>';

if ( $css_animation && 'none' !== $css_animation ) {
	$output .= '</div>';
}

// @codingStandardsIgnoreLine
echo $output;