<?php
/**
 * WooCommerce menu cart functions.
 *
 * @package TotalTheme
 * @subpackage Integration/WooCommerce
 * @version 5.3
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add WooCommerce framents.
 *
 * @since 4.0
 */
function wpex_menu_cart_icon_fragments( $fragments ) {
	$fragments['.wcmenucart']      = wpex_wcmenucart_menu_item();
	$fragments['.wpex-cart-count'] = wpex_mobile_menu_cart_count();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'wpex_menu_cart_icon_fragments' );

/**
 * Get correct style for WooCommerce menu cart style.
 *
 * @since 4.0
 */
function wpex_header_menu_cart_style() {

	// Return if disabled completely in Customizer.
	if ( 'disabled' === get_theme_mod( 'woo_menu_icon_display', 'icon_count' ) ) {
		return false;
	}

	// If not disabled get style from settings.
	else {

		// Get Menu Icon Style.
		$style = get_theme_mod( 'woo_menu_icon_style', 'drop_down' );

		// Return click style for these pages.
		if ( is_cart() || is_checkout() ) {
			$style = 'custom-link';
		}

	}

	/**
	 * Filters the menu cart style
	 *
	 * @param string $style.
	 */
	$style = apply_filters( 'wpex_menu_cart_style', $style );

	// Sanitize output so it's not empty and check for deprecated 'drop-down' style.
	if ( 'drop-down' === $style || ! $style ) {
		$style = 'drop_down';
	}

	// Return style.
	return $style;

}

/**
 * Returns header menu cart item.
 *
 * @since 4.4
 * @todo deprecate the $style arg.
 */
function wpex_get_header_menu_cart_item( $style = '' ) {

	if ( ! $style ) {
		return;
	}

	// Get header style.
	$header_style = wpex_header_style();

	// Define classes to add to li element.
	$class = array(
		'woo-menu-icon',
		'menu-item',
		'wpex-menu-extra',
	);

	// Add style class.
	$class[] = 'wcmenucart-toggle-' . sanitize_html_class( $style );

	// Prevent clicking on cart and checkout.
	if ( 'custom-link' !== $style && ( is_cart() || is_checkout() ) ) {
		$class[] = 'nav-no-click';
	}

	// Add toggle class.
	else {
		$class[] = 'toggle-cart-widget';
	}

	// Ubermenu integration.
	if ( class_exists( 'UberMenu' ) && apply_filters( 'wpex_add_search_toggle_ubermenu_classes', true ) ) {
		$class[] = 'ubermenu-item-level-0 ubermenu-item'; // @todo rename or remove filter.
	}

	// Max Mega menu integration.
	if ( function_exists( 'max_mega_menu_is_enabled' ) && max_mega_menu_is_enabled( 'main_menu' ) ) {
		$class[] = 'mega-menu-item';
	}

	// Add cart dropdown inside menu items for specific header styles.
	$cart_dropdown = '';
	if ( 'drop_down' === $style && wpex_maybe_add_header_drop_widget_inline( 'cart' ) ) {
		ob_start();
		get_template_part( 'partials/cart/cart-dropdown' );
		$cart_dropdown .= ob_get_clean();
	}

	// Add cart link to menu items.
	return '<li class="' . esc_attr( implode( ' ', $class ) ) . '">' . wpex_wcmenucart_menu_item() . $cart_dropdown . '</li>';

}


/**
 * Mobile Menu Cart link.
 *
 * @since 5.3
 */
function wpex_get_mobile_menu_cart_link() {

	$mobile_cart_link = '';
	$cart_url = wc_get_cart_url();

	if ( $cart_url && get_theme_mod( 'has_woo_mobile_menu_cart_link', true ) && wpex_has_header_mobile_menu() ) {

		if ( is_callable( 'TotalThemeCore\Shortcodes\Shortcode_Cart_Link::output' ) ) {

			$cart_link_args = array(
				'icon'  => null,
				'link'  => true,
				'items' => array( 'icon', 'count', 'total' ),
				'link'  => 'false', // add our own link around the whole element to prevent block issues.
			);

			/**
			 * Filters the mobile menu cart link attributes
			 *
			 * @param array $cart_link_args
			 */
			$cart_link_args = apply_filters( 'wpex_woo_mobile_menu_cart_link_args', $cart_link_args );

			$mobile_cart_link = TotalThemeCore\Shortcodes\Shortcode_Cart_Link::output( $cart_link_args );

			if ( $mobile_cart_link ) {
				$mobile_cart_link = '<a href="' . esc_url( $cart_url ) . '"><span class="link-inner">' . $mobile_cart_link . '</span></a>';
			}

		} else {

			$mobile_cart_link = '<a href="' . esc_url( $cart_url ) . '"><span class="link-inner">' . esc_html( get_theme_mod( 'woo_menu_cart_text', esc_html__( 'Cart', 'total' ) ) ) . '</span></a>';

		}

		/**
		 * Filters the mobile menu cart link html.
		 *
		 * @param html $mobile_cart_link
		 */
		$mobile_cart_link = apply_filters( 'wpex_woo_mobile_menu_cart_link', $mobile_cart_link );

		if ( $mobile_cart_link && is_string( $mobile_cart_link ) ) {
			return '<li class="menu-item wpex-mm-menu-item">' . $mobile_cart_link . '</li>';
		}

	}

}

/**
 * Add cart link to the header menu for use on mobile.
 *
 * @since 4.0
 */
function wpex_add_header_menu_cart_item( $items, $args ) {

	// Only used for the main menu.
	if ( 'main_menu' !== $args->theme_location || 'seven' === wpex_header_style() ) {
		return $items;
	}

	// Get style.
	$style = wpex_header_menu_cart_style();

	// Return items if no style.
	if ( ! $style ) {
		return $items;
	}

	// Add cart item to menu.
	$items .= wpex_get_header_menu_cart_item( $style );

	// Get mobile menu link.
	$items .= wpex_get_mobile_menu_cart_link();

	// Return menu items.
	return $items;

}
add_filter( 'wp_nav_menu_items', 'wpex_add_header_menu_cart_item', 10, 2 );

/**
 * Creates the WooCommerce link for the navbar.
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_wcmenucart_menu_item' ) ) {
	function wpex_wcmenucart_menu_item() {

		global $woocommerce;
		$icon_style   = wpex_header_menu_cart_style();
		$custom_link  = get_theme_mod( 'woo_menu_icon_custom_link' );
		$count        = WC()->cart->cart_contents_count;

		// Link classes.
		$a_classes = 'wcmenucart';
		$count     = $count ? $count : '0';
		$a_classes .= ' wcmenucart-items-' . intval( $count );

		if ( $count && '0' !== $count ) {
			$a_classes .= ' wpex-has-items';
		}

		// Ubermenu integration.
		if ( class_exists( 'UberMenu' ) && apply_filters( 'wpex_add_search_toggle_ubermenu_classes', true ) ) {
			$a_classes .= ' ubermenu-target ubermenu-item-layout-default ubermenu-item-layout-text_only';
		}

		// Max Mega Menu integration.
		if ( function_exists( 'max_mega_menu_is_enabled' ) && max_mega_menu_is_enabled( 'main_menu' ) ) {
			$a_classes  .= ' mega-menu-link';
		}

		// Define cart icon link URL.
		$url = '';
		if ( 'custom-link' === $icon_style && $custom_link ) {
			$url = esc_url( $custom_link );
		} elseif ( $cart_id = wpex_parse_obj_id( wc_get_page_id( 'cart' ), 'page' ) ) {
			$url = get_permalink( $cart_id );
		}

		// Cart total.
		$display = get_theme_mod( 'woo_menu_icon_display', 'icon_count' );
		$count_txt = absint( $count );
		if ( 'icon_total' === $display ) {
			$cart_extra = WC()->cart->get_cart_total();
			$cart_extra = str_replace( 'amount', 'amount wcmenucart-details', $cart_extra );
		} elseif ( 'icon_count' === $display ) {
			$extra_class = 'wcmenucart-details count';
			if ( $count && '0' !== $count ) {
				$extra_class .= ' wpex-has-items';
			}
			if ( 'six' === wpex_header_style() ) {
				$count_txt = '(' . $count_txt . ')';
			} elseif ( get_theme_mod( 'wpex_woo_menu_icon_bubble', true ) ) {
				$extra_class .= ' t-bubble';
			}
			$cart_extra = '<span class="' . esc_attr( $extra_class ) . '">' . esc_html( $count_txt ) . '</span>';
		} else {
			$cart_extra = '';
		}

		// Cart Icon.
		$icon_class = ( $icon_class = get_theme_mod( 'woo_menu_icon_class' ) ) ? $icon_class : 'shopping-cart';
		$cart_text  = get_theme_mod( 'woo_menu_cart_text', esc_html__( 'Cart', 'total' ) );
		$cart_icon  = '<span class="wcmenucart-icon ticon ticon-' . esc_attr( $icon_class ) . '"></span><span class="wcmenucart-text">' . esc_html( $cart_text ) . '</span>';

		/**
		 * Filters the menu cart link html
		 *
		 * @param html $cart_icon
		 * @param string $icon_class
		 */
		$cart_icon = apply_filters( 'wpex_menu_cart_icon_html', $cart_icon, $icon_class );

		/**
		 * Filters the header menu cart link class.
		 *
		 * @param string $class
		 */
		$a_classes = apply_filters( 'wpex_header_menu_cart_item_link_class', $a_classes );

		// Link attributes.
		$a_attributes = array(
			'href'  => esc_url( $url ),
			'class' => esc_attr( $a_classes ),
		);

		if ( 'drop_down' === $icon_style || 'overlay' === $icon_style ) {

			$a_attributes['role'] = 'button';
			$a_attributes['aria-expanded'] = 'false';

			$a_aria = wpex_get_aria_label( 'shop_cart' );
			if ( $a_aria ) {
				$a_attributes['aria-label'] = esc_attr( $a_aria );
			}

			if ( 'drop_down' === $icon_style ) {
				$a_attributes['aria-controls'] = 'current-shop-items-dropdown';
			} elseif ( 'overlay' === $icon_style ) {
				$a_attributes['aria-controls'] = 'wpex-cart-overlay';
			}

		}

		// Output.
		$output = '<a ' . wpex_parse_attrs( $a_attributes ) . '>';

			$output .= '<span class="link-inner">';

				$output .= '<span class="wcmenucart-count">' . $cart_icon . $cart_extra . '</span>';

			$output .= '</span>';

		$output .= '</a>';

		return $output;

	}
}

/**
 * Add cart overlay html to site.
 *
 * @since 4.0
 */
function wpex_cart_overlay_html() {
	if ( 'overlay' === wpex_header_menu_cart_style() ) {
		get_template_part( 'partials/cart/cart-overlay' );
	}
}
add_action( 'wpex_outer_wrap_after', 'wpex_cart_overlay_html' );

/**
 * Add cart dropdown html.
 *
 * @since 4.0
 */
function wpex_add_cart_dropdown_html() {

	if ( 'drop_down' === wpex_header_menu_cart_style()
		&& 'wpex_hook_header_inner' === current_filter() // @todo remove?
		&& ! wpex_maybe_add_header_drop_widget_inline( 'cart' )
	) {
		get_template_part( 'partials/cart/cart-dropdown' );
	}

}
add_action( 'wpex_hook_header_inner', 'wpex_add_cart_dropdown_html', 40 );

/**
 * Mobile menu cart counter.
 *
 * @since 4.0
 */
function wpex_mobile_menu_cart_count() {

	$count = absint( WC()->cart->cart_contents_count );

	$count = $count ? absint( $count ) : 0;

	$classes = array(
		'wpex-cart-count',
		'wpex-absolute',
		'wpex-text-center',
		'wpex-semibold',
		'wpex-rounded',
		'wpex-text-white',
	);

	if ( $count ) {
		$classes[] = 'wpex-block wpex-bg-accent';
	} else {
		$classes[] = 'wpex-hidden wpex-bg-gray-400';
	}

	return '<span class="' . esc_attr( implode( ' ', $classes ) ) . '">' . esc_html( $count ) . '</span>';
}