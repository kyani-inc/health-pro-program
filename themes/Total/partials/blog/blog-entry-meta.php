<?php
/**
 * Blog entry meta.
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 5.3.1
 */

defined( 'ABSPATH' ) || exit;

$sections = wpex_blog_entry_meta_sections();

if ( empty( $sections ) ) {
	return;
}

?>

<ul <?php wpex_blog_entry_meta_class(); ?>>

	<?php
	// Loop through meta sections.
	foreach ( $sections as $key => $val ) : ?>

		<?php
		// Display Date.
		if ( 'date' === $val ) : ?>

			<li class="meta-date"><?php wpex_theme_icon_html( 'clock-o' ); ?><span class="updated"><?php echo get_the_date(); ?></span></li>

		<?php
		// Display Author.
		elseif ( 'author' === $val ) : ?>

			<li class="meta-author"><?php wpex_theme_icon_html( 'user-o' ); ?><span class="vcard author"><span class="fn"><?php the_author_posts_link(); ?></span></span></li>

		<?php
		// Display Categories.
		elseif ( 'categories' === $val ) : ?>

			<?php
			// Standard posts.
			if ( 'post' === get_post_type() ) { ?>

				<?php wpex_list_post_terms( array(
					'taxonomy' => 'category',
					'before'   => '<li class="meta-category">' . wpex_get_theme_icon_html( 'folder-o' ),
					'after'    => '</li>',
					'instance' => 'blog_entry_meta',
				) ); ?>

			<?php }
			// Non standard posts (for search results with blog entry style).
			elseif ( $taxonomy = apply_filters( 'wpex_meta_categories_taxonomy', wpex_get_post_type_cat_tax() ) ) { ?>

				<?php wpex_list_post_terms( array(
					'taxonomy' => $taxonomy,
					'before'   => '<li class="meta-category">' . wpex_get_theme_icon_html( 'folder-o' ),
					'after'    => '</li>',
					'instance' => 'blog_entry_meta',
				) ); ?>

			<?php } ?>

		<?php
		// Display First Category.
		elseif ( 'first_category' === $val ) : ?>

			<?php
			// Standard posts.
			if ( 'post' === get_post_type() ) { ?>

				<?php wpex_first_term_link( array(
					'taxonomy' => 'category',
					'before'   => '<li class="meta-category">' . wpex_get_theme_icon_html( 'folder-o' ),
					'after'    => '</li>',
					'instance' => 'blog_entry_meta',
				) ); ?>

			<?php }
			// Non standard posts (for search results with blog entry style).
			elseif ( $taxonomy = apply_filters( 'wpex_meta_first_category_taxonomy', wpex_get_post_type_cat_tax() ) ) { ?>

				<?php wpex_first_term_link( array(
					'taxonomy' => $taxonomy,
					'before'   => '<li class="meta-category">' . wpex_get_theme_icon_html( 'folder-o' ),
					'after'    => '</li>',
					'instance' => 'blog_entry_meta',
				) ); ?>

			<?php } ?>

		<?php
		// Display Comments Count.
		elseif ( 'comments' === $val ) : ?>

			<?php if ( comments_open() && ! post_password_required() ) { ?>

				<li class="meta-comments comment-scroll"><?php wpex_theme_icon_html( 'comment-o' ); ?><?php comments_popup_link( esc_html__( '0 Comments', 'total' ), esc_html__( '1 Comment',  'total' ), esc_html__( '% Comments', 'total' ), 'comments-link' ); ?></li>

			<?php } ?>

		<?php
		// Display Custom Meta Block.
		elseif ( $key !== 'meta' ) :

			// Note: Callable check needs to be here because of 'date'.
			if ( is_callable( $val ) ) { ?>

				<li class="meta-<?php echo sanitize_html_class( $key ); ?>"><?php echo call_user_func( $val ); ?></li>

			<?php } else { ?>

				<li class="meta-<?php echo sanitize_html_class( $val ); ?>"><?php get_template_part( 'partials/meta/' . $val ); ?></li>

			<?php } ?>

		<?php endif; ?>

	<?php endforeach; ?>

</ul>