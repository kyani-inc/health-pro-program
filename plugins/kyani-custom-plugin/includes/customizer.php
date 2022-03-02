<?php


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
