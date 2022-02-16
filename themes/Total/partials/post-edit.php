<?php
/**
 * Edit post link.
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 5.3.1
 */

defined( 'ABSPATH' ) || exit;

edit_post_link(
    null, // use wp default
    '<div class="post-edit wpex-my-40">', ' <a href="#" class="hide-post-edit">' . wpex_get_theme_icon_html( 'times' ) . '<span class="screen-reader-text">' . esc_html__( 'Hide Post Edit Links', 'total' ) . '</span></a></div>'
);