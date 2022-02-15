<?php
namespace TotalThemeCore\Shortcodes;

defined( 'ABSPATH' ) || exit;

final class Shortcode_Cart_Link {

	/**
	 * Register the shortcode and add filters.
	 *
	 * @since 1.3
	 */
	public function __construct() {

		if ( ! shortcode_exists( 'cart_link' ) ) {
			add_shortcode( 'cart_link', __CLASS__ . '::output' );
		} else {
			add_shortcode( 'wpex_cart_link', __CLASS__ . '::output' );
		}

		add_filter( 'woocommerce_add_to_cart_fragments', __CLASS__ . '::cart_fragments' );

	}

	/**
	 * Shortcode output.
	 *
	 * @since 1.3
	 */
	public static function output( $atts = array() ) {

		$atts = shortcode_atts( array(
			'items'       => array( 'icon', 'count', 'total' ),
			'link'        => true,
			'icon'        => '',
			'font_size'   => '',
			'font_family' => '',
			'font_color'  => '',
			'el_class'    => '',
		), $atts, 'cart_link' );

		if ( is_string( $atts['items'] ) ) {
			$atts['items'] = explode( ',', $atts['items'] );
		}

		if ( empty( $atts['items'] ) || ! is_array( $atts['items'] ) ) {
			return;
		}

		if ( ! empty( $atts['el_class'] ) ) {
			$el_class = ' ' . str_replace( '.', '', trim( $atts['el_class'] ) );
		} else {
			$el_class = '';
		}

		$html = '<span class="wpex-cart-link wpex-inline-block' . esc_attr( $el_class ) . '">';

			if ( self::has_link( $atts ) ) {
				$html .= '<a href="' . esc_url( wc_get_cart_url() ) . '">';
			}

			$items_class = 'wpex-cart-link__items wpex-flex wpex-items-center';

			$items_style = self::get_inline_style( $atts );

			$html .= '<span class="' . esc_attr( $items_class ) . '"' . $items_style . '>';

				if ( in_array( 'icon', $atts['items'] ) ) {

					$html .= '<span class="wpex-cart-link__icon wpex-flex wpex-items-center">';

						if ( ! empty( $atts['icon'] ) ) {
							// @todo Include support for custom icons (preferably via an SVG - will need new picker for WPBakery for this).
						} elseif ( function_exists( 'wpex_theme_icon_html' ) ) {
							$cart_icon = get_theme_mod( 'woo_menu_icon_class' );
							$cart_icon = $cart_icon ?: 'shopping-cart';
							$icon = wpex_get_theme_icon_html( $cart_icon );
						}

						/**
						 * Filters the cart_link icon.
						 *
						 * @param string $icon
						 */
						$icon = apply_filters( 'wpex_cart_link_shortcode_icon', $icon, $atts );

						$html .= $icon; // @codingStandardsIgnoreLine

					$html .= '</span>';

				}

				if ( in_array( 'count', $atts['items'] ) ) {
					$html .= self::get_cart_count();
				}

				if ( in_array( 'count', $atts['items'] ) && in_array( 'total', $atts['items'] ) ) {
					$html .= self::get_dash();
				}

				if ( in_array( 'total', $atts['items'] ) ) {
					$html .= self::get_cart_price();
				}

			$html .= '</span>';

			if ( self::has_link( $atts ) ) {
				$html .= '</a>';
			}

		$html .= '</span>';

		return $html;

	}

	/**
	 * Check if we should link to the cart.
	 *
	 * @since 1.3
	 */
	public static function has_link( $atts ) {
		if ( ! array_key_exists( 'link',  $atts ) ) {
			return true;
		}
		return wp_validate_boolean( $atts['link'] );
	}

	/**
	 * Get inline style.
	 *
	 * @since 1.3
	 */
	public static function get_inline_style( $atts ) {

		if ( ! empty( $atts['font_family'] ) ) {
			vcex_enqueue_font( $atts['font_family'] );
		}

		if ( function_exists( 'vcex_inline_style' ) ) {
			return vcex_inline_style( array(
				'font_family' => $atts['font_family'],
				'font_size'   => $atts['font_size'],
				'color'       => $atts['font_color'],
			) );
		}

	}

	/**
	 * Hook into the WooCommerce woocommerce_add_to_cart_fragments filter
	 * so that the cart count is refreshed whenever items are added or removed from the cart.
	 *
	 * @since 1.3
	 */
	public static function cart_fragments( $fragments ) {
		$fragments['.wpex-cart-link__count'] = self::get_cart_count();
		$fragments['.wpex-cart-link__dash']  = self::get_dash();
		$fragments['.wpex-cart-link__price'] = self::get_cart_price();
		return $fragments;
	}

	/**
	 * Return items dash.
	 *
	 * @since 1.3
	 */
	public static function get_dash() {
		if ( 0 === WC()->cart->cart_contents_count ) {
			return '<span class="wpex-cart-link__dash wpex-hidden">&#45;</span>';
		}
		return '<span class="wpex-cart-link__dash">&#45;</span>';
	}

	/**
	 * Return current cart items count.
	 *
	 * @since 1.3
	 */
	public static function get_cart_count() {
		$count = WC()->cart->cart_contents_count;

		if ( 1 === $count ) {
			$text = apply_filters( 'wpex_cart_link_shortcode_item_text', esc_html__( 'Item', 'total-theme-core' ) );
		} else {
			$text = apply_filters( 'wpex_cart_link_shortcode_items_text', esc_html__( 'Items', 'total-theme-core' ) );
		}

		$html = '<span class="wpex-cart-link__count">';
			$html .= esc_html( $count );
			if ( $text ) {
				$html .= ' ' . esc_html( $text );
			}
		$html .= '</span>';
		return $html;
	}

	/**
	 * Return current cart price.
	 *
	 * @since 1.3
	 */
	public static function get_cart_price() {
		if ( 0 === WC()->cart->cart_contents_count ) {
			return '<span class="wpex-cart-link__price wpex-hidden"></span>';
		}
		$price = WC()->cart->get_cart_total();
		return '<span class="wpex-cart-link__price">' . wp_kses_post( $price ) .'</span>';
	}

}