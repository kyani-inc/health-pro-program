<?php
namespace TotalTheme\Integration\Gutenberg;

defined( 'ABSPATH' ) || exit;

/**
 * Removes Gutenberg Scripts.
 *
 * @package TotalTheme
 * @subpackage Integration\Gutenberg
 * @version 5.3.1
 */
class Remove_Scripts {

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
		add_action( 'wp_enqueue_scripts', __CLASS__ . '::run', 9999 );
	}

	/**
	 * Hooks into wp_enqueue_scripts to remove scripts.
	 */
	public static function run() {

		// Remove core block library.
		wp_dequeue_style( 'wp-block-library' );

		// Remove WooCommerce scripts.
		if ( WPEX_WOOCOMMERCE_ACTIVE ) {
			wp_dequeue_style( 'wc-block-style' );
			wp_dequeue_style( 'wc-blocks-style' ); // "s" was added in a Woo update.
			wp_dequeue_style( 'wc-blocks-vendors-style' );
		}

	}

}