<?php
/**
 * Testimonials single post layout.
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 5.1
 *
 * @todo Allow display of the title in the testimonial seperate from archive entry title setting.
 */

defined( 'ABSPATH' ) || exit;

// Custom template design.
if ( $template_content = wpex_get_singular_template_content( 'testimonials' ) ) {
	wpex_singular_template( $template_content );
	return;
}

?>

<div id="single-blocks" class="wpex-clr">

	<div id="testimonials-single-content" <?php wpex_testimonials_single_content_class(); ?>>

		<?php
		// "Quote" style.
		if ( 'blockquote' === wpex_get_testimonials_single_layout() ) :

			wpex_set_loop_instance( 'singular' );

			get_template_part( 'partials/testimonials/testimonials-entry' );

		// Display full content.
		else : ?>

			<?php the_content(); ?>

		<?php endif; ?>

	</div>

	<?php
	// Displays comments if enabled.
	if ( get_theme_mod( 'testimonials_comments', false ) ) :

		get_template_part( 'partials/testimonials/testimonials-single-comments' );

	endif; ?>

</div>