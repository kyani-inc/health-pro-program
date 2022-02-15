<?php
/**
 * vcex_post_content shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_post_content', $atts ) ) {
	return;
}

// Get post ID.
$post_id = vcex_get_the_ID();

// Prevent the module to display in itself when creating templates which would cause an endless loop.
if ( vcex_vc_is_inline() && 'templatera' === get_post_type() && $post_id === get_queried_object_id() ) {
	echo '<div class="wpex-alert wpex-text-center">' . esc_html__( 'Post Content Placeholder', 'total-theme-core' ) . '</div>';
	return;
}

// Get post content.
$post_content = get_the_content( '', '', $post_id );

// Return if the current post has this shortcode inside it to prevent infinite loop.
if ( false !== strpos( $post_content, 'vcex_post_content' ) ) {
	return;
}

// Fallback for when blocks were added in 1.2 (must check before mapping).
if ( empty( $atts['blocks'] ) ) {

	$blocks = array();

	$old_settings = array(
		'post_series',
		'the_content',
		'social_share',
		'author_bio',
		'related',
		'comments',
	);

	foreach( $old_settings as $setting ) {

		if ( 'the_content' == $setting ) {
			$blocks[] = $setting;
		} elseif ( isset( $atts[$setting] ) && 'true' == $atts[$setting] ) {
			$blocks[] = $setting;
		}

	}

}

// Get shortcode attributes based on vc_lean_map => This makes sure no attributes are empty.
$atts = vcex_shortcode_atts( 'vcex_post_content', $atts, $this );

// Sanitize then turn blocks into array.
$blocks = ! empty( $blocks ) ? $blocks : $atts['blocks'];
if ( ! is_array( $blocks ) ) {
	$blocks = (array) explode( ',' , $atts['blocks'] );
}

// Define wrap attributes.
$wrap_attrs = array(
	'class' => array(
		'vcex-post-content',
		'vcex-clr',
	)
);

// Add max-width when custom width is defined.
if ( ! empty( $atts['width'] ) ) {
	$wrap_attrs['class'][] = 'wpex-mx-auto';
	$wrap_attrs['class'][] = 'wpex-max-w-100';
}

// Add css animation class.
if ( $css_animation_class = vcex_get_css_animation( $atts['css_animation'] ) ) {
	$wrap_attrs['class'][] = $css_animation_class;
}

// Add extra classname.
if ( $el_class_class = vcex_get_extra_class( $atts['el_class'] ) ) {
	$wrap_attrs['class'][] = $el_class_class;
}

// Add CSS class.
if ( ! empty( $atts['css'] ) ) {
	$wrap_attrs['class'][] = vcex_vc_shortcode_custom_css_class( $atts['css'] );
}

// Get responsive data.
$unique_classname = vcex_element_unique_classname();

$el_responsive_styles = array(
	'font_size' => $atts['font_size'],
);

$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

if ( $responsive_css ) {
	$wrap_attrs['class'][] = $unique_classname;
	echo '<style>' . $responsive_css . '</style>';
}

// Inline style.
$wrap_attrs['style'][] = vcex_inline_style( array(
	'width' => $atts['width'],
	'animation_delay' => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
), false );

// Sidebar check.
$has_sidebar = false;
if ( defined( 'TOTAL_THEME_ACTIVE' ) ) {
	$has_sidebar = 'true' == $atts['sidebar'] && apply_filters( 'vcex_post_content_has_sidebar', true ) ? true : false;

	if ( $has_sidebar && $atts['sidebar_position'] ) {
		$wrap_attrs['class'][] = 'vcex-post-content-' . sanitize_html_class( $atts['sidebar_position'] ) . '-sidebar';
	}

}

?>

<div <?php echo vcex_parse_html_attributes( $wrap_attrs ); ?>>

	<?php
	// Open sidebar wrapper if enabled.
	if ( $has_sidebar ) { ?>

		<div class="vcex-post-content-blocks wpex-content-w wpex-clr">

	<?php }

	// Display blocks.
	if ( function_exists( 'wpex_get_template_part' ) ) :

		foreach ( $blocks as $block ) :

			switch( $block ) :

				case 'featured_media' : ?>

					<div id="post-media" class="wpex-mb-20 wpex-clr">

						<?php if ( function_exists( 'wpex_post_media' ) ) {
							wpex_post_media( $post_id );
						} else {
							the_post_thumbnail();
						} ?>

					</div>

				<?php break;

				case 'title' : ?>

					<h1 class="single-post-title entry-title wpex-text-3xl"<?php wpex_schema_markup( 'heading' ); ?>><?php the_title(); ?></h1>

					<?php break;

				case 'meta' :

					wpex_get_template_part( 'post_meta' );

					break;

				case 'the_content' :

					$post_content_inline_style = array(
						'font_size'      => $atts['font_size'],
						'font_family'    => $atts['font_family'],
						'color'          => $atts['color'],
						'line_height'    => $atts['line_height'],
						'letter_spacing' => $atts['letter_spacing']
					);

				?>

					<div class="vcex-post-content-c wpex-clr"<?php echo vcex_inline_style( $post_content_inline_style ); ?>><?php echo apply_filters( 'the_content', $post_content ); ?></div>

					<?php break;

				case 'post_series' :

					wpex_get_template_part( 'post_series' );

					break;

				case 'social_share' :

					wpex_get_template_part( 'social_share' );

					break;

				case 'author_bio' :

					wpex_get_template_part( 'author_bio' );

					break;

				case 'related' :

					$post_type = get_post_type( $post_id );

					switch ( $post_type ) {
						case 'post':
							get_template_part( 'partials/blog/blog-single-related' );
							break;
						case 'portfolio':
							get_template_part( 'partials/portfolio/portfolio-single-related' );
							break;
						case 'staff':
							get_template_part( 'partials/staff/staff-single-related' );
							break;
						default:
							get_template_part( 'partials/cpt/cpt-single-related' );
							break;
					}

					break;

				case 'comments' :

					comments_template();

					break;

				default:

					if ( is_callable( $block ) ) {
						call_user_func( $block );
					}

				break;

			endswitch;

		endforeach;

	endif; ?>

	<?php
	// Close sidebar wrapper if enabled.
	if ( $has_sidebar ) { ?>
		</div>
	<?php } ?>

	<?php
	// Display sidebar if enabled.
	if ( $has_sidebar ) { ?>

		<aside id="sidebar" class="vcex-post-content-sidebar sidebar-container sidebar-primary"<?php wpex_schema_markup( 'sidebar' ); ?><?php wpex_aria_landmark( 'sidebar' ); ?>>

			<?php wpex_hook_sidebar_top(); ?>

				<div id="sidebar-inner" class="clr"><?php

					dynamic_sidebar( wpex_get_sidebar() );

				?></div>

			<?php wpex_hook_sidebar_bottom(); ?>

		</aside>

	<?php } ?>

</div>