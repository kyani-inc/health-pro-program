<?php
namespace TotalTheme\Integration\Gutenberg;

defined( 'ABSPATH' ) || exit;

/**
 * Gutenberg Block Gallery Lightbox.
 *
 * @package TotalTheme
 * @subpackage Integration\Gutenberg
 * @version 5.3.1
 */
class Block_Gallery_Lightbox {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the class instance.
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
		add_action( 'wp_enqueue_scripts', __CLASS__ . '::register_scripts' );
	}

	/**
	 * Register scripts.
	 */
	public static function register_scripts() {

		if ( ! is_singular() || ! has_block( 'gallery', get_the_ID() ) ) {
			return;
		}

		wpex_enqueue_lightbox_scripts();

		wp_enqueue_script(
			'wpex-block-gallery-lightbox',
			wpex_asset_url( 'js/dynamic/gutenberg/wpex-block-gallery-lightbox.js' ),
			array(),
			WPEX_THEME_VERSION,
			true
		);

	}

}