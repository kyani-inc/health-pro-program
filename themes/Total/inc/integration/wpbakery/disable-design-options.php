<?php
namespace TotalTheme\Integration\WPBakery;

defined( 'ABSPATH' ) || exit;

final class Disable_Design_Options {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Disable_Design_Options.
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

		if ( get_option( 'wpb_js_use_custom' ) ) {
			delete_option( 'wpb_js_use_custom' );
		}

		add_filter( 'vc_settings_page_show_design_tabs', '__return_false' );
		add_action( 'wp_enqueue_scripts', __CLASS__ . '::dequeue_scripts' );

	}

	/**
	 * Dequeue scripts.
	 */
	public static function dequeue_scripts() {
		wp_deregister_style( 'js_composer_custom_css' );
	}

}