<?php
/**
 * vcex_post_meta shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_post_meta', $atts ) ) {
	return;
}

$atts = vcex_shortcode_atts( 'vcex_post_meta', $atts, $this );

if ( ! empty( $atts['sections'] ) ) {
	$sections = (array) vcex_vc_param_group_parse_atts( $atts['sections'] );
}

if ( ! ! empty( $sections ) ) {
	return;
}

global $post;

if ( ! $post ) {
	return;
}

$output = '';

$is_templatera = ( 'templatera' === $post->post_type ) ? true : false;
$style = ( 'vertical' === $atts['style'] ) ? 'vertical' : 'horizontal';

$shortcode_class = array(
	'vcex-post-meta',
	'meta',
);

if ( 'vertical' === $style ) {
	$shortcode_class[] = 'meta-vertical';
}

if ( 'horizontal' === $style ) {
	$shortcode_class[] = 'wpex-flex wpex-flex-wrap wpex-items-center'; // allows vertical alignment for the author avatar.
}

if ( $atts['align'] ) {
	$atts['text_align'] = $atts['align'];

	if ( 'horizontal' === $style ) {
		$justify_class = vcex_parse_justify_content_class( $atts['align'] );
		if ( $justify_class ) {
			$shortcode_class[] = $justify_class;
		}
	}

}

if ( $atts['color'] ) {
	$shortcode_class[] = 'wpex-child-inherit-color';
}

if ( ! empty( $atts['max_width'] ) ) {

	switch ( $atts['float'] ) {
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

$extra_classes = vcex_get_shortcode_extra_classes( $atts, 'vcex_post_meta' );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

$shortcode_class = vcex_parse_shortcode_classes( implode( ' ', $shortcode_class ), 'vcex_post_meta', $atts );

$shortcode_style = vcex_inline_style( array(
	'font_size'          => $atts['font_size'],
	'color'              => $atts['color'],
	'line_height'        => $atts['line_height'],
	'letter_spacing'     => $atts['letter_spacing'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
	'max_width'          => $atts['max_width'],
) );

// Output starts here.
$output .= '<ul class="' . esc_attr( $shortcode_class ) . '"' . $shortcode_style . '>';

	// Separator html.
	if ( 'vertical' !== $atts['style'] ) {

		switch ( $atts['separator'] ) {
			case 'dash':
				$separator = '&ndash;';
			break;
			case 'dot':
				$separator = '&middot;';
			break;
			case 'forward_slash':
				$separator = '&sol;';
			break;
			case 'backslash':
				$separator = '&bsol;';
			break;
			case 'pipe':
				$separator = '&vert;';
			break;
			default:
				$separator = '';
			break;
		}

		if ( ! empty( $separator ) ) {
			$separator = '<li class="vcex-post-meta__separator">' . $separator . '</li>';
		}

	}

	// Sections.
	$count = 0;
	foreach ( $sections as $section ) {

		$section_html = '';
		$type = $section['type'] ?? '';
		$label = $section['label'] ?? '';
		$icon_type = $section['icon_type'] ?? '';
		$icon = $section['icon'] ?? '';
		$icon_typicons = $section['icon_typicons'] ?? '';
		$icon_class = vcex_get_icon_class( $section, 'icon' );
		$icon_out = '';
		$label_out = '';

		// Enqueue icon font family.
		if ( $icon_class ) {
			vcex_enqueue_icon_font( $icon_type, $icon_class );
			$icon_out = '<span class="meta-icon ' . esc_attr( $icon_class ) . '" aria-hidden="true"></span>';
		}

		// Parse label.
		if ( $label ) {

			$label_out = '<span class="meta-label">';

				$label_out .= wp_strip_all_tags( $label );

				if ( vcex_validate_boolean( $atts['label_colon'] ) ) {
					$label_out .= ':';
				}

			$label_out .= '</span> ';

		}

		// Display sections.
		switch ( $type ) {

			// Date.
			case 'date':

				$section_html .= '<li class="meta-date">';

					if ( $icon_out ) {
						$section_html .= $icon_out;
					}

					if ( $label_out ) {
						$section_html .= $label_out;
					}

					$date_format = $section['date_format'] ?? '';

					$section_html .= '<time datetime="' . esc_attr( get_the_date( 'Y-m-d' ) ) . '"' . vcex_get_schema_markup( 'publish_date' ) . '>' . get_the_date( $date_format, $post->ID ) . '</time>';

				$section_html .= '</li>';

				break;

			// Author.
			case 'author':

				$section_html .= '<li class="meta-author">';

					if ( $icon_out ) {
						$section_html .= $icon_out;
					}

					if ( $label_out ) {
						$section_html .= $label_out;
					}

					$author_name = get_the_author_meta( 'display_name', $post->post_author );
					$author_name = apply_filters( 'the_author', $author_name );

					$section_html .= '<span class="vcard author"' . vcex_get_schema_markup( 'author_name' ) . '><span class="fn"><a href="' . esc_url( get_author_posts_url( $post->post_author ) ) . '">' . esc_html( $author_name ) . '</a></span></span>';

				$section_html .= '</li>';

				break;

			// Author with Avatar
			case 'author_w_avatar':

				$section_html .= '<li class="meta-author">';

					if ( $label_out ) {
						$section_html .= $label_out;
					}

					$section_html .= '<a class="wpex-inline-flex wpex-items-center" href="' . esc_url( get_author_posts_url( $post->post_author ) ) . '">';

					$avatar_args = array( 'class' => 'wpex-align-middle wpex-rounded-full' );
					$avatar_size = $section['avatar_size'] ?? 25;
					$avatar = get_avatar( $post->post_author, absint( $avatar_size ), '', '', $avatar_args );

					if ( $avatar ) {
						$section_html .= '<span class="meta-author-avatar wpex-mr-10">' . $avatar . '</span>';
					}

					$author_name = get_the_author_meta( 'display_name', $post->post_author );
					$author_name = apply_filters( 'the_author', $author_name );

					$section_html .= '<span class="vcard author"' . vcex_get_schema_markup( 'author_name' ) . '><span class="fn">' . esc_html( $author_name ) . '</span></span>';

				$section_html .= '</a></li>';

				break;

			// Comments.
			// @Todo allow for comments link that can scroll down.
			case 'comments':

				$comment_link = $section['has_link'] ?? false;
				$comment_number = get_comments_number();

				$section_html .= '<li class="meta-comments comment-scroll">';

					if ( $comment_link ) {
						$comments_link_hash = $comment_number ? '#view_comments' : '#comments_reply';
						$section_html .= '<a href="' . esc_url( get_permalink( $post ) . $comments_link_hash ) . '" class="comments-link">';
					}

						if ( $icon_out ) {
							$section_html .= $icon_out;
						}

						if ( $label_out ) {
							$section_html .= $label_out;
						}

						if ( $comment_number == 0 ) {
							$section_html .= esc_html__( '0 Comments', 'total' );
						} elseif ( $comment_number > 1 ) {
							$section_html .= $comment_number .' '. esc_html__( 'Comments', 'total' );
						} else {
							$section_html .= esc_html__( '1 Comment',  'total' );
						}

					if ( $comment_link ) {
						$section_html .= '</a>';
					}

					$section_html .= '</li>';

				break;

			// Post terms.
			case 'post_terms':

				$taxonomy = $section['taxonomy'] ?? '';
				$get_terms = '';

				if ( $is_templatera ) {

					$section_html .= '<li class="meta-post-terms wpex-clr">';

						if ( $icon_out ) {
							$section_html .= $icon_out;
						}

						if ( $label_out ) {
							$section_html .= $label_out;
						}

						$section_html .= '<a href="#">' . esc_html__( 'Sample Item', 'total' ) . '</a>';

					$section_html .= '</li>';

				} elseif ( $taxonomy ) {

					$get_terms = vcex_get_list_post_terms( $taxonomy, true );

					if ( $get_terms ) {

						$section_html .= '<li class="meta-post-terms wpex-clr">';

							if ( $icon_out ) {
								$section_html .= $icon_out;
							}

							if ( $label_out ) {
								$section_html .= $label_out;
							}

							$section_html .= $get_terms;

						$section_html .= '</li>';

					}


				}

				break;

			// Last updated.
			case 'modified_date':

				$section_html .= '<li class="meta-modified-date">';

					if ( $icon_out ) {
						$section_html .= $icon_out;
					}

					if ( $label_out ) {
						$section_html .= $label_out;
					}

					$section_html .= '<time datetime="' . esc_attr( get_the_modified_date( 'Y-m-d' ) ) . '"' . vcex_get_schema_markup( 'date_modified' ) . '>' . get_the_modified_date( $date_format, $post->ID ) . '</time>';

				$section_html .= '</li>';

				break;

				// Callback.
				case 'callback':

					$callback_function = $section['callback_function'] ?? '';

					if ( $callback_function && function_exists( $callback_function ) ) {

						$section_html .= '<li class="meta-callback">';

							if ( $icon_out ) {
								$section_html .= $icon_out;
							}

							if ( $label_out ) {
								$section_html .= $label_out;
							}

							$section_html .= wp_kses_post( call_user_func( $callback_function ) );

						$section_html .= '</li>';

					}

					break;

			// Default - see if the type is a callback function.
			// @todo add li tags if not added by default.
			default:

				$custom_section_output = apply_filters( 'vcex_post_meta_custom_section_output', $type, $icon_class );

				if ( ! empty( $custom_section_output ) ) {
					$section_html .= $custom_section_output;
				}

				break;


		} // end switch.

		if ( $section_html ) {
			$count++;

			if ( ! empty( $separator ) && $count > 1 ) {
				$output .= $separator;
			}

			$output .= $section_html;

		}

	} // end foreach.

$output .= '</ul>';

// @codingStandardsIgnoreLine
echo $output;