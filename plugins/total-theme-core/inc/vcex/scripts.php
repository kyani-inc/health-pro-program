<?php
namespace TotalThemeCore\Vcex;

defined( 'ABSPATH' ) || exit;

/**
 * Register scripts for use with vcex elements and enqueues global js.
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 1.3.1
 */
final class Scripts {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Scripts.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
		}
		return static::$instance;
	}

	/**
	 * Class Constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
	}

	/**
	 * Register scripts.
	 */
	public function register_scripts() {

		$js_extension = '.js';

		if ( defined( 'WPEX_MINIFY_JS' ) && WPEX_MINIFY_JS ) {
			$js_extension = '.min.js';
		}

		/* Justified Grid */
		wp_register_script(
			'justifiedGallery',
			vcex_asset_url( 'js/lib/jquery.justifiedGallery' . $js_extension ),
			array( 'jquery' ),
			'3.8.1',
			true
		);

		wp_register_script(
			'vcex-justified-gallery',
			vcex_asset_url( 'js/vcex-justified-gallery' . $js_extension ),
			array( 'jquery', 'justifiedGallery' ),
			TTC_VERSION,
			true
		);

		wp_register_style(
			'vcex-justified-gallery',
			vcex_asset_url( 'css/vcex-justified-gallery.css' ),
			array(),
			TTC_VERSION
		);

		/* Isotope Scripts */
		wp_register_script(
			'vcex-isotope-grids',
			vcex_asset_url( 'js/vcex-isotope-grids' . $js_extension ),
			array( 'jquery' ),
			TTC_VERSION,
			true
		);

		/* Carousel Scripts */
		wp_register_style(
			'wpex-owl-carousel',
			vcex_asset_url( 'css/wpex-owl-carousel.css' ),
			array(),
			'2.3.4'
		);

		wp_register_script(
			'wpex-owl-carousel',
			vcex_asset_url( 'js/lib/wpex-owl-carousel' . $js_extension ),
			array( 'jquery' ),
			TTC_VERSION,
			true
		);

		wp_register_script(
			'vcex-carousels',
			vcex_asset_url( 'js/vcex-carousels' . $js_extension ),
			array( 'jquery', 'wpex-owl-carousel', 'imagesloaded' ),
			TTC_VERSION,
			true
		);

		wp_localize_script(
			'vcex-carousels',
			'vcex_carousels_params',
			array(
				'i18n' => array(
					'NEXT' => esc_html__( 'next slide', 'total-theme-core' ),
					'PREV' => esc_html__( 'previous slide', 'total-theme-core' ),
				),
			)
		);

		/* Responsive Text */
		wp_register_script(
			'vcex-responsive-text',
			vcex_asset_url( 'js/vcex-responsive-text' . $js_extension ),
			array(),
			TTC_VERSION,
			true
		);

		/**
		 * Responsive CSS.
		 *
		 * @deprecated Soft deprecated in v1.3 in exchange for inline style tags, kept as fallback.
		 */
		wp_register_script(
			'vcex-responsive-css',
			vcex_asset_url( 'js/vcex-responsive-css' . $js_extension ),
			array( 'jquery' ),
			TTC_VERSION,
			true
		);

	}

}