<?php
/**
 * vcex_author_bio shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_author_bio', $atts ) ) {
	return;
}

// Get and extract shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_author_bio', $atts, $this );

// Define main vars.
$output = '';
$style  = ! empty( $atts['style'] ) ? $atts['style'] : 'default';

if ( 'default' !== $style ) {
	$breakpoint = 'md';
	$avatar_size = absint( $atts['avatar_size'] );
	$avatar_border_radius_class = 'wpex-rounded-full';

	if ( ! empty( $atts['avatar_spacing'] ) ) {
		$avatar_spacing = absint( $atts['avatar_spacing'] );
	}

	if ( in_array( $style, array( 'alt-3') ) ) {
		$avatar_border_radius_class = '';
	}

	if ( ! empty( $atts['avatar_border_radius'] ) ) {
		$avatar_border_radius_class = vcex_parse_border_radius_class( $atts['avatar_border_radius'] );
	}

	$avatar_class = array( 'class' => 'wpex-align-middle ' . $avatar_border_radius_class );

}

// Shortcode classes.
$shortcode_class = array(
	'vcex-author-bio',
	'vcex-module',
);

if ( 'default' !== $style ) {
	$shortcode_class[] = 'vcex-author-bio--' . sanitize_html_class( $style );
}

switch ( $style ) {

	case 'alt-1':
		$shortcode_class[] = 'wpex-flex wpex-flex-col wpex-' . $breakpoint . '-flex-row wpex-'. $breakpoint . '-items-center';
		$shortcode_class[] = 'wpex-bordered';
		if ( empty( $atts['padding_all'] ) ) {
			$shortcode_class[] = 'wpex-p-30';
		}
		break;

	case 'alt-2':
	case 'alt-3':
		$shortcode_class[] = 'wpex-flex wpex-items-center';
		break;

	case 'alt-4':
		$shortcode_class[] = 'wpex-flex wpex-flex-col wpex-' . $breakpoint . '-flex-row';
		break;

}

if ( $bottom_margin_class = vcex_parse_margin_class( $atts['bottom_margin'], 'wpex-mb-' ) ) {
	$shortcode_class[] = $bottom_margin_class;
}

if ( $atts['visibility'] ) {
	$shortcode_class[] = vcex_parse_visibility_class( $atts['visibility'] );
}

if ( $css_animation_class = vcex_get_css_animation( $atts['css_animation'] ) ) {
	$shortcode_class[] = $css_animation_class;
}

if ( 'default' !== $style ) {

	if ( $padding_class = vcex_parse_padding_class( $atts['padding_all'] ) ) {
		$shortcode_class[] = $padding_class;
	}

	if ( $border_style_class = vcex_parse_border_style_class( $atts['border_style'] ) ) {
		$shortcode_class[] = $border_style_class;
	}

	if ( $border_width_class = vcex_parse_border_width_class( $atts['border_width'] ) ) {
		$shortcode_class[] = $border_width_class;
	}

	if ( $border_radius_class = vcex_parse_border_radius_class( $atts['border_radius'] ) ) {
		$shortcode_class[] = $border_radius_class;
	}

	if ( $css_class = vcex_vc_shortcode_custom_css_class( $atts['css'] )  ) {
		$shortcode_class[] = $css_class;
	}

}

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

if ( $el_class = vcex_get_extra_class( $atts['el_class'] ) ) {
	$shortcode_class[] = $el_class;
}

$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, 'vcex_author_bio', $atts );

$shortcode_style = vcex_inline_style( array(
	'max_width'          => $atts['max_width'],
	'background_color'   => $atts['background_color'],
	'border_color'       => $atts['border_color'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
) );

$output .= '<div class="' . esc_attr( $shortcode_class ) . '"' . $shortcode_style . '>';

	// Get user data for custom styles.
	if ( 'default' !== $style ) {

		$post_id = vcex_get_the_ID();

		$post = get_post( $post_id );

		$date_format = ! empty( $atts['date_format'] ) ? $date_format : '';

		if ( function_exists( 'wpex_get_author_box_data' ) ) {

			$authordata = wpex_get_author_box_data( $post );

			$author_display = trim( esc_html( ucfirst( $authordata['author_name'] ) ) );

			$author_link = '';

			switch ( $atts['author_onclick'] ) {
				case 'author_website':
					$author_link = get_the_author_meta( 'user_url', $post->post_author );
					$author_link_title = esc_html( 'Go to Author Website', 'total' );
					break;
				case 'author_archive':
					$author_link = get_author_posts_url( $post->post_author );
					$author_link_title = esc_html( 'Go to Author Page', 'total' );
					break;
			}

			if ( ! empty( $atts['author_onclick_title'] ) ) {
				$author_link_title = $atts['author_onclick_title'];
			}

		}

		if ( empty( $authordata ) ) {
			$style = ''; // prevent showing anything.
		}

	}

	switch ( $style ) {

		/*--------------------------------*/
		/* [ Style => Alt 1 ]
		/*--------------------------------*/
		case 'alt-1':

			if ( empty( $avatar_size ) ) {
				$avatar_size = 100;
			}

			$avatar = get_avatar( $authordata['post_author'], $avatar_size, '', '', $avatar_class );

			if ( ! isset( $avatar_spacing ) ) {
				$avatar_spacing = '30';
			}

			if ( ! empty( trim( $avatar ) ) ) {

				$output .= '<div class="vcex-author-bio__avatar wpex-mb-' . $avatar_spacing . ' wpex-' . $breakpoint .'-mb-0 wpex-' . $breakpoint .'-mr-' . $avatar_spacing . ' wpex-flex-shrink-0">';

					if ( $author_link ) {
						$output .= '<a href="' . esc_url( $author_link ) . '" title="' . $author_link_title . '">' . $avatar . '</a>';
					} else {
						$output .= $avatar;
					}

				$output .= '</div>';

			}

			$output .= '<div class="vcex-author-bio__details wpex-flex-grow">';

				if ( ! empty( $authordata['author_name'] ) ) {

					$output .= '<div class="vcex-author-bio__title wpex-font-heading wpex-text-lg">';

						if ( $author_link ) {
							$author_display = '<a href="' . esc_url( $author_link ) . '"  title="' . $author_link_title . '">' . $author_display . '</a>';
						} else {
							$author_display = '<span class="wpex-font-bold">' . $author_display . '</span>';
						}

						$output .= sprintf( esc_html__( 'By %s on %s', 'total-theme-core' ), $author_display, get_the_date( $date_format, $post->ID ) );

					$output .= '</div>';


				}

				$get_terms = vcex_get_list_post_terms();

				if ( ! empty( $get_terms ) ) {

					$output .= '<div class="vcex-author-bio__meta wpex-mt-10">' . sprintf( esc_html__( 'Posted in %s', 'total-theme-core' ), $get_terms ) . '</div>';

				}

			$output .= '</div>';

			break;

		/*--------------------------------*/
		/* [ Style => Alt 2 ]
		/*--------------------------------*/
		case 'alt-2':

			if ( empty( $avatar_size ) ) {
				$avatar_size = 50;
			}

			if ( ! isset( $avatar_spacing ) ) {
				$avatar_spacing = '20';
			}

			$avatar = get_avatar( $authordata['post_author'], $avatar_size, '', '', $avatar_class );

			if ( ! empty( trim( $avatar ) ) ) {

				$output .= '<div class="vcex-author-bio__avatar wpex-mr-' . $avatar_spacing . ' wpex-flex-shrink-0">';

					if ( $author_link ) {
						$output .= '<a href="' . esc_url( $author_link ) . '"  title="' . $author_link_title . '">' . $avatar . '</a>';
					} else {
						$output .= $avatar;
					}

				$output .= '</div>';

			}

			if ( ! empty( $authordata['author_name'] ) ) {

				$output .= '<div class="vcex-author-bio__name">';

					if ( $author_link ) {
						$output .= '<a href="' . esc_url( $author_link ) . '" class="wpex-inherit-color">' . $author_display . '</a>';
					} else {
						$output .= $author_display;
					}

				$output .= '</div>';


			}

			break;

		/*--------------------------------*/
		/* [ Style => Alt 3 ]
		/*--------------------------------*/
		case 'alt-3':

			if ( empty( $avatar_size ) ) {
				$avatar_size = 80;
			}

			if ( ! isset( $avatar_spacing ) ) {
				$avatar_spacing = '20';
			}

			$avatar = get_avatar( $authordata['post_author'], $avatar_size, '', '', $avatar_class );

			if ( ! empty( trim( $avatar ) ) ) {

				$output .= '<div class="vcex-author-bio__avatar wpex-mr-' . $avatar_spacing . ' wpex-flex-shrink-0">';

					if ( $author_link ) {
						$output .= '<a href="' . esc_url( $author_link ) . '"  title="' . $author_link_title . '">' . $avatar . '</a>';
					} else {
						$output .= $avatar;
					}

				$output .= '</div>';

			}

			if ( ! empty( $authordata['author_name'] ) ) {

				$output .= '<div class="vcex-author-bio__name wpex-heading wpex-text-lg">';

					if ( $author_link ) {
						$output .= '<a href="' . esc_url( $author_link ) . '"  title="' . $author_link_title . '">' . $author_display . '</a>';
					} else {
						$output .= $author_display;
					}

				$output .= '</div>';


			}

			break;

		/*--------------------------------*/
		/* [ Style => Alt 4 ]
		/*--------------------------------*/
		case 'alt-4':

			if ( empty( $avatar_size ) ) {
				$avatar_size = 65;
			}

			if ( ! isset( $avatar_spacing ) ) {
				$avatar_spacing = '25';
			}

			$avatar = get_avatar( $authordata['post_author'], $avatar_size, '', '', $avatar_class );

			if ( ! empty( trim( $avatar ) ) ) {

				$output .= '<div class="vcex-author-bio__avatar wpex-mb-' . $avatar_spacing . ' wpex-' . $breakpoint .'-mb-0 wpex-' . $breakpoint .'-mr-' . $avatar_spacing . ' wpex-flex-shrink-0">';

					if ( $author_link ) {
						$output .= '<a href="' . esc_url( $author_link ) . '"  title="' . $author_link_title . '">' . $avatar . '</a>';
					} else {
						$output .= $avatar;
					}

				$output .= '</div>';

			}

			$output .= '<div class="vcex-author-bio__details wpex-flex-grow">';

				if ( ! empty( $authordata['author_name'] ) ) {

					$output .= '<div class="vcex-author-bio__title wpex-heading wpex-text-lg">';

						if ( $author_link ) {
							$output .= '<a href="' . esc_url( $author_link ) . '"  title="' . $author_link_title . '">' . $author_display . '</a>';
						} else {
							$output .= $author_display;
						}

					$output .= '</div>';

					$description = get_the_author_meta( 'description', $post->post_author );

					if ( $description ) {

						$output .= '<div class="vcex-author-bio__description wpex-mt-10">' . do_shortcode( wp_kses_post( $description ) ) . '</div>';

					}

					if ( function_exists( 'wpex_get_user_social_links' ) ) {

						$output .= wpex_get_user_social_links( array(
							'user_id'         => $post->post_author,
							'display'         => 'icons',
							'before'          => '<div class="vcex-author-bio__social wpex-mt-10 wpex-leading-none wpex-last-mr-0">',
							'after'           => '</div>',
							'link_attributes' => array(
								'class' => 'wpex-inline-block wpex-p-5 wpex-inherit-color wpex-hover-text-accent wpex-mr-10'
							),
						) );

					}

				}

			$output .= '</div>';

			break;

		/*--------------------------------*/
		/* [ Style => Default ]
		/*--------------------------------*/
		case 'default':

			if ( function_exists( 'wpex_get_template_part' ) ) {
				ob_start();
				wpex_get_template_part( 'author_bio' );
				$output .= ob_get_clean();
			}

			break;

	}

$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;