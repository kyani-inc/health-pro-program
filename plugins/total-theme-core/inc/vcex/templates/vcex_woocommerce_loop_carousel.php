<?php
/**
 * Visual Composer WooCommerce Loop Carousel.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 *
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_woocommerce_loop_carousel', $atts ) ) {
	return;
}

// WooCommerce Only.
if ( ! class_exists( 'woocommerce' ) ) {
	return;
}

// Get and extract shortcode attributes.
$atts = vcex_shortcode_atts( 'vcex_woocommerce_loop_carousel', $atts, $this );

// Define vars.
$atts['post_type'] = 'product';
$atts['taxonomy']  = 'product_cat';
$atts['tax_query'] = '';

// Custom query_products_by argument.
if ( $atts['query_products_by'] ) {
	if ( 'featured' == $atts['query_products_by'] ) {
		$atts['featured_products_only'] = true;
	} elseif ( 'on_sale' == $atts['query_products_by'] ) {
		if ( function_exists( 'wc_get_product_ids_on_sale' ) ) {
			$atts['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
		}
	}
}

// Extract attributes.
extract( $atts );

if ( 'woo_top_rated' === $atts['orderby'] ) {
	add_filter( 'posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses' ) );
}

// Build the WordPress query.
$vcex_query = vcex_build_wp_query( $atts );

if ( 'woo_top_rated' === $atts['orderby'] ) {
	remove_filter( 'posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses' ) );
}

// Output posts.
if ( $vcex_query->have_posts() ) :

	// Enqueue scripts.
	vcex_enqueue_carousel_scripts();

	// Wrap Classes.
	$wrap_classes = array(
		'vcex-module',
		'wpex-carousel',
		'wpex-carousel-woocommerce-loop',
		'owl-carousel',
		'products',
		'wpex-clr'
	);

	if ( 'true' == $arrows ) {
		$wrap_classes[] = $arrows_style ? 'arrwstyle-' . $arrows_style : 'arrwstyle-default';
		if ( $arrows_position && 'default' != $arrows_position ) {
			$wrap_classes[] = 'arrwpos-' . $arrows_position;
		}
	}

	if ( $visibility ) {
		$wrap_classes[] = vcex_parse_visibility_class( $visibility );
	}

	if ( $css_animation && 'none' != $css_animation ) {
		$wrap_classes[] = vcex_get_css_animation( $css_animation );
	}

	if ( $classes ) {
		$wrap_classes[] = vcex_get_extra_class( $classes );
	}

	// Disable autoplay.
	if ( vcex_vc_is_inline() || '1' == count( $vcex_query->posts ) ) {
		$atts['auto_play'] = false;
	}

	// VC filter.
	$wrap_classes = vcex_parse_shortcode_classes( implode( ' ', $wrap_classes ), 'vcex_woocommerce_carousel', $atts );

	?>

	<div class="woocommerce wpex-clr">

		<ul class="<?php echo esc_attr( $wrap_classes ); ?>" data-wpex-carousel="<?php echo vcex_get_carousel_settings( $atts, 'vcex_woocommerce_loop_carousel' ); ?>"<?php vcex_unique_id( $unique_id ); ?>>

			<?php
			// Loop through posts.
			while ( $vcex_query->have_posts() ) :

				// Get post from query.
				$vcex_query->the_post();

				if ( function_exists( 'wc_set_loop_prop' ) ) {
					wc_set_loop_prop( 'name', 'wpex_loop' );
				}

				// Get woocommerce template part.
				if ( function_exists( 'wc_get_template_part' ) ) {
					wc_get_template_part( 'content', 'product' );
				}

			endwhile;

			?>

		</ul>

	</div>

	<?php
	// Reset loop.
	if ( function_exists( 'wc_reset_loop' ) ) {
		wc_reset_loop();
	}
	wp_reset_postdata(); ?>

<?php
// If no posts are found display message.
else : ?>

	<?php
	// Display no posts found error if function exists.
	echo vcex_no_posts_found_message( $atts ); ?>

<?php
// End post check
endif; ?>