<?php
namespace TotalTheme\Integration\WooCommerce;

defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce Product Gallery Integration.
 *
 * @package TotalTheme
 * @subpackage Integration/WooCommerce
 * @version 5.3
 */
final class Product_Gallery {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Product_Gallery.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
		}
		return static::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {

		// Register theme support.
		if ( is_customize_preview() ) {
			add_action( 'wp', __CLASS__ . '::add_theme_support' ); // run later to work correctly with customizer.
		} else {
			add_action( 'after_setup_theme', __CLASS__ . '::add_theme_support' );
		}

		// Register and Enqueue lightbox scripts.
		add_action( 'wp_enqueue_scripts', __CLASS__ . '::register_lightbox_script' );
		add_action( 'wp_footer', __CLASS__ . '::lightbox_footer_scripts' );

		// Make sure lightbox scripts are loaded when using the product_page shortcode.
		add_action( 'woocommerce_after_single_product', __CLASS__ . '::maybe_enqueue_lightbox_scripts' );

		// Custom product gallery flexslider options.
		add_filter( 'woocommerce_single_product_carousel_options', __CLASS__ . '::flexslider_options' );

		// Gallery columns.
		add_filter( 'woocommerce_product_thumbnails_columns', __CLASS__ . '::thumbails_columns' );

		// Custom CSS.
		add_filter( 'wpex_head_css', __CLASS__ . '::custom_css' );

	}

	/**
	 * Add theme support.
	 */
	public static function add_theme_support() {

		if ( get_theme_mod( 'woo_product_gallery_slider', true ) ) {
			add_theme_support( 'wc-product-gallery-slider' );
		} else {
			remove_theme_support( 'wc-product-gallery-slider' );
		}

		if ( get_theme_mod( 'woo_product_gallery_zoom', true ) ) {
			add_theme_support( 'wc-product-gallery-zoom' );
		} else {
			remove_theme_support( 'wc-product-gallery-zoom' );
		}

		if ( 'woo' === self::get_lightbox_type() ) {
			add_theme_support( 'wc-product-gallery-lightbox' );
		} else {
			remove_theme_support( 'wc-product-gallery-lightbox' );
		}

	}

	/**
	 * Check what lightbox type is enabled for products.
	 *
	 * @since 5.0
	 */
	public static function get_lightbox_type() {
		return get_theme_mod( 'woo_product_gallery_lightbox', 'total' );
	}

	/**
	 * Register scripts.
	 *
	 * @since 5.1.3
	 */
	public static function register_lightbox_script() {

		if ( WPEX_MINIFY_JS ) {
			$file = 'js/dynamic/woocommerce/wpex-lightbox-gallery.min.js';
		} else {
			$file = 'js/dynamic/woocommerce/wpex-lightbox-gallery.js';
		}

		wp_register_script(
			'wpex-wc-product-lightbox',
			wpex_asset_url( $file ),
			array( 'jquery', WPEX_THEME_JS_HANDLE ),
			WPEX_THEME_VERSION,
			true
		);

		wp_localize_script(
			'wpex-wc-product-lightbox',
			'wpex_wc_lightbox_params',
			array(
				'showTitle' => get_theme_mod( 'woo_product_gallery_lightbox_titles' ) ? 1 : 0,
			)
		);

	}

	/**
	 * Enqueue footer scripts.
	 *
	 * @since 5.1.3
	 */
	public static function lightbox_footer_scripts() {
		if ( is_product() ) {
			self::maybe_enqueue_lightbox_scripts();
		}
	}

	/**
	 * Maybe enqueue theme lightbox scripts.
	 *
	 * @since 5.1.3
	 */
	public static function maybe_enqueue_lightbox_scripts() {
		if ( 'total' !== self::get_lightbox_type() ) {
			return;
		}
		wpex_enqueue_lightbox_scripts();
		wp_enqueue_script( 'wpex-wc-product-lightbox' );
	}

	/**
	 * Custom product gallery flexslider options.
	 *
	 * Not used at the moment due to WooCommerce bugs.
	 *
	 * @since 4.1
	 */
	public static function flexslider_options( $options ) {
		if ( get_theme_mod( 'woo_product_gallery_slider_arrows' ) ) {
			$options['directionNav'] = true;
		}
		$options['animationSpeed'] = intval( get_theme_mod( 'woo_product_gallery_slider_animation_speed', '600' ) );
		return $options;
	}

	/**
	 * Define columns for gallery.
	 *
	 * @since 5.3
	 */
	public static function thumbails_columns() {
		$columns = absint( get_theme_mod( 'woocommerce_gallery_thumbnails_count' ) );
		if ( ! $columns ) {
			$columns = 5;
		}
		return $columns;
	}

	/**
	 * Inline CSS.
	 *
	 * @since 5.3
	 */
	public static function custom_css( $css ) {
		if ( get_theme_mod( 'woo_product_gallery_slider', true ) ) {
			$slider_thumbnail_gap = get_theme_mod( 'woocommerce_gallery_thumbnails_gap' );
			if ( $slider_thumbnail_gap ) {
				$css .= '.woocommerce-product-gallery .flex-control-thumbs{padding-top:' . absint( $slider_thumbnail_gap ) . 'px}';
			}
		}
		return $css;
	}

}