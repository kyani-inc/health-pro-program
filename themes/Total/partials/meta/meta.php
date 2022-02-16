<?php
/**
 * Post meta (date, author, comments, etc) for custom post types.
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 5.3.1
 */

defined( 'ABSPATH' ) || exit;

if ( isset( $args['blocks'] ) ) {
	$blocks = $args['blocks'];
	$is_custom = true;
} else {
	$blocks = wpex_meta_blocks();
	$is_custom = false;
}

// Make sure we have blocks and it's an array.
if ( ! empty( $blocks ) && is_array( $blocks ) ) : ?>

	<ul <?php wpex_cpt_meta_class( $is_custom ); ?>>

		<?php
		// Loop through meta sections.
		foreach ( $blocks as $key => $val ) : ?>

			<?php
			// Date.
			if ( 'date' === $val ) : ?>

				<li class="meta-date"><?php wpex_theme_icon_html( 'clock-o' ); ?><time class="updated" datetime="<?php esc_attr( the_date( 'Y-m-d' ) ); ?>"<?php wpex_schema_markup( 'publish_date' ); ?>><?php echo get_the_date(); ?></time></li>

			<?php
			// Author.
			elseif ( 'author' === $val ) : ?>

				<li class="meta-author"><?php wpex_theme_icon_html( 'user-o' ); ?><span class="vcard author"<?php wpex_schema_markup( 'author_name' ); ?>><span class="fn"><?php the_author_posts_link(); ?></span></span></li>

			<?php
			// Categories.
			elseif ( 'categories' === $val ) : ?>

				<?php if ( $taxonomy = apply_filters( 'wpex_meta_categories_taxonomy', wpex_get_post_type_cat_tax() ) ) { ?>

					<?php wpex_list_post_terms( array(
						'taxonomy' => $taxonomy,
						'before'   => '<li class="meta-category">' . wpex_get_theme_icon_html( 'folder-o' ),
						'after'    => '</li>',
						'instance' => 'meta',
					) ); ?>

				<?php } ?>

			<?php
			// Display First Category.
			elseif ( 'first_category' === $val ) : ?>

				<?php if ( $taxonomy = apply_filters( 'wpex_meta_first_category_taxonomy', wpex_get_post_type_cat_tax() ) ) { ?>

					<?php wpex_first_term_link( array(
						'taxonomy' => $taxonomy,
						'before'   => '<li class="meta-category">' . wpex_get_theme_icon_html( 'folder-o' ),
						'after'    => '</li>',
						'instance' => 'meta',
					) ); ?>

				<?php } ?>

			<?php
			// Comments.
			elseif ( 'comments' === $val ) : ?>

				<?php if ( comments_open() && ! post_password_required() ) { ?>

					<li class="meta-comments comment-scroll"><?php wpex_theme_icon_html( 'comment-o' ); ?><?php comments_popup_link( esc_html__( '0 Comments', 'total' ), esc_html__( '1 Comment',  'total' ), esc_html__( '% Comments', 'total' ), 'comments-link' ); ?></li>

				<?php } ?>

			<?php
			// Custom meta block (can not be named "meta" or it will cause infinite loop).
			elseif ( $key !== 'meta' ) :

				if ( is_callable( $val ) ) { ?>

					<li class="meta-<?php echo esc_attr( $key ); ?>"><?php echo call_user_func( $val ); ?></li>

				<?php } else { ?>

					<li class="meta-<?php echo esc_attr( $val ); ?>"><?php get_template_part( 'partials/meta/'. $val ); ?></li>

				<?php } ?>

			<?php endif; ?>

		<?php endforeach; ?>

	</ul>

<?php endif; ?>