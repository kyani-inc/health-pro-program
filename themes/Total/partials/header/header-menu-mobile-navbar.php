<?php
/**
 * Navbar Header Menu Mobile Toggle Style.
 *
 * @package TotalTheme
 * @subpackage Partials
 * @version 5.3.1
 */

defined( 'ABSPATH' ) || exit;

$text = wpex_get_translated_theme_mod( 'mobile_menu_toggle_text' );

if ( ! $text ) {
	$text = esc_html__( 'Menu', 'total' );
}

/**
 * Filters the mobile menu navbar text
 *
 * @param string $text
 */
$text = apply_filters( 'wpex_mobile_menu_navbar_open_text', $text );

// Toggle button icon.
$icon = wpex_get_theme_icon_html( 'navicon', 'wpex-mr-10' );

?>

<div id="wpex-mobile-menu-navbar" <?php wpex_mobile_menu_toggle_class(); ?>>
	<div class="container">
		<div class="wpex-flex wpex-items-center wpex-justify-between wpex-text-white wpex-child-inherit-color wpex-text-md">
			<?php wpex_hook_mobile_menu_toggle_top(); ?>
			<div id="wpex-mobile-menu-navbar-toggle-wrap" class="wpex-flex-grow">
				<a href="#mobile-menu" class="mobile-menu-toggle wpex-no-underline" role="button" aria-expanded="false"<?php wpex_aria_label( 'mobile_menu_toggle' ); ?>><?php echo apply_filters( 'wpex_mobile_menu_navbar_open_icon', $icon ); ?><span class="wpex-text"><?php echo wp_kses_post( $text ); ?></span></a>
			</div>
			<?php wpex_mobile_menu_toggle_extra_icons(); ?>
			<?php wpex_hook_mobile_menu_toggle_bottom(); ?>
		</div>
	</div>
</div>