<?php
/**
 * Topbar content.
 *
 * @package TotalTheme
 * @subpackage Partials
 * @version 5.1.3
 */

defined( 'ABSPATH' ) || exit;

// Get topbar content
$content = wpex_topbar_content();

// Display topbar content
if ( $content || has_nav_menu( 'topbar_menu' ) ) : ?>

	<div id="top-bar-content" <?php wpex_topbar_content_class(); ?>><?php

		// Get topbar menu.
		get_template_part( 'partials/topbar/topbar-menu' );

		// Check if there is content for the topbar.
		if ( $content ) {

			// Display top bar content.
			echo do_shortcode( wp_kses_post( $content ) );

		}

	?></div>

<?php endif; ?>