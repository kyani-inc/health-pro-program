<?php
/**
 * Blog entry readmore button.
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 5.3.1
 *
 * @todo remove legacy &rarr; arrow.
 */

defined( 'ABSPATH' ) || exit;

$text = wpex_get_translated_theme_mod( 'blog_entry_readmore_text' ) ?: esc_html__( 'Read more', 'total' );

/**
 * Filters the blog post readmore link text
 *
 * @param string $text
 */
$text = apply_filters( 'wpex_post_readmore_link_text', $text );

if ( ! $text ) {
	return;
}

?>

<div <?php wpex_blog_entry_button_wrap_class(); ?>><a href="<?php wpex_permalink(); ?>" <?php wpex_blog_entry_button_class(); ?>><?php echo do_shortcode( wp_strip_all_tags( $text ) ); ?></a></div>