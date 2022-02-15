<?php
namespace TotalThemeCore\WPBakery;

defined( 'ABSPATH' ) || exit;

/**
 * WPBakery tweaks and custom shortcodes.
 *
 * @package TotalThemeCore
 * @version 1.3.1
 */
final class Init {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Init.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new Init;
			static::$instance->include_functions();
			static::$instance->initiate_classes();
			static::$instance->init_hooks();
		}

		return static::$instance;
	}

	/**
	 * Hook into actions and filters.
	 */
	public function init_hooks() {

		// Global functions.
		add_action( 'vc_before_mapping', array( $this, 'vc_before_mapping' ) );

		// Register scripts.
		add_action( 'vc_base_register_admin_css', array( $this, 'register_editor_css' ) );
		add_action( 'vc_base_register_front_css', array( $this, 'register_editor_css' ) );

		// Enqueue scripts.
		add_action( 'vc_load_iframe_jscss', array( $this, 'editor_iframe_scripts' ), PHP_INT_MAX );
		add_action( 'vc_backend_editor_enqueue_js_css', array( $this, 'backend_editor_scripts' ) );
		add_action( 'vc_frontend_editor_enqueue_js_css', array( $this, 'frontend_editor_scripts' ) );
		add_action( 'vc_page_settings_build', array( $this, 'settings_scripts' ) );

	}

	/**
	 * Includes files.
	 */
	public function include_functions() {
		require_once TTC_PLUGIN_DIR_PATH . 'inc/wpbakery/functions/core.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/wpbakery/functions/autocomplete.php';
	}

	/**
	 * Initiate classes.
	 */
	public function initiate_classes() {

		// Custom backend-editor views.
		if ( is_admin() ) {
			Views\Backend_Editor\Image::instance();
			Views\Backend_Editor\Image_Gallery::instance();
			Views\Backend_Editor\Image_Before_After::instance();
		}

		// Allow frontend editor support for templatera.
		if ( is_admin() || ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) ) {
			new Templatera\Enable_Frontend;
		}

	}

	/**
	 * Run functions before/needed for VC mapping.
	 */
	public function vc_before_mapping() {

		// Register icon sets.
		add_filter( 'vc_iconpicker-type-ticons', array( 'TotalThemeCore\WPBakery\Iconpicker\Ticons', 'get_icons' ) );

		// Add custom parameters.
		if ( function_exists( 'vc_add_shortcode_param' ) ) {
			require_once TTC_PLUGIN_DIR_PATH . 'inc/wpbakery/add-params.php';
		}

	}

	/**
	 * Register CSS scripts.
	 */
	public function register_editor_css() {
		wp_register_style(
			'vcex-wpbakery-editor',
			TTC_PLUGIN_DIR_URL . 'inc/wpbakery/assets/css/vcex-wpbakery-editor.css',
			array(),
			TTC_VERSION
		);
	}

	/**
	 * Register JS Scripts.
	 */
	public function register_frontend_js() {
		wp_register_script(
			'vcex-vc_reload',
			TTC_PLUGIN_DIR_URL . 'inc/wpbakery/assets/js/frontend-editor/vcex-vc_reload.min.js',
			array( 'jquery' ),
			TTC_VERSION,
			true
		);
	}

	/**
	 * Editor Scripts.
	 */
	public function editor_iframe_scripts() {
		wp_enqueue_style(
			'vcex-wpbakery-vc-helper',
			TTC_PLUGIN_DIR_URL . 'inc/wpbakery/assets/css/vc-helper.css',
			array(),
			TTC_VERSION
		);
		wp_enqueue_script(
			'vcex-vc_reload',
			TTC_PLUGIN_DIR_URL . 'inc/wpbakery/assets/js/frontend-editor/vcex-vc_reload.min.js',
			array( 'jquery' ),
			TTC_VERSION,
			true
		);
	}

	/**
	 * Enqueue backend editor scripts.
	 *
	 * @todo move vcex-params.min.js here if possible? Not sure if it's any better.
	 */
	public function backend_editor_scripts() {
		wp_enqueue_style( 'vcex-wpbakery-editor' );
	}

	/**
	 * Enqueue frontend editor scripts.
	 */
	public function frontend_editor_scripts() {
		wp_enqueue_style( 'vcex-wpbakery-editor' );
	}

	/**
	 * Enqueue scripts for the WPBakery settings pages.
	 */
	public function settings_scripts() {
		wp_enqueue_style(
			'vcex-element-icons',
			TTC_PLUGIN_DIR_URL . 'inc/wpbakery/assets/css/vcex-element-icons.css',
			array( 'js_composer_settings' ),
			TTC_VERSION
		);
	}

}