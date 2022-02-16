<?php
/**
 * Post Author Hover Overlay.
 *
 * @package TotalTheme
 * @subpackage Partials
 * @version 5.1.1
 */

defined( 'ABSPATH' ) || exit;

// Only used for inside position.
if ( 'inside_link' !== $position ) {
	return;
}

// Get post author.
$author = isset( $args['post_author'] ) ? $args['post_author'] : get_the_author();

?>

<div class="overlay-post-author theme-overlay overlay-hide wpex-absolute wpex-inset-0 wpex-transition-all wpex-duration-<?php echo wpex_overlay_speed( 'post-author' ); ?> wpex-flex wpex-items-end wpex-p-20 wpex-text-white" aria-hidden="true">
	<span class="overlay-bg wpex-bg-center wpex-bg-no-repeat wpex-bg-<?php echo wpex_overlay_bg( 'post-author' ); ?> wpex-block wpex-absolute wpex-inset-0 wpex-opacity-<?php echo wpex_overlay_opacity( 'post-author', '20' ); ?>"></span>
	<div class="wpex-flex wpex-items-center wpex-relative">
		<div class="wpex-mr-10"><?php echo get_avatar( get_the_author_meta( 'ID' ), 32, '', '', array( 'class' => 'wpex-round' ) ); ?></div>
		<div><?php echo esc_html( $author ); ?></div>
	</div>
</div>