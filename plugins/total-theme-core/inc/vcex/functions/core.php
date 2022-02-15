<?php
/**
 * Vcex shortcodes functions.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return theme branding.
 */
function vcex_shortcodes_branding() {
	if ( function_exists( 'wpex_get_theme_branding' ) ) {
		return wpex_get_theme_branding();
	}
	return 'Total Theme';
}

/**
 * Total exclusive setting notice.
 */
function vcex_total_exclusive_notice() {
	return '<div class="vcex-t-exclusive">' . esc_html__( 'This is a Total theme exclusive function.', 'total-theme-core' ) . '</div>';
}

/**
 * Locate shortcode template.
 */
function vcex_get_shortcode_template( $shortcode_tag ) {
	$user_template_path = locate_template( 'vcex_templates/' . $shortcode_tag . '.php' );

	if ( $user_template_path ) {
		return $user_template_path;
	}

	return TTC_PLUGIN_DIR_PATH . 'inc/vcex/templates/' . $shortcode_tag . '.php';
}

/**
 * Check if a given shortcode should display.
 */
function vcex_maybe_display_shortcode( $shortcode_tag, $atts ) {
	$check = true;

	if ( is_admin() && ! wp_doing_ajax() ) {
		$check = false; // shortcodes are for front-end only. Prevents issues with Gutenberg. !!! important !!!
	}

	/**
	 * Filters whether a vcex shortcode should display on the front-end or not.
	 *
	 * @param bool $check
	 */
	$check = (bool) apply_filters( 'vcex_maybe_display_shortcode', $check, $shortcode_tag, $atts );

	return $check;
}

/**
 * Call any shortcode function by it's tagname.
 */
function vcex_do_shortcode_function( $tag, $atts = array(), $content = null ) {
	global $shortcode_tags;

    if ( ! isset( $shortcode_tags[ $tag ] ) ) {
        return false;
    }

    return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
}

/**
 * Return asset path.
 */
function vcex_asset_url( $part = '' ) {
	return TTC_PLUGIN_DIR_URL . 'inc/vcex/assets/' . $part;
}

/**
 * Return asset dir path.
 */
function vcex_asset_dir_path( $part = '' ) {
	return TTC_PLUGIN_DIR_PATH . 'inc/vcex/assets/' . $part;
}

/**
 * Check if currently working in the wpbakery front-end editor.
 */
function vcex_vc_is_inline() {
	if ( function_exists( 'vc_is_inline' ) ) {
		return vc_is_inline();
	}
	return false;
}

/**
 * Get post type cat tax.
 */
function vcex_get_post_type_cat_tax( $post_type = '' ) {
	if ( function_exists( 'wpex_get_post_type_cat_tax' ) ) {
		return wpex_get_post_type_cat_tax( $post_type );
	}

	if ( ! $post_type ) {
		$post_type = get_post_type();
	}

	switch ( $post_type ) {
		case 'post':
			$tax = 'category';
			break;
		case 'portfolio':
			$tax = 'portfolio_category';
			break;
		case 'staff':
			$tax = 'staff_category';
			break;
		case 'testimonials':
			$tax = 'testimonials_category';
			break;
		default:
			$tax = '';
	}

	/**
	 * Filters the post type category taxonomy name.
	 *
	 * @param string $tax
	 */
	$tax = (string) apply_filters( 'wpex_get_post_type_cat_tax', $tax, $post_type );

	return $tax;
}

/**
 * Wrapper for intval with fallback.
 */
function vcex_intval( $val = null, $fallback = null ) {
	if ( 0 === $val ) {
		return 0; // Some settings may need empty values.
	}

	$val = intval( $val ); // sanitize $val first incase it returns 0

	return $val ?: $fallback;
}

/**
 * WPBakery vc_param_group_parse_atts wrapper function.
 */
function vcex_vc_param_group_parse_atts( $atts_string ) {
	if ( function_exists( 'vc_param_group_parse_atts' ) ) {
		return vc_param_group_parse_atts( $atts_string );
	}
	$array = json_decode( urldecode( $atts_string ), true );
	return $array;
}

/**
 * Validate Font Size.
 *
 * @todo rename to vcex_sanitize_font_size()
 */
function vcex_validate_font_size( $input ) {
	if ( strpos( $input, 'px' )
		|| strpos( $input, 'em' ) // includes rem.
		|| strpos( $input, 'vw' )
		|| strpos( $input, 'vmin' )
		|| strpos( $input, 'vmax' )
	) {
		$input = $input;
	} else {
		$input = absint( $input ) . 'px';
	}
	if ( '0px' !== $input && '0em' !== $input ) {
		return esc_attr( $input );
	}
	return '';
}

/**
 * Validate Boolean.
 */
function vcex_validate_boolean( $var ) {
	if ( is_bool( $var ) ) {
        return $var;
    }
    if ( is_string( $var ) ) {
		if ( 'true' === $var || 'yes' === $var ) {
			return true;
		} elseif ( 'false' === $var || 'no' === $var ) {
			return false;
		}
	}
	return (bool) $var;
}

/**
 * Validate px.
 */
function vcex_validate_px( $input ) {
	if ( ! $input ) {
		return;
	}
	if ( 'none' === $input || '0px' === $input ) {
		return '0';
	}
	$input = floatval( $input );
	if ( $input ) {
		return $input . 'px';
	}
}

/**
 * Validate px or percentage value.
 */
function vcex_validate_px_pct( $input ) {
	if ( ! $input ) {
		return;
	}
	if ( 'none' === $input || '0px' === $input ) {
		return '0';
	}
	if ( strpos( $input, '%' ) ) {
		return wp_strip_all_tags( $input );
	}
	if ( $input = floatval( $input ) ) {
		return wp_strip_all_tags( $input ) . 'px';
	}
}

/**
 * Get site default font size.
 */
function vcex_get_body_font_size() {
	if ( function_exists( 'wpex_get_body_font_size' ) ) {
		return wpex_get_body_font_size();
	}

	/**
	 * Filters the site body font size.
	 *
	 * @param string|int $font_size
	 */
	$font_size = apply_filters( 'vcex_get_body_font_size', '13px' );

	return $font_size;
}

/**
 * Check if an attachment id exists.
 */
function vcex_validate_attachment( $attachment = '' ) {
	if ( 'attachment' === get_post_type( $attachment ) ) {
		return $attachment;
	}
}

/**
 * Get encoded vc data.
 */
function vcex_vc_value_from_safe( $value, $encode = false ) {
	if ( function_exists( 'vc_value_from_safe' ) ) {
		return vc_value_from_safe( $value );
	}
	$value = preg_match( '/^#E\-8_/', $value ) ? rawurldecode( base64_decode( preg_replace( '/^#E\-8_/', '', $value ) ) ) : $value;
	if ( $encode ) {
		$value = htmlentities( $value, ENT_COMPAT, 'UTF-8' );
	}
	return $value;
}

/**
 * REturns theme post types.
 */
function vcex_theme_post_types() {
	if ( function_exists( 'wpex_theme_post_types' ) ) {
		return wpex_theme_post_types();
	}
	return array();
}

/**
 * Convert to array, used for the grid filter.
 *
 * @todo use wp_parse_list instead and deprecate.
 */
function vcex_string_to_array( $value = array() ) {

	if ( empty( $value ) && is_array( $value ) ) {
		return null;
	}

	if ( ! empty( $value ) && is_array( $value ) ) {
		return $value;
	}

	$items = preg_split( '/\,[\s]*/', $value );

	foreach ( $items as $item ) {
		if ( strlen( $item ) > 0 ) {
			$array[] = $item;
		}
	}

	return $array;

}

/**
 * Combines multiple top/right/bottom/left fields.
 */
function vcex_combine_trbl_fields( $top = '', $right = '', $bottom = '', $left = '' ) {
	$margins = array();

	if ( $top ) {
		$margins['top'] = 'top:' . wp_strip_all_tags( $top );
	}

	if ( $right ) {
		$margins['right'] = 'right:' . wp_strip_all_tags( $right );
	}

	if ( $bottom ) {
		$margins['bottom'] = 'bottom:' . wp_strip_all_tags( $bottom );
	}

	if ( $left ) {
		$margins['left'] = 'left:' . wp_strip_all_tags( $left );
	}

	if ( $margins ) {
		return implode( '|', $margins );
	}
}

/**
 * Migrate font_container field to individual params.
 */
function vcex_migrate_font_container_param( $font_container_field = '', $target = '', $atts = array() ) {
	if ( empty( $atts[ $font_container_field ] ) ) {
		return $atts;
	}

	$get_typo = vcex_parse_typography_param( $atts[ $font_container_field ] );

	if ( empty( $get_typo ) ) {
		return $atts;
	}

	$params_to_migrate = array(
		'font_size',
		'text_align',
		'line_height',
		'color',
		'font_family',
		'tag',
	);

	foreach( $params_to_migrate as $param ) {

		if ( empty( $get_typo[ $param ] ) ) {
			continue;
		}

		$value = $get_typo[ $param ];

		if ( 'text_align' === $param && ( 'left' === $value || 'justify' === $value ) ) {
			continue; // left text align was never & justify isn't available in the theme so don't migrate
		}

		if ( empty( $atts[ $target . '_' . $param ] ) ) {
			$atts[ $target . '_' . $param ] = $value;
		}

	}

	return $atts;
}

/**
 * Build Query.
 */
function vcex_build_wp_query( $atts ) {
	$query_builder = new TotalThemeCore\Vcex\Query_Builder( $atts );
	return $query_builder->build();
}

/**
 * Get shortcode custom css class.
 */
function vcex_vc_shortcode_custom_css_class( $css = '' ) {
	if ( $css && function_exists( 'vc_shortcode_custom_css_class' ) ) {
		return trim( vc_shortcode_custom_css_class( $css ) );
	}
}

/**
 * Returns inline style tag based on css properties.
 */
function vcex_inline_style( $atts = array(), $add_style = true ) {
	$atts = array_filter( $atts ); // remove empty items.
	if ( $atts && is_array( $atts ) ) {
		$inline_style = new TotalThemeCore\Vcex\Inline_Style( $atts, $add_style );
		return $inline_style->return_style();
	}
}

/**
 * Return post id.
 */
function vcex_get_the_ID() {
	if ( function_exists( 'wpex_get_dynamic_post_id' ) ) {
		return wpex_get_dynamic_post_id();
	}
	return get_the_ID();
}

/**
 * Check if responsiveness is enabled.
 */
function vcex_is_layout_responsive() {
	return apply_filters( 'wpex_is_layout_responsive', get_theme_mod( 'responsive', true ) );
}

/**
 * Return post title.
 */
function vcex_get_the_title() {
	if ( function_exists( 'wpex_title' ) && function_exists( 'wpex_get_dynamic_post_id' ) ) {
		return wpex_title( wpex_get_dynamic_post_id() );
	} else {
		return get_the_title();
	}
}

/**
 * Return post title.
 */
function vcex_get_schema_markup( $location ) {
	if ( function_exists( 'wpex_get_schema_markup' ) ) {
		return wpex_get_schema_markup( $location );
	}
}

/**
 * Return post permalink.
 */
function vcex_get_permalink( $post_id = '' ) {
	if ( function_exists( 'wpex_get_permalink' ) ) {
		return wpex_get_permalink( $post_id );
	}
	return get_permalink();
}

/**
 * Return post class.
 */
function vcex_get_post_class( $class = '', $post_id = null ) {
	return 'class="' . esc_attr( implode( ' ', get_post_class( $class, $post_id ) ) ) . '"';
}

/**
 * Get module header output.
 */
function vcex_get_module_header( $args = array() ) {
	if ( function_exists( 'wpex_get_heading' ) ) {
		$header = wpex_get_heading( $args );
	} else {
		$header = '<h2 class="vcex-module-heading">' . do_shortcode( wp_kses_post( $args['content'] ) ) . '</h2>';
	}

	/**
	 * Filters the vcex shortcode header html.
	 *
	 * @param string $header_html
	 * @param array $header_args
	 */
	$header = apply_filters( 'vcex_get_module_header', $header, $args );

	return $header;
}

/**
 * Returns entry image overlay output.
 */
function vcex_get_entry_image_overlay( $position = '', $shortcode_tag = '', $atts = '' ) {
	if ( empty( $atts['overlay_style'] ) || 'none' === $atts['overlay_style'] ) {
		return '';
	}

	ob_start();
		vcex_image_overlay( $position, $atts['overlay_style'], $atts );
	$overlay = ob_get_clean();

	/**
	 * Filters the entry image overlay html.
	 *
	 * @param string $overlay_html
	 * @param string $overlay_position
	 * @param string $shortcode_tag
	 * @param array $shortcode_attributes
	 */
	$overlay = apply_filters( 'vcex_entry_image_overlay', $overlay, $position, $shortcode_tag, $atts );

	return $overlay;
}

/**
 * Return post content.
 */
function vcex_the_content( $content = '', $context = '' ) {
	if ( empty( $content ) ) {
		return '';
	}
	if ( defined( 'TOTAL_THEME_ACTIVE' ) ) {
		return apply_filters( 'wpex_the_content', wp_kses_post( $content ), $context );
	} else {
		return do_shortcode( shortcode_unautop( wpautop( wp_kses_post( $content ) ) ) );
	}
}

/**
 * Return escaped post title.
 */
function vcex_esc_title( $post = '' ) {
	return the_title_attribute( array(
		'echo' => false,
		'post' => $post,
	) );
}

/**
 * Wrapper for esc_attr with fallback.
 */
function vcex_esc_attr( $val = null, $fallback = null ) {
	if ( ! $val ) {
		$val = $fallback;
	}
	return esc_attr( $val );
}

/**
 * Wrapper for the wpex_get_star_rating function.
 */
function vcex_get_star_rating( $rating = '', $post_id = '', $before = '', $after = '' ) {
	if ( function_exists( 'wpex_get_star_rating' ) ) {
		return wpex_get_star_rating( $rating, $post_id, $before, $after );
	}
	if ( $rating = get_post_meta( get_the_ID(), 'wpex_post_rating', true ) ) {
		echo esc_html( $trating );
	}
}

/**
 * Wrapper for the vcex_get_user_social_links function.
 */
function vcex_get_user_social_links( $user_id = '', $display = 'icons', $attr = '', $before = '', $after = '' ) {
	if ( function_exists( 'wpex_get_user_social_links' ) ) {
		return wpex_get_user_social_links( $user_id, $display, $attr, $before, $after );
	}
}

/**
 * Wrapper for the wpex_get_social_button_class function.
 */
function vcex_get_social_button_class( $style = 'default' ) {
	if ( function_exists( 'wpex_get_social_button_class' ) ) {
		return wpex_get_social_button_class( $style );
	}
}

/**
 * Get image filter class.
 */
function vcex_image_filter_class( $filter = '' ) {
	if ( function_exists( 'wpex_image_filter_class' ) ) {
		return wpex_image_filter_class( $filter );
	}
}

/**
 * Get image hover classes.
 */
function vcex_image_hover_classes( $hover = '' ) {
	if ( function_exists( 'wpex_image_hover_classes' ) ) {
		return wpex_image_hover_classes( $hover );
	}
}

/**
 * Get image overlay classes.
 */
function vcex_image_overlay_classes( $overlay = '', $args = array() ) {
	if ( function_exists( 'wpex_overlay_classes' ) ) {
		return wpex_overlay_classes( $overlay, $args );
	}
}

/**
 * Return image overlay.
 */
function vcex_image_overlay( $position = '', $style = '', $atts = '' ) {
	if ( function_exists( 'wpex_overlay' ) ) {
		wpex_overlay( $position, $style, $atts );
	}
}

/**
 * Return button classes.
 */
function vcex_get_button_classes( $style = '', $color = '', $size = '', $align = '' ) {
	if ( function_exists( 'wpex_get_button_classes' ) ) {
		return wpex_get_button_classes( $style, $color, $size, $align );
	}
}

/**
 * Return after media content.
 */
function vcex_get_entry_media_after( $instance = '' ) {
	return apply_filters( 'wpex_get_entry_media_after', '', $instance ); // do NOT rename filter!!!
}

/**
 * Return excerpt.
 */
function vcex_get_excerpt( $args = '' ) {
	if ( function_exists( 'wpex_get_excerpt' ) ) {
		return wpex_get_excerpt( $args );
	} else {
		$excerpt_length = $args['length'] ?? 40;
		return wp_trim_words( get_the_excerpt(), $excerpt_length, null );
	}
}

/**
 * Return thumbnail.
 */
function vcex_get_post_thumbnail( $args = '' ) {
	if ( function_exists( 'wpex_get_post_thumbnail' ) ) {
		return wpex_get_post_thumbnail( $args );
	}
	if ( isset( $args['attachment'] ) ) {
		$size = $args['size'] ?? 'full';
		return wp_get_attachment_image( $args[ 'attachment' ], $size );
	}
}

/**
 * Return WooCommerce price
 */
function vcex_get_woo_product_price( $post_id = '' ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	if ( 'product' == get_post_type( $post_id ) ) {
		$product = wc_get_product( $post_id );
		$price = $product->get_price_html();
		if ( $price ) {
			return $price;
		}
	}
}

/**
 * Return button arrow.
 *
 * @todo deprecate.
 */
function vcex_readmore_button_arrow() {
	if ( is_rtl() ) {
		$arrow = '&larr;';
	} else {
		$arrow = '&rarr;';
	}

	/**
	 * Filters the readmore button arrow
	 *
	 * @param string $arrow
	 */
	$arrow = apply_filters( 'wpex_readmore_button_arrow', $arrow );

	return $arrow;
}

/**
 * Return font weight class
 */
function vcex_font_weight_class( $font_weight = '' ) {
	if ( ! $font_weight ) {
		return;
	}

	$font_weights = array(
		'hairline'  => 'wpex-font-hairline',
		'100'       => 'wpex-font-hairline',

		'thin'      => 'wpex-font-thin',
		'200'       => 'wpex-font-thin',

		'normal'    => 'wpex-font-normal',
		'400'       => 'wpex-font-normal',

		'semibold'  => 'wpex-font-semibold',
		'600'       => 'wpex-font-semibold',

		'bold'      => 'wpex-font-bold',
		'700'       => 'wpex-font-bold',

		'extrabold' => 'wpex-font-extrabold',
		'bolder'    => 'wpex-font-extrabold',
		'800'       => 'wpex-font-extrabold',

		'black'     => 'wpex-font-black',
		'900'       => 'wpex-font-black',
	);

	if ( isset( $font_weights[$font_weight] ) ) {
		return $font_weights[$font_weight];
	}
}

/**
 * Get theme term data.
 */
function vcex_get_term_data() {
	if ( function_exists( 'wpex_get_term_data' ) ) {
		return wpex_get_term_data();
	}
}

/**
 * Get term thumbnail.
 */
function vcex_get_term_thumbnail_id( $term_id = '' ) {
	if ( is_callable( array( 'TotalThemeCore\Term_Thumbnails', 'get_term_thumbnail_id' ) ) ) {
		return TotalThemeCore\Term_Thumbnails::get_term_thumbnail_id( $term_id );
	}
}

/**
 * Get post video.
 */
function vcex_get_post_video( $post_id = '' ) {
	if ( function_exists( 'wpex_get_post_video' ) ) {
		return wpex_get_post_video( $post_id );
	}
}

/**
 * Get post video html.
 */
function vcex_get_post_video_html() {
	if ( function_exists( 'wpex_get_post_video_html' ) ) {
		return wpex_get_post_video_html();
	}
}

/**
 * Get post video html.
 */
function vcex_video_oembed( $video = '', $classes = '', $params = array() ) {
	if ( function_exists( 'wpex_video_oembed' ) ) {
		return wpex_video_oembed( $video, $classes, $params );
	}
	return wp_oembed_get( $video );
}

/**
 * Get post video oembed URL.
 */
function vcex_get_post_video_oembed_url( $post_id = '' ) {
	if ( function_exists( 'wpex_get_post_video_oembed_url' ) ) {
		return wpex_get_post_video_oembed_url( $post_id );
	}
}

/**
 * Get post video oembed URL.
 */
function vcex_get_video_embed_url( $video = '' ) {
	if ( function_exists( 'wpex_get_video_embed_url' ) ) {
		return wpex_get_video_embed_url( $video );
	}
}

/**
 * Get hover animation class
 */
function vcex_hover_animation_class( $animation = '' ) {
	if ( function_exists( 'wpex_hover_animation_class' ) ) {
		return wpex_hover_animation_class( $animation );
	}
}

/**
 * Get first post term.
 */
function vcex_get_first_term( $post = '', $taxonomy = 'category', $terms = '' ) {
	if ( function_exists( 'wpex_get_first_term' ) ) {
		return wpex_get_first_term( $post, $taxonomy, $terms );
	}
}

/**
 * Get post first term link.
 */
function vcex_get_first_term_link( $post = '', $taxonomy = 'category', $terms = '' ) {
	if ( function_exists( 'wpex_get_first_term_link' ) ) {
		return wpex_get_first_term_link( $post, $taxonomy, $terms );
	}
}

/**
 * Get post terms.
 */
function vcex_get_list_post_terms( $taxonomy = 'category', $show_links = true ) {
	if ( function_exists( 'wpex_get_list_post_terms' ) ) {
		return wpex_get_list_post_terms( $taxonomy, $show_links );
	}
}

/**
 * Get pagination.
 */
if ( ! function_exists( 'vcex_pagination' ) ) {
	function vcex_pagination( $query = '', $echo = true ) {
		if ( function_exists( 'wpex_pagination' ) ) {
			return wpex_pagination( $query, $echo );
		}
		if ( $query ) {
			global $wp_query;
			$temp_query = $wp_query;
			$wp_query = $query;
		}
		ob_start();
		posts_nav_link();
		$wp_query = $temp_query;
		return ob_get_clean();
	}
}

/**
 * Filters module grid to return active blocks.
 */
function vcex_filter_grid_blocks_array( $blocks ) {
	$new_blocks = array();
	foreach ( $blocks as $key => $value ) {
		if ( 'true' == $value ) {
			$new_blocks[$key] = '';
		}
	}
	return $new_blocks;
}

/**
 * Returns correct classes for grid modules
 * Does NOT use post_class to prevent conflicts.
 */
function vcex_grid_get_post_class( $classes = array(), $post_id = '', $media_check = true ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	// Get post type.
	$post_type = get_post_type( $post_id );

	// Add post ID class.
	$classes[] = 'post-' . sanitize_html_class( $post_id );

	// Add entry class.
	$classes[] = 'entry';

	// Add type class.
	$classes[] = 'type-' . sanitize_html_class( $post_type );

	// Add has media class.
	if ( $media_check && function_exists( 'wpex_post_has_media' ) ) {
		if ( wpex_post_has_media( $post_id, true ) ) {
			$classes[] = 'has-media';
		} else {
			$classes[] = 'no-media';
		}
	}

	// Add terms.
	if ( $terms = vcex_get_post_term_classes( $post_id, $post_type ) ) {
		$classes[] = $terms;
	}

	// Custom link class.
	if ( function_exists( 'wpex_get_post_redirect_link' ) && wpex_get_post_redirect_link() ) {
		$classes[] = 'has-redirect';
	}

	/**
	 * Filters the grid post classes.
	 *
	 * @param array $classes
	 */
	$classes = (array) apply_filters( 'vcex_grid_get_post_class', $classes );

	// Sanitize classes
	$classes = array_map( 'esc_attr', $classes );

	return 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
}

/**
 * Returns entry classes for vcex module entries.
 *
 */
function vcex_get_post_term_classes( $post_id = '', $post_type = '' ) {
	if ( ! defined( 'TOTAL_THEME_ACTIVE' ) ) {
		return array();
	}

	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	if ( ! $post_type ) {
		$post_type = get_post_type( $post_id );
	}

	// Define vars.
	$classes = array();

	// Loop through tax objects and save in taxonomies var.
	$taxonomies = get_object_taxonomies( $post_type, 'names' );

	// Return of there is an error.
	if ( is_wp_error( $taxonomies ) || ! $taxonomies ) {
		return;
	}

	// Loop through taxomies.
	foreach ( $taxonomies as $tax ) {

		// Get terms.
		$terms = get_the_terms( $post_id, $tax );

		// Make sure terms aren't empty before loop.
		if ( $terms && ! is_wp_error( $terms ) ) {

			// Loop through terms.
			foreach ( $terms as $term ) {

				// Set prefix as taxonomy name.
				$prefix = esc_html( $term->taxonomy );

				// Add class if we have a prefix.
				if ( $prefix ) {

					// Get total post types to parse.
					$parse_types = vcex_theme_post_types();
					if ( in_array( $post_type, $parse_types ) ) {
						$search  = array( $post_type . '_category', $post_type . '_tag' );
						$replace = array( 'cat', 'tag' );
						$prefix  = str_replace( $search, $replace, $prefix );
					}

					// Category prefix.
					if ( 'category' === $prefix ) {
						$prefix = 'cat';
					}

					// Add term.
					$classes[] = sanitize_html_class( $prefix . '-' . $term->term_id );

					// Add term parent.
					if ( $term->parent ) {
						$classes[] = sanitize_html_class( $prefix . '-' . $term->parent );
					}

				}

			}
		}
	}

	// Sanitize classes.
	$classes_escaped = array_map( 'esc_attr', $classes );

	if ( $classes_escaped ) {
		return implode( ' ', $classes_escaped );
	}
}

/**
 * Returns correct class for columns.
 */
function vcex_get_grid_column_class( $atts ) {
	if ( isset( $atts['single_column_style'] ) && 'left_thumbs' === $atts['single_column_style'] ) {
		return;
	}
	$return_class = '';
	if ( isset( $atts['columns'] ) ) {
		$return_class .= ' span_1_of_' . sanitize_html_class( $atts['columns'] );
	}
	if ( ! empty( $atts['columns_responsive_settings'] ) ) {
		$rs = vcex_parse_multi_attribute( $atts['columns_responsive_settings'], array() );
		foreach ( $rs as $key => $val ) {
			if ( $val ) {
				$return_class .= ' span_1_of_' . sanitize_html_class( $val ) . '_' . sanitize_html_class( $key );
			}
		}
	}
	return trim( $return_class );
}

/**
 * Returns correct CSS for custom button color based on style.
 */
function vcex_get_button_custom_color_css( $style = '', $color ='' ) {
	if ( function_exists( 'wpex_get_button_custom_color_css' ) ) {
		return wpex_get_button_custom_color_css( $style, $color );
	}
}

/**
 * Get carousel settings.
 */
function vcex_get_carousel_settings( $atts, $shortcode ) {
	$settings = array(
		'nav'                  => ! empty( $atts['arrows'] ) ? $atts['arrows'] : 'true',
		'dots'                 => ! empty( $atts['dots'] ) ? $atts['dots'] : 'false',
		'autoplay'             => ! empty( $atts['auto_play'] ) ? $atts['auto_play'] : 'false',
		'loop'                 => ! empty( $atts['infinite_loop'] ) ? $atts['infinite_loop'] : 'true',
		'center'               => ! empty( $atts['center'] ) ? $atts[ 'center'] : 'false',
		'smartSpeed'           => ! empty( $atts['animation_speed'] ) ? absint( $atts['animation_speed'] ) : 250,
		'items'                => ! empty( $atts['items'] ) ? intval( $atts['items'] ) : 4,
		'slideBy'              => ! empty( $atts['items_scroll'] ) ? intval( $atts['items_scroll'] ) : 1,
		'autoplayTimeout'      => ! empty( $atts['timeout_duration'] ) ? intval( $atts['timeout_duration'] ) : 5000, // cant be 0
		'margin'               => ! empty( $atts['items_margin'] ) ? absint( $atts['items_margin'] ) : 15,
		'itemsTablet'          => ! empty( $atts['tablet_items'] ) ? absint( $atts['tablet_items'] ) : 3,
		'itemsMobileLandscape' => ! empty( $atts['mobile_landscape_items'] ) ? absint( $atts['mobile_landscape_items'] ) : 2,
		'itemsMobilePortrait'  => ! empty( $atts['mobile_portrait_items'] ) ? absint( $atts['mobile_portrait_items'] ) : 1,
	);

	if ( isset( $atts['style'] ) && $atts['style'] == 'no-margins' ) {
		$settings[ 'margin' ] = 0;
	}

	if ( isset( $atts['auto_width'] ) && 1 !== $settings['items'] ) {
		$settings['autoWidth'] = vcex_esc_attr( $atts[ 'auto_width' ], false );
	}

	if ( isset( $atts[ 'auto_height' ] ) ) {
		$settings['autoHeight'] = vcex_esc_attr( $atts[ 'auto_height' ], false );
	}

	$settings = apply_filters( 'vcex_get_carousel_settings', $settings, $atts, $shortcode );

	foreach( $settings as $k => $v ) {
		if ( 'true' == $v || 'false' == $v ) {
			$settings[$k] = vcex_validate_boolean( $v );
		}
	}

	return htmlspecialchars( wp_json_encode( $settings ) );
}

/**
 * Helper function enqueues icon fonts from Visual Composer.
 */
function vcex_enqueue_icon_font( $family = '', $icon = '' ) {
	if ( ! $icon ) {
		return;
	}

	// If font family isn't defined lets get it from the icon class.
	if ( ! $family ) {
		$family = vcex_get_icon_type_from_class( $icon );
	}

	// Return if we are using ticons.
	if ( 'ticons' === $family || ! $family ) {
		wp_enqueue_style( 'ticons' );
		return;
	}

	// Check for custom enqueue.
	$fonts = vcex_get_icon_font_families();

	// Custom stylesheet check.
	if ( ! empty( $fonts[$family]['style'] ) ) {
		wp_enqueue_style( $fonts[$family]['style'] );
		return;
	}

	// Default vc font icons.
	if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
		vc_icon_element_fonts_enqueue( $family );
	}
}

/**
 * Returns animation class and loads animation js.
 */
function vcex_get_css_animation( $css_animation = '' ) {
	if ( ! $css_animation || 'none' === $css_animation ) {
		return;
	}

	wp_enqueue_script( 'vc_waypoints' );
	wp_enqueue_style( 'vc_animate-css' );

	return ' wpb_animate_when_almost_visible wpb_' . sanitize_html_class( $css_animation ) . ' ' . esc_attr( $css_animation );
}

/**
 * Return unique ID for responsive class.
 *
 * @todo deprecate | no longer used.
 */
function vcex_get_reponsive_unique_id( $unique_id = '' ) {
	return $unique_id ? '.wpex-' . $unique_id : uniqid( 'wpex-' );
}

/**
 * Return responsive font-size data.
 *
 * @deprecated Since 5.2 in exchange for inline style tags.
 */
function vcex_get_responsive_font_size_data( $value ) {
	if ( ! $value ) {
		return;
	}

	// Not needed for simple font_sizes.
	if ( strpos( $value, '|' ) === false ) {
		return;
	}

	// Parse data to return array.
	$data = vcex_parse_multi_attribute( $value );

	if ( ! $data && ! is_array( $data ) ) {
		return;
	}

	wp_enqueue_script( 'vcex-responsive-css' );

	$sanitized_data = array();

	// Sanitize.
	foreach ( $data as $key => $val ) {
		$sanitized_data[$key] = vcex_validate_font_size( $val, 'font_size' );
	}

	return $sanitized_data;
}

/**
 * Return responsive font-size data.
 *
 * @deprecated Since 5.2 in exchange for inline style tags.
 */
function vcex_get_module_responsive_data( $atts, $type = '' ) {
	if ( ! $atts ) {
		return; // No need to do anything if atts is empty
	}

	wp_enqueue_script( 'vcex-responsive-css' );

	$return = array();
	$parsed_data = array();
	$settings = array( 'font_size' );

	if ( $type && ! is_array( $atts ) ) {
		$settings = array( $type );
		$atts = array( $type => $atts );
	}

	foreach ( $settings as $setting ) {

		if ( 'font_size' === $setting ) {

			$value = $atts['font_size'] ?? '';

			if ( ! $value ) {
				break;
			}

			$value = vcex_get_responsive_font_size_data( $value );

			if ( $value ) {
				$parsed_data['font-size'] = $value;
			}

		}

	}

	if ( $parsed_data ) {
		return "data-wpex-rcss='" . htmlspecialchars( wp_json_encode( $parsed_data ) ) . "'";
	}
}

/**
 * Get unique element classname.
 */
function vcex_element_unique_classname( $prefix = 'vcex' ) {
	return sanitize_html_class( uniqid( $prefix . '_' ) );
}

/**
 * Get responsive CSS for given element.
 */
function vcex_element_responsive_css( $atts = array(), $target = '' ) {
	if ( ! $atts || ! $target ) {
		return;
	}

	$css = '';
	$css_list = array();

	// Sanitize target (should always be a unique classname).
	$target = '.' . trim( sanitize_html_class( $target ) );

	// Get font size.
	if ( ! empty( $atts['font_size'] ) && false !== strpos( $atts['font_size'], '|' ) ) {

		$font_size = $atts['font_size'];

		// Parse data to return array.
		$font_size_opts = vcex_parse_multi_attribute( $font_size );

		if ( is_array( $font_size_opts ) ) {
			foreach( $font_size_opts as $font_size_device => $font_size_v ) {
				$safe_font_size = vcex_validate_font_size( $font_size_v );
				if ( $safe_font_size ) {
					$css_list[$font_size_device]['font-size'] = $safe_font_size;
				}
			}
		}

	}

	if ( $css_list ) {

		foreach ( $css_list as $device => $device_properties ) {

			$media_rule = vcex_get_css_media_rule( $device );

			if ( $media_rule ) {
				$css .= $media_rule . '{';
			}

			$css .= $target . '{';

				foreach( $device_properties as $property_k => $property_v ) {
					$css .= $property_k . ':' . esc_attr( $property_v ) . '!important;';
				}

			$css .= '}';

			if ( $media_rule ) {
				$css .= '}';
			}

		}

	}

	return $css;
}

/**
 * Get responsive CSS from an element attribute.
 */
function vcex_responsive_attribute_css( $attribute = '', $target_element = '', $target_property = '' ) {
	$values = vcex_parse_multi_attribute( $attribute );

	if ( ! is_array( $values ) || empty( $values ) ) {
		return;
	}

	$css = '';
	$safe_target = '.' . sanitize_html_class( $target_element );
	$safe_property = wp_strip_all_tags( trim( $target_property ) );

	foreach( $values as $device_abrev => $value ) {

		// Get CSS from value, pass through vcex_inline_style for sanitization.
		$bk_css = vcex_inline_style( array(
			$target_property => $value,
		), false );

		if ( ! $bk_css ) {
			continue;
		}

		$media_rule = vcex_get_css_media_rule( $device_abrev );

		if ( $media_rule ) {

			$css .= $media_rule . '{';
				$css .= $safe_target . '{';
					$css .= esc_attr( $bk_css );
				$css .= '}';
			$css .= '}';

		} else {
			$css .= $safe_target . '{';
				$css .= esc_attr( $bk_css );
			$css .= '}';
		}

	}

	return $css;
}

/**
 * Return breakpoint widths.
 */
function vcex_get_css_breakpoints() {
	$breakpoints = array(
		'tl' => '1024px',
		'tp' => '959px',
		'pl' => '767px',
		'pp' => '479px',
	);

	/**
	 * Filters the css breakpoints used for responsive vcex inputs.
	 *
	 * @param array $breakpoints
	 */
	$breakpoints = (array) apply_filters( 'vcex_css_breakpoints', $breakpoints );

	return $breakpoints;
}

/**
 * Return the @media rule for a specific breakpoint.
 */
function vcex_get_css_media_rule( $breakpoint = '' ) {
	if ( ! $breakpoint || 'd' === $breakpoint ) {
		return;
	}

	$breakpoints = vcex_get_css_breakpoints();

	if ( ! empty( $breakpoints[$breakpoint] ) ) {
		return '@media (max-width:' . esc_attr( $breakpoints[$breakpoint] ) . ')';
	}
}

/**
 * Get Extra class.
 */
function vcex_get_extra_class( $classes = '' ) {
	$classes = trim( $classes );
	if ( $classes ) {
		return esc_attr( str_replace( '.', '', $classes ) );
	}
}

/**
 * Generates various types of HTML based on a value.
 *
 * @todo deprecate
 */
function vcex_html( $type, $value, $trim = false ) {
	$return = '';

	// Return if value is empty.
	if ( ! $value ) {
		return;
	}

	// ID attribute.
	if ( 'id_attr' === $type ) {
		$value  = trim ( str_replace( '#', '', $value ) );
		$value  = str_replace( ' ', '', $value );
		if ( $value ) {
			$return = ' id="'. esc_attr( $value ) .'"';
		}
	}

	// Title attribute.
	if ( 'title_attr' === $type ) {
		$return = ' title="'. esc_attr( $value ) .'"';
	}

	// Link Target.
	elseif ( 'target_attr' === $type ) {
		if ( 'blank' === $value
			|| '_blank' === $value
			|| strpos( $value, 'blank' ) ) {
			$return = ' target="_blank"';
		}
	}

	// Link rel.
	elseif ( 'rel_attr' === $type ) {
		if ( 'nofollow' === $value ) {
			$return = ' rel="nofollow"';
		}
	}

	if ( $trim ) {
		return trim( $return );
	} else {
		return $return;
	}
}

/**
 * Notice when no posts are found.
 */
function vcex_no_posts_found_message( $atts = array() ) {
	if ( ! empty( $atts['no_posts_found_message'] ) ) {
		return '<div class="vcex-no-posts-found">' . esc_html( $atts['no_posts_found_message'] ) . '</div>';
	}

	// Default message.
	$message = null;
	$check = false;

	if ( vcex_vc_is_inline() || ( isset( $atts['auto_query'] ) && 'true' == $atts['auto_query'] ) ) {
		$check = true;
	}

	$check = (bool) apply_filters( 'vcex_has_no_posts_found_message', $check, $atts );

	if ( $check ) {
		$message = '<div class="vcex-no-posts-found">' . esc_html__( 'No posts found for your query.', 'total-theme-core' ) . '</div>';
	}

	/**
	 * Apply filters to the no posts found message.
	 *
	 * @param string $message
	 * @param array $shortcode_atts
	 */
	$message = apply_filters( 'vcex_no_posts_found_message', $message, $atts );

	return $message;
}

/**
 * Echos unique ID html for VC modules.
 */
function vcex_unique_id( $id = '' ) {
	echo vcex_get_unique_id( $id );
}

/**
 * Returns unique ID html for VC modules.
 */
function vcex_get_unique_id( $id = '' ) {
	if ( $id ) {
		return ' id="' . esc_attr( $id ) . '"'; // do not remove empty space at front!!
	}
}

/**
 * Returns lightbox image.
 */
function vcex_get_lightbox_image( $thumbnail_id = '' ) {
	if ( function_exists( 'wpex_get_lightbox_image' ) ) {
		return wpex_get_lightbox_image( $thumbnail_id );
	} else {
		return esc_url( wp_get_attachment_url(  $thumbnail_id ) );
	}
}


/**
 * Returns term color.
 */
function vcex_get_term_color( $term ) {
	$term = get_term( $term );
	if ( ! $term ) {
		return;
	}
	if ( is_callable( 'TotalThemeCore\Term_Colors::get_term_color' ) ) {
		return TotalThemeCore\Term_Colors::get_term_color( $term );
	}
}

/**
 * Returns attachment data
 */
function vcex_get_attachment_data( $attachment = '', $return = 'array' ) {
	if ( function_exists( 'wpex_get_attachment_data' ) ) {
		return wpex_get_attachment_data( $attachment, $return );
	}

	if ( ! $attachment || 'none' === $return ) {
		return;
	}

	switch ( $return ) {
		case 'url':
		case 'src':
			return wp_get_attachment_url( $attachment );
			break;
		case 'alt':
			return get_post_meta( $attachment, '_wp_attachment_image_alt', true );
			break;
		case 'title':
			return get_the_title( $attachment );
			break;
		case 'caption':
			return wp_get_attachment_caption( $attachment );
			break;
		case 'description':
			return get_post_field( 'post_content', $attachment );
			break;
		case 'video':
			return esc_url( get_post_meta( $attachment, '_video_url', true ) );
			break;
		default:

			$url = wp_get_attachment_url( $attachment );

			return array(
				'url'         => $url,
				'src'         => $url, // fallback
				'alt'         => get_post_meta( $attachment, '_wp_attachment_image_alt', true ),
				'title'       => get_the_title( $attachment ),
				'caption'     => wp_get_attachment_caption( $attachment ),
				'description' => get_post_field( 'post_content', $attachment ),
				'video'       => esc_url( get_post_meta( $attachment, '_video_url', true ) ),
			);
			break;
	}
}

/**
 * Returns post gallery ID's
 */
function vcex_get_post_gallery_ids( $post_id = '' ) {

	/**
	 * Filters the post gallery image ids before trying to fetch them.
	 *
	 * @param array $ids
	 */
	$filter_val = apply_filters( 'vcex_pre_get_post_gallery_ids', null );

	if ( $filter_val ) {
		return $filter_val;
	}

	if ( function_exists( 'wpex_get_gallery_ids' ) ) {
		return wpex_get_gallery_ids( $post_id );
	}

	$attachment_ids = '';

	if ( ! $post_id ) {
		$post_id = vcex_get_the_ID();
	}

	if ( class_exists( 'WC_product' ) && 'product' == get_post_type( $post_id ) ) {
		$product = new WC_product( $post_id );
		if ( $product && method_exists( $product, 'get_gallery_image_ids' ) ) {
			$attachment_ids = $product->get_gallery_image_ids();
		}
	}

	$attachment_ids = $attachment_ids ?: get_post_meta( $post_id, '_easy_image_gallery', true );

	if ( $attachment_ids ) {
		if ( is_string( $attachment_ids ) ) {
			$attachment_ids = explode( ',', $attachment_ids );
		}
		$attachment_ids = array_values( array_filter( $attachment_ids, 'wpex_sanitize_gallery_id' ) );
		return apply_filters( 'wpex_get_post_gallery_ids', $attachment_ids );
	}
}

/**
 * Used to enqueue styles for Visual Composer modules.
 *
 * @todo deprecate.
 */
function vcex_enque_style( $type = '', $value = '' ) {
	if ( 'ilightbox' === $type || 'lightbox' === $type ) {
		if ( function_exists( 'wpex_enqueue_lightbox_scripts' ) ) {
			wpex_enqueue_lightbox_scripts();
		} elseif ( function_exists( 'wpex_enqueue_ilightbox_skin' ) ) {
			wpex_enqueue_ilightbox_skin( $value );
		}
	} elseif ( 'hover-animations' === $type ) {
		wp_enqueue_style( 'wpex-hover-animations' );
	}
}

/**
 * Helper function for building links using link param.
 */
function vcex_build_link( $link, $fallback = '' ) {
	if ( empty( $link ) ) {
		return $fallback;
	}

	// Return if there isn't any link.
	if ( '||' == $link || '|||' == $link || '||||' == $link ) {
		return;
	}

	// Return simple link escaped (fallback for old textfield input).
	if ( false === strpos( $link, 'url:' ) ) {
		return esc_url( $link );
	}

	// Build link.
	// Needs to use total function to fix issue with fallbacks.
	$link = vcex_parse_multi_attribute( $link, array( 'url' => '', 'title' => '', 'target' => '', 'rel' => '' ) );

	// Sanitize.
	$link = is_array( $link ) ? array_map( 'trim', $link ) : '';

	return $link;
}

/**
 * Returns link data (used for fallback link settings).
 */
function vcex_get_link_data( $return, $link, $fallback = '' ) {
	$link = vcex_build_link( $link, $fallback );

	if ( 'url' === $return ) {
		if ( is_array( $link ) && ! empty( $link['url'] ) ) {
			return $link['url'];
		} else {
			return is_array( $link ) ? $fallback : $link;
		}
	}

	if ( 'title' === $return ) {
		if ( is_array( $link ) && ! empty( $link['title'] ) ) {
			return $link['title'];
		} else {
			return $fallback;
		}
	}

	if ( 'target' === $return ) {
		if ( is_array( $link ) && ! empty( $link['target'] ) ) {
			return $link['target'];
		} else {
			return $fallback;
		}
	}

	if ( 'rel' === $return ) {
		if ( is_array( $link ) && ! empty( $link['rel'] ) ) {
			return $link['rel'];
		} else {
			return $fallback;
		}
	}
}

/**
 * Get source value.
 */
function vcex_get_source_value( $source = '', $atts = array() ) {
	if ( empty( $source ) ) {
		return;
	}
	$source_val = new TotalThemeCore\Vcex\Source_Value( $source, $atts );
	return $source_val->get_value();
}

/**
 * Return shortcode CSS.
 *
 * @todo rename to vcex_get_wpb_shortcodes_custom_css
 */
function vcex_wpb_shortcodes_custom_css( $post_id = '' ) {
	$meta = get_post_meta( $post_id, '_wpb_shortcodes_custom_css', true );
	if ( $meta ) {
		return '<style data-type="vc_shortcodes-custom-css">' . wp_strip_all_tags( $meta ) . '</style>';
	}
}

/**
 * Get shortcode style classes based on global params.
 */
function vcex_get_shortcode_extra_classes( $atts = array(), $shortcode_tag = '' ) {
	if ( empty( $atts ) ) {
		return array();
	}

	$extra_classes = array();

	if ( isset( $atts['text_align'] ) ) {
		$extra_classes[] = vcex_parse_text_align_class( $atts['text_align'] );
	}

	if ( isset( $atts['font_size'] ) ) {
		$extra_classes[] = vcex_parse_font_size_class( $atts['font_size'] );
	}

	if ( isset( $atts['bottom_margin'] ) ) {
		$extra_classes[] = vcex_parse_margin_class( $atts['bottom_margin'], 'wpex-mb-' );
	}

	if ( isset( $atts['padding_all'] ) ) {
		$extra_classes[] = vcex_parse_padding_class( $atts['padding_all'] );
	}

	if ( isset( $atts['padding_y'] ) ) {
		$extra_classes[] = vcex_parse_padding_class( $atts['padding_y'], 'y' );
	}

	if ( isset( $atts['border_style'] ) ) {
		$extra_classes[] = vcex_parse_border_style_class( $atts['border_style'] );
	}

	if ( isset( $atts['border_width'] ) ) {
		$extra_classes[] = vcex_parse_border_width_class( $atts['border_width'] );
	}

	if ( isset( $atts['border_radius'] ) ) {
		$extra_classes[] = vcex_parse_border_radius_class( $atts['border_radius'] );
	}

	if ( ! empty( $atts['visibility'] ) ) {
		$extra_classes[] = vcex_parse_visibility_class( $atts['visibility'] );
	}

	if ( isset( $atts['shadow'] ) ) {
		$extra_classes[] = vcex_parse_shadow_class( $atts['shadow'] );
	}

	if ( isset( $atts['shadow_hover'] ) ) {
		$extra_classes[] = vcex_parse_shadow_class( $atts['shadow_hover'], 'hover' );
	}

	if ( isset( $atts['css_animation'] ) ) {
		$extra_classes[] = trim( vcex_get_css_animation( $atts['css_animation'] ) );
	}

	if ( ! empty( $atts['el_class'] ) ) {
		$extra_classes[] = vcex_get_extra_class( $atts['el_class'] ); // add custom classes last.
	} elseif ( ! empty( $atts['classes'] ) ) {
		$extra_classes[] = vcex_get_extra_class( $atts['classes'] );
	}

	if ( isset( $atts['css'] ) ) {
		$extra_classes[] = vcex_vc_shortcode_custom_css_class( $atts['css'] );
	}

	return array_filter( $extra_classes ); // return extra classes and remove empty vars.
}

/**
 * Returns array of carousel settings
 */
function vcex_vc_map_carousel_settings( $dependency = array(), $group = '' ) {
	$settings = array(
		array(
			'type'       => 'vcex_subheading',
			'param_name' => 'vcex_subheading__carousel',
			'text'       => esc_html__( 'Carousel Settings', 'total-theme-core' ),
		),
		array(
			'type' => 'vcex_ofswitch',
			'heading' => esc_html__( 'Arrows', 'total-theme-core' ),
			'param_name' => 'arrows',
			'std' => 'true',
		),
		array(
			'type' => 'vcex_carousel_arrow_styles',
			'heading' => esc_html__( 'Arrows Style', 'total-theme-core' ),
			'param_name' => 'arrows_style',
			'dependency' => array( 'element' => 'arrows', 'value' => 'true' ),
		),
		array(
			'type' => 'vcex_carousel_arrow_positions',
			'heading' => esc_html__( 'Arrows Position', 'total-theme-core' ),
			'param_name' => 'arrows_position',
			'dependency' => array( 'element' => 'arrows', 'value' => 'true' ),
			'std' => 'default',
		),
		array(
			'type' => 'vcex_ofswitch',
			'heading' => esc_html__( 'Dot Navigation', 'total-theme-core' ),
			'param_name' => 'dots',
			'std' => 'false',
		),
		array(
			'type' => 'vcex_ofswitch',
			'heading' => esc_html__( 'Auto Play', 'total-theme-core' ),
			'param_name' => 'auto_play',
			'std' => 'false',
			'admin_label' => true,
		),
		array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Autoplay interval timeout.', 'total-theme-core' ),
			'param_name' => 'timeout_duration',
			'value' => '5000',
			'description' => esc_html__( 'Time in milliseconds between each auto slide. Default is 5000.', 'total-theme-core' ),
			'dependency' => array( 'element' => 'auto_play', 'value' => 'true' ),
		),
		array(
			'type' => 'vcex_ofswitch',
			'heading' => esc_html__( 'Infinite Loop', 'total-theme-core' ),
			'param_name' => 'infinite_loop',
			'std' => 'true',
		),
		array(
			'type' => 'vcex_ofswitch',
			'heading' => esc_html__( 'Center Item', 'total-theme-core' ),
			'param_name' => 'center',
			'std' => 'false',
		),
		array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Animation Speed', 'total-theme-core' ),
			'param_name' => 'animation_speed',
			'value' => '250',
			'description' => esc_html__( 'Default is 250 milliseconds. Enter 0.0 to disable.', 'total-theme-core' ),
		),
		array(
			'type' => 'vcex_ofswitch',
			'std' => 'false',
			'heading' => esc_html__( 'Auto Width', 'total-theme-core' ),
			'param_name' => 'auto_width',
			'description' => esc_html__( 'If enabled the carousel will display items based on their width showing as many as possible.', 'total-theme-core' ),
		),
		array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Items To Display', 'total-theme-core' ),
			'param_name' => 'items',
			'value' => '4',
			'dependency' => array( 'element' => 'auto_width', 'value' => 'false' ),
		),
		array(
			'type' => 'vcex_ofswitch',
			'std' => 'false',
			'heading' => esc_html__( 'Auto Height?', 'total-theme-core' ),
			'param_name' => 'auto_height',
			'dependency' => array( 'element' => 'items', 'value' => '1' ),
			'description' => esc_html__( 'Allows the carousel to change height based on the active item. This setting is used only when you are displaying 1 item per slide.', 'total-theme-core' ),
		),
		array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Items To Scrollby', 'total-theme-core' ),
			'param_name' => 'items_scroll',
			'value' => '1',
		),
		array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Tablet: Items To Display', 'total-theme-core' ),
			'param_name' => 'tablet_items',
			'value' => '3',
			'dependency' => array( 'element' => 'auto_width', 'value' => 'false' ),
		),
		array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Mobile Landscape: Items To Display', 'total-theme-core' ),
			'param_name' => 'mobile_landscape_items',
			'value' => '2',
			'dependency' => array( 'element' => 'auto_width', 'value' => 'false' ),
		),
		array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Mobile Portrait: Items To Display', 'total-theme-core' ),
			'param_name' => 'mobile_portrait_items',
			'value' => '1',
			'dependency' => array( 'element' => 'auto_width', 'value' => 'false' ),
		),
		array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Margin Between Items', 'total-theme-core' ),
			'description' => vcex_shortcode_param_description( 'px' ),
			'param_name' => 'items_margin',
			'value' => '15',
		),
	);

	if ( $dependency ) {
		foreach ( $settings as $key => $value ) {
			if ( empty( $settings[$key]['dependency'] ) ) {
				$settings[$key]['dependency'] = $dependency;
			}
		}
	}

	if ( $group ) {
		foreach ( $settings as $key => $value ) {
			$settings[$key]['group'] = $group;
		}
	}

	return $settings;
}

/**
 * Returns array for adding CSS Animation to VC modules.
 */
function vcex_vc_map_add_css_animation( $args = array() ) {

	// Fallback pre VC 5.0
	if ( ! function_exists( 'vc_map_add_css_animation' ) ) {

		$animations = apply_filters( 'wpex_css_animations', array(
			'' => esc_html__( 'None', 'total') ,
			'top-to-bottom' => esc_html__( 'Top to bottom', 'total' ),
			'bottom-to-top' => esc_html__( 'Bottom to top', 'total' ),
			'left-to-right' => esc_html__( 'Left to right', 'total' ),
			'right-to-left' => esc_html__( 'Right to left', 'total' ),
			'appear' => esc_html__( 'Appear from center', 'total' ),
		) );

		return array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Appear Animation', 'total-theme-core' ),
			'param_name' => 'css_animation',
			'value' => array_flip( $animations ),
			'dependency' => array( 'element' => 'filter', 'value' => 'false' ),
		);

	}

	// New since VC 5.0.
	$defaults = array(
		'type' => 'animation_style',
		'heading' => esc_html__( 'CSS Animation', 'total-theme-core' ),
		'param_name' => 'css_animation',
		'value' => 'none',
		'std' => 'none',
		'settings' => array(
			'type' => 'in',
			'custom' => array(
				array(
					'label' => esc_html__( 'Default', 'total-theme-core' ),
					'values' => array(
						esc_html__( 'Top to bottom', 'total-theme-core' ) => 'top-to-bottom',
						esc_html__( 'Bottom to top', 'total-theme-core' ) => 'bottom-to-top',
						esc_html__( 'Left to right', 'total-theme-core' ) => 'left-to-right',
						esc_html__( 'Right to left', 'total-theme-core' ) => 'right-to-left',
						esc_html__( 'Appear from center', 'total-theme-core' ) => 'appear',
					),
				),
			),
		),
		'description' => esc_html__( 'Select a CSS animation for when the element "enters" the browser\'s viewport. Note: Animations will not work with grid filters as it creates a conflict with re-arranging items.', 'total-theme-core' ) ,
	);

	$args = wp_parse_args( $args, $defaults );

	/**
	 * Filters the vc map CSS animation parameter args.
	 *
	 * @parray array $args
	 */
	$args = apply_filters( 'vc_map_add_css_animation', $args );

	return $args;
}


/**
 * Return block editor script src.
 */
function vcex_get_block_editor_script_src( $block = '' ) {
	return TTC_PLUGIN_DIR_URL . 'inc/vcex/blocks/' . $block . '/vcex-' . $block . '-block.js';
}