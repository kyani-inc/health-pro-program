<?php
namespace TotalTheme;

defined( 'ABSPATH' ) || exit;

/**
 * Lightbox.
 *
 * @package TotalTheme
 * @version 5.3.1
 */
final class Lightbox {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of our class.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
		}
		return static::$instance;
	}

	/**
	 * Construct.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', __CLASS__ . '::register_scripts' );
		add_action( 'wp_enqueue_scripts', __CLASS__ . '::enqueue_global_scripts' );
	}

	/**
	 * Register scripts.
	 */
	public static function register_scripts() {
		self::register_fancybox();

		if ( get_theme_mod( 'lightbox_auto', false ) ) {
			self::register_auto_lightbox();
		}

	}

	/**
	 * Register auto lightbox.
	 */
	public static function register_auto_lightbox() {

		$js_extension = WPEX_MINIFY_JS ? '.min.js' : '.js';

		wp_register_script(
			'wpex-auto-lightbox',
			wpex_asset_url( 'js/dynamic/wpex-auto-lightbox' . $js_extension ),
			array( 'jquery', 'fancybox' ),
			WPEX_THEME_VERSION,
			true
		);

		$auto_lightbox_targets = '.wpb_text_column a > img, body.no-composer .entry a > img';

		/**
		 * Filters the auto lightbox target elements.
		 *
		 * @param string $targets
		 */
		$auto_lightbox_targets = apply_filters( 'wpex_auto_lightbox_targets', $auto_lightbox_targets );

		wp_localize_script(
			'wpex-auto-lightbox',
			'wpex_autolightbox_params',
			array(
				'targets' => $auto_lightbox_targets,
			)
		);

	}

	/**
	 * Register fancybox script.
	 */
	public static function register_fancybox() {

		wp_register_style(
			'fancybox',
			wpex_asset_url( 'lib/fancybox/jquery.fancybox.min.css' ),
			array(),
			'3.5.7'
		);

		$js_extension = WPEX_MINIFY_JS ? '.min.js' : '.js';

		wp_register_script(
			'fancybox',
			wpex_asset_url( 'lib/fancybox/jquery.fancybox' . $js_extension ),
			array( 'jquery' ),
			'3.5.7',
			true
		);

		wp_register_script(
			'wpex-fancybox',
			wpex_asset_url( 'js/dynamic/wpex-fancybox' . $js_extension ),
			array( 'jquery', 'fancybox' ),
			WPEX_THEME_VERSION,
			true
		);

		wp_localize_script(
			'wpex-fancybox',
			'wpex_fancybox_params',
			wpex_get_lightbox_settings()
		);

	}

	/**
	 * Enqueue Global Scripts.
	 */
	public static function enqueue_global_scripts() {

		if ( self::maybe_enqueue_scripts_globally() ) {
			wpex_enqueue_lightbox_scripts();
		}

		if ( get_theme_mod( 'lightbox_auto', false ) ) {
			wp_enqueue_script( 'wpex-auto-lightbox' );
		}

	}

	/**
	 * Check if scripts should load globally.
	 */
	public static function maybe_enqueue_scripts_globally() {

		if ( get_theme_mod( 'lightbox_auto', false ) ) {
			return true;
		}

		$check = get_theme_mod( 'lightbox_load_style_globally', false );

		/**
		 * Filters whether lightbox should be loaded globally or not.
		 *
		 * @param bool $check
		 * @todo rename wpex_load_lightbox_globally
		 */
		$check = (bool) apply_filters( 'wpex_load_ilightbox_globally', $check );

		return $check;
	}

	/**
	 * Enqueue scripts.
	 */
	public static function enqueue_scripts() {
		self::enqueue_fancybox();
		do_action( 'wpex_enqueue_lightbox_scripts' );
	}

	/**
	 * Enqueue fancybox.
	 */
	public static function enqueue_fancybox( $initialize = true ) {
		wp_enqueue_style( 'fancybox' );
		wp_enqueue_script( 'fancybox' );

		$skin = get_theme_mod( 'lightbox_skin' );

		if ( 'light' === $skin ) {
			wp_enqueue_style(
				'wpex-fancybox-light',
				wpex_asset_url( 'css/fancybox-skins/wpex-fancybox-light.css' ),
				array( 'fancybox' ),
				'1.0'
			);
		}

		if ( $initialize ) {
			wp_enqueue_script( 'wpex-fancybox' );
		}

	}

}