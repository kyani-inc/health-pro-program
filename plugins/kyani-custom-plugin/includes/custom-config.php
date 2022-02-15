<?php

/*
 * Disables Javascript Concatenation to resolve conflicts between Elementor and Yoast
 */
if (!function_exists('vip_temp_disable-_concat')) {
	function vip_temp_disable_concat($handle) {
		if (is_admin()) {
			return false;
		}
		return $handle;
	}

	add_filter('js_do_concat', 'vip_temp_disable_concat', -1, 1);
}

/*
 * Disables the Advanced Custom Fields Admin Menu
 */
add_filter('acf/settings/show_admin', '__return_false');

/*
 * Preserves the rep query string if it exists
 */
function add_rep_query_var($link) {
	if (isset($_SERVER['HTTP_X_KYANI_REP'])) {
		$rep = explode(';', $_SERVER['HTTX_KYANI_REP'])[0];
		$uri = str_replace($_SERVER['HTTP_X_FORWARDED_PROTO']. "://" . $_SERVER['HTTP_HOST'],"", $link );
		return 'https://'.$rep.'.'.$_SERVER['HTTP_HOST'] . $uri;
	}
	return $link;
}

add_filter('page_link', 'add_rep_query_var');
add_filter('post_link', 'add_rep_query_var');
add_filter('term_link', 'add_rep_query_var');
add_filter('tag_link', 'add_rep_query_var');
add_filter('category_link', 'add_rep_query_var');
add_filter('post_type_link', 'add_rep_query_var');
add_filter('search_link', 'add_rep_query_var');

add_filter('feed_link', 'add_rep_query_var');
add_filter('post_comments_feed_link', 'add_rep_query_var');
add_filter('author_feed_link', 'add_rep_query_var');
add_filter('category_feed_link', 'add_rep_query_var');
add_filter('taxonomy_feed_link', 'add_rep_query_var');
add_filter('search_feed_link', 'add_rep_query_var');

add_filter('index_rel_link', 'add_rep_query_var');
add_filter('parent_post_rel_link', 'add_rep_query_var');
add_filter('previous_post_rel_link', 'add_rep_query_var');
add_filter('next_post_rel_link', 'add_rep_query_var');
add_filter('start_post_rel_link', 'add_rep_query_var');
add_filter('end_post_rel_link', 'add_rep_query_var');


// custom fields for posts
if (function_exists('acf_add_local_field_group')) :
	acf_add_local_field_group(array(
		'key' => 'reviews',
		'title' => 'Reviews',
		'fields' => array(
			array(
				'key' => 'stars',
				'label' => 'Stars',
				'name' => 'stars',
				'type' => 'image'
			),
			array(
				'key' => 'location',
				'label' => 'Location',
				'name' => 'location',
				'type' => 'text'
			),
			array(
				'key' => 'reviewer',
				'label' => 'Reviewer',
				'name' => 'reviewer',
				'type' => 'text'
			)
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'reviews'
				)
			)
		),
	));

	acf_add_local_field_group(array(
		'key' => 'posts',
		'title' => 'Posts',
		'fields' => array(
			array(
				'key' => 'banner',
				'label' => 'Banner',
				'name' => 'banner',
				'type' => 'image'
			)
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post'
				)
			)
		),
	));
endif;

// create custom post types
function my_custom_post_review() {
	$labels = array(
		'name' => _x('Reviews', 'post type general name'),
		'singular_name' => _x('Reviews', 'post type singular name'),
		'add_new' => _x('Add New', 'review'),
		'add_new_item' => __('Add New Review'),
		'edit_item' => __('Edit Review'),
		'new_item' => __('New Review'),
		'all_items' => __('All Reviews'),
		'view_item' => __('View Product'),
		'search_items' => __('Search Reviews'),
		'not_found' => __('No reviews found'),
		'not_found_in_trash' => __('No reviews found in the Trash'),
		'parent_item_color' => "",
		'menu_name' => 'Reviews'
	);
	$args = array(
		'labels' => $labels,
		'description' => 'Holds our reviews and review specific data',
		'public' => true,
		'menu_position' => 5,
		'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'comments', 'author'),
		'has_archive' => true,
		'rewrite' => array('with_front' => false, 'slug' => 'reviews/%category%'),
		'taxonomies' => array('category', 'author'),
		'publicly_queryable' => true,
		'capability_type' => 'page',
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true,
	);
	register_post_type('reviews', $args);
}

add_action('init', 'my_custom_post_review');

function tm_books_post_link($post_link, $id = 0) {
	$post = get_post($id);
	$terms = wp_get_object_terms($post->ID, 'category');
	if ($terms) {
		return str_replace('%category%', $terms[0]->slug, $post_link);
	} else {
		return str_replace('%category%/', '', $post_link);
	}

	return $post_link;
}

add_filter('post_type_link', 'tm_books_post_link', 1, 3);

// add cors policy
add_action('init', 'add_cors_http_header');
function add_cors_http_header() {
	header('Access-Control-Allow-Origin: *');
}
