<?php
/**
 * Mobile Menu alternative.
 *
 * @package TotalTheme
 * @subpackage Partials
 * @version 5.3.1
 */

defined( 'ABSPATH' ) || exit;

?>

<div id="mobile-menu-alternative" class="wpex-hidden"<?php wpex_aria_landmark( 'mobile_menu_alt' ); ?><?php wpex_aria_label( 'mobile_menu_alt' ); ?>><?php

	wp_nav_menu( array(
		'theme_location' => 'mobile_menu_alt',
		'menu_class'     => 'dropdown-menu',
		'fallback_cb'    => false,
	) );

?></div>