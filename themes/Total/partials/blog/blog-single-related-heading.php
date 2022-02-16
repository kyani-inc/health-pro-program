<?php
/**
 * Blog single related heading.
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 5.3.1
 */

defined( 'ABSPATH' ) || exit;

wpex_heading( array(
	'tag'           => get_theme_mod( 'related_heading_tag' ) ?: 'h3',
	'content'		=> wpex_blog_related_heading(),
	'classes'		=> array( 'related-posts-title' ),
	'apply_filters'	=> 'blog_related',
) );