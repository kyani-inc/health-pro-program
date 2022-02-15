<?php
defined( 'ABSPATH' ) || exit;

/**
 * Return array of vcex shortcodes to be registered.
 *
 * @package TotalThemeCore
 * @version 1.3.1
 */
function vcex_shortcodes_list() {

	$shortcodes = array(

		// Standard shortcodes.
		'flex_container',
		'grid_container',
		'spacing',
		'divider',
		'heading',
		'button',
		'multi_buttons',
		'toggle_group',
		'toggle',
		'alert',
		'animated_text',
		'wpex_post_cards',
		'blog_grid',
		'blog_carousel',
		'breadcrumbs',
		'bullets',
		'list_item',
		'contact_form',
		'callout',
		'countdown',
		'column_side_border',
		'custom_field',
		'divider_dots',
		'divider_multicolor',
		'form_shortcode',
		'icon_box',
		'feature_box',
		'teaser',
		'icon',
		'image',
		'image_banner',
		'image_before_after',
		'image_carousel',
		'image_flexslider',
		'image_galleryslider',
		'image_grid',
		'image_swap',
		'leader',
		'login_form',
		'milestone',
		'navbar',
		'newsletter_form',
		'portfolio_carousel',
		'portfolio_grid',
		'post_type_grid',
		'post_type_carousel',
		'post_type_slider',
		'post_type_archive',
		'pricing',
		'recent_news',
		'searchbar',
		'shortcode',
		'skillbar',
		'social_links',
		'staff_carousel',
		'staff_grid',
		'staff_social',
		'terms_carousel',
		'terms_grid',
		'testimonials_carousel',
		'testimonials_grid',
		'testimonials_slider',
		'users_grid',

		// Dynamic post modules.
		'page_title',
		'post_comments',
		'post_content',
		'post_media',
		'post_meta',
		'post_next_prev',
		'post_series',
		'post_terms',
		'author_bio',
		'social_share',

		// Dynamic archive modules.
		'term_description',

		// Custom Grid items.
		'grid_item-post_excerpt',
		'grid_item-post_meta',
		'grid_item-post_terms',
		'grid_item-post_video',

	);

	if ( class_exists( 'WooCommerce' ) ) {
		$shortcodes[] = 'cart_link';
		$shortcodes[] = 'woocommerce_carousel';
		$shortcodes[] = 'woocommerce_loop_carousel';
	}

	$shortcodes = (array) apply_filters( 'vcex_builder_modules', $shortcodes ); // @deprecated 1.2.8

	/**
	 * Filters the vcex shortcodes list.
	 *
	 * @param array $list
	 */
	$shortcodes = (array) apply_filters( 'vcex_shortcodes_list', $shortcodes );

	return $shortcodes;

}

// @deprecated 1.2.8
function vcex_builder_modules() {
	return array();
}