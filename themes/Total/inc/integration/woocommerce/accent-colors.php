<?php
namespace TotalTheme\Integration\WooCommerce;

defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce accent colors.
 *
 * @package TotalTheme
 * @subpackage Integration/WooCommerce
 * @version 5.3
 */
final class Accent_Colors {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Accent_Colors.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
			static::$instance->init_hooks();
		}

		return static::$instance;
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 5.0
	 */
	public function init_hooks() {
		add_filter( 'wpex_accent_texts', array( $this, 'accent_texts' ) );
		add_filter( 'wpex_accent_borders', array( $this, 'accent_borders' ) );
		add_filter( 'wpex_accent_backgrounds', array( $this, 'accent_backgrounds' ) );
		add_filter( 'wpex_accent_hover_backgrounds', array( $this, 'accent_hover_backgrounds' ) );
		add_filter( 'wpex_border_color_elements', array( $this, 'border_color_elements' ) );
	}

	/**
	 * Adds border accents for WooCommerce styles.
	 *
	 * @since 4.1
	 */
	public function accent_texts( $elements ) {
		$woo_elements = array(
			'.woocommerce .order-total td',
			'.price > .amount',
			'.price ins .amount',
		);
		return array_merge( $woo_elements, $elements );
	}

	/**
	 * Adds border accents for WooCommerce styles.
	 *
	 * @since 4.1
	 */
	public function accent_borders( $elements ) {
		$woo_borders = array(
			'.woocommerce div.product .woocommerce-tabs ul.tabs li.active a' => array( 'bottom' ),
		);
		return array_merge( $woo_borders, $elements );
	}

	/**
	 * Filter accent backgrounds.
	 *
	 * @since 4.1
	 */
	public function accent_backgrounds( $elements ) {
		$woo_elements = array(
			'.woocommerce-MyAccount-navigation li.is-active a',
			'.woocommerce .widget_price_filter .ui-slider .ui-slider-range',
			'.woocommerce .widget_price_filter .ui-slider .ui-slider-handle',
			'.wcmenucart-details.count.t-bubble',
			'.added_to_cart',
			'.added_to_cart:hover',
			'.select2-container--default .select2-results__option--highlighted[aria-selected],.select2-container--default .select2-results__option--highlighted[data-selected]',
		);
		return array_merge( $woo_elements, $elements );
	}

	/**
	 * Filter accent hover backgrounds.
	 *
	 * @since 4.1
	 */
	public function accent_hover_backgrounds( $elements ) {
		$woo_elements = array(
			'.added_to_cart:hover',
		);
		return array_merge( $woo_elements, $elements );
	}

	/**
	 * Adds border color elements for WooCommerce styles.
	 *
	 * @since 4.1
	 */
	public function border_color_elements( $elements ) {
		$woo_elements = array(

			// Product
			'.product_meta',
			'.woocommerce div.product .woocommerce-tabs ul.tabs',

			// Account
			'#customer_login form.login, #customer_login form.register, p.myaccount_user',

			// Widgets
			'.woocommerce ul.product_list_widget li:first-child',
			'.woocommerce .widget_shopping_cart .cart_list li:first-child',
			'.woocommerce.widget_shopping_cart .cart_list li:first-child',
			'.woocommerce ul.product_list_widget li',
			'.woocommerce .widget_shopping_cart .cart_list li',
			'.woocommerce.widget_shopping_cart .cart_list li',

			// Cart dropdown
			'#current-shop-items-dropdown p.total',

			// Checkout
			'.woocommerce form.login',
			'.woocommerce form.register',
			'.woocommerce-checkout #payment',
			'#add_payment_method #payment ul.payment_methods',
			'.woocommerce-cart #payment ul.payment_methods',
			'.woocommerce-checkout #payment ul.payment_methods',

		);
		return array_merge( $woo_elements, $elements );
	}

}