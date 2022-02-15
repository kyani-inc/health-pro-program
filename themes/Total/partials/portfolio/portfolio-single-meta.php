<?php
/**
 * Portfolio single meta.
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 5.3.1
 *
 */

defined( 'ABSPATH' ) || exit;

$sections = wpex_portfolio_single_meta_sections();

if ( ! empty( $sections ) ) : ?>

	<ul id="portfolio-single-meta" <?php wpex_portfolio_single_meta_class(); ?>>

		<?php
		// Loop through meta sections.
		foreach ( $sections as $section ) : ?>

			<?php
			// Date.
			if ( 'date' === $section ) : ?>

				<li class="meta-date"><?php wpex_theme_icon_html( 'clock-o' ); ?><time class="updated" datetime="<?php the_date('Y-m-d');?>"<?php wpex_schema_markup( 'publish_date' ); ?>><?php echo get_the_date(); ?></time></li>

			<?php
			// Author.
			elseif ( 'author' === $section ) : ?>

				<li class="meta-author"><?php wpex_theme_icon_html( 'user-o' ); ?><span class="vcard author"<?php wpex_schema_markup( 'author_name' ); ?>><?php the_author_posts_link(); ?></span></li>

			<?php
			// Categories.
			elseif ( 'categories' === $section ) : ?>

				<?php wpex_list_post_terms( array(
					'taxonomy' => 'portfolio_category',
					'before'   => '<li class="meta-category">' . wpex_get_theme_icon_html( 'folder-o' ),
					'after'    => '</li>',
					'instance' => 'portfolio_single_meta_sections',
				) ); ?>

			<?php
			// Comments.
			elseif ( 'comments' === $section ) : ?>

				<?php if ( comments_open() && ! post_password_required() ) { ?>

					<li class="meta-comments comment-scroll"><?php wpex_theme_icon_html( 'comment-o' ); ?><?php comments_popup_link( esc_html__( '0 Comments', 'total' ), esc_html__( '1 Comment',  'total' ), esc_html__( '% Comments', 'total' ), 'comments-link' ); ?></li>

				<?php } ?>

			<?php
			// Display Custom Meta Block.
			elseif ( $key !== 'meta' ) :

				if ( is_callable( $val ) ) { ?>

					<li class="meta-<?php echo sanitize_html_class( $key ); ?>"><?php echo call_user_func( $val ); ?></li>

				<?php } else { ?>

					<li class="meta-<?php echo sanitize_html_class( $val ); ?>"><?php get_template_part( 'partials/meta/'. $val ); ?></li>

				<?php } ?>

			<?php endif; ?>

		<?php endforeach; ?>

	</ul>

<?php endif; ?>