<?php
/**
 * Blog entry avatar.
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 5.3.1
 */

defined( 'ABSPATH' ) || exit;

$avatar_size = 75;

if ( 'grid-entry-style' === wpex_blog_entry_style() ) {
	$avatar_size = 60;
}

?>

<div <?php wpex_blog_entry_avatar_class(); ?>>
	<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php esc_attr_e( 'Visit Author Page', 'total' ); ?>">
		<?php echo get_avatar(
			get_the_author_meta( 'user_email' ),
			apply_filters( 'wpex_blog_entry_author_avatar_size', $avatar_size ),
			'',
			'',
			array(
				'class' => 'wpex-round wpex-align-middle',
			)
		); ?>
	</a>
</div>