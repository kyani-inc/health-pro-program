<?php
namespace TotalTheme\Integration\WooCommerce;

defined( 'ABSPATH' ) || exit;

/**
 * Vanilla WooCommerce (very basic WooCommerce support).
 *
 * @package TotalTheme
 * @subpackage Integration/WooCommerce
 * @version 5.2
 */
final class WooCommerce_Vanilla {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of WooCommerce_Vanilla.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
			static::$instance->includes();
			static::$instance->init_hooks();
		}

		return static::$instance;
	}

	/**
	 * Includes.
	 */
	public function includes() {
		Customize\Vanilla_Settings::instance();
	}

	/**
	 * Hook into actions and filters.
	 */
	public function init_hooks() {

		// Add theme support.
		add_action( 'after_setup_theme', array( $this, 'add_theme_support' ) );

		// Remove title from main shop.
		add_filter( 'woocommerce_show_page_title', '__return_false' );

		// Remove category descriptions because they are added by the theme.
		remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );

	}

	/**
	 * Register theme support.
	 */
	public function add_theme_support() {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-slider' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
	}

}