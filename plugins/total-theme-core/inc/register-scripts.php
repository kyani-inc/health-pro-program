<?php
namespace TotalThemeCore;

defined( 'ABSPATH' ) || exit;

final class Register_Scripts {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Register_Scripts.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
			static::$instance->init_hooks();
		}

		return static::$instance;
	}

	/**
	 * Get things started.
	 */
	public function init_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin' ) );
	}

	/**
	 * Register admin scripts.
	 */
	public function admin() {

		wp_register_script( 'wp-color-picker-alpha',
			TTC_PLUGIN_DIR_URL . 'assets/js/wp-color-picker-alpha.min.js',
			array( 'wp-color-picker' ),
			'2.1.4',
			true
		);

	}

}