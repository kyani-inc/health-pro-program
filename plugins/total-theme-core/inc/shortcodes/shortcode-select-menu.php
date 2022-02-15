<?php
namespace TotalThemeCore\Shortcodes;

defined( 'ABSPATH' ) || exit;

final class Shortcode_Select_menu {

	public function __construct() {

		if ( ! shortcode_exists( 'select_menu' ) ) {
			add_shortcode( 'select_menu', __CLASS__ . '::output' );
		}

	}

	public static function output( $atts, $content = null ) {

		$atts = shortcode_atts( array(
			'menu'          => null,
			'custom_select' => true
		), $atts );

		if ( empty( $atts['menu'] ) ) {
			return;
		}

		$menu = wp_get_nav_menu_object( $atts['menu'] );

		if ( ! $menu ) {
			return;
		}

		$atts['custom_select'] = wp_validate_boolean( $atts['custom_select'] ); // sanitize custom_select field

		ob_start();

		$menu_items = wp_get_nav_menu_items( $menu->term_id );

		$escaped_menu_id = esc_attr( 'select-menu-' . sanitize_html_class( $menu->term_id ) ); ?>

		<?php if ( ! empty( $atts['custom_select'] ) ) {

			echo '<div class="wpex-select-wrap">';

		} ?>

		<select id="<?php echo $escaped_menu_id; ?>" class="wpex-select-menu-shortcode" onchange="if (this.value) window.location.href=this.value"><?php

			// Make sure we have menu items
			if ( $menu_items && is_array( $menu_items ) ) {

				foreach ( $menu_items as $menu_item ) : ?>

					<option value="<?php echo esc_url( $menu_item->url ); ?>"><?php echo esc_attr( $menu_item->title ); ?></option>

				<?php endforeach;
			}

		?></select>

		<?php if ( ! empty( $atts['custom_select'] ) ) {

			if ( function_exists( 'wpex_theme_icon_html' ) ) {
				wpex_theme_icon_html( 'angle-down' );
			}

			echo '</div>';

		} ?>

		<?php return ob_get_clean();

	}

}