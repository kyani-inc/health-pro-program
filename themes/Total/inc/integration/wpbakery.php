<?php
namespace TotalTheme\Integration;

defined( 'ABSPATH' ) || exit;

/**
 * WPBakery Integration.
 *
 * @package TotalTheme
 * @subpackage WPBakery
 * @version 5.3
 */
final class WPBakery {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of WPBakery.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
			static::$instance->includes();
			static::$instance->initialize();
			static::$instance->init_hooks();
		}
		return static::$instance;
	}

	/**
	 * Include required core files.
	 *
	 * @todo load files based on request
	 */
	public function includes() {
		require_once WPEX_INC_DIR . 'integration/wpbakery/functions/helpers.php';
	}

	/**
	 * Initiate classes.
	 */
	public function initialize() {

		WPBakery\Disable_About_Screen::instance();
		WPBakery\Inline_CSS::instance();
		WPBakery\Accent_Colors::instance();
		WPBakery\Shortcode_Theme_Styles::instance();

		if ( get_theme_mod( 'section_templates_enable', true ) ) {
			WPBakery\Section_Templates::instance();
		}

		require_once WPEX_INC_DIR . 'integration/wpbakery/actions/remove-core-elements.php';
		require_once WPEX_INC_DIR . 'integration/wpbakery/actions/filter-font-container.php';

		require_once WPEX_INC_DIR . 'integration/wpbakery/classes/class-vcex-vc-section-config.php';
		require_once WPEX_INC_DIR . 'integration/wpbakery/classes/class-vcex-vc-row-config.php';
		require_once WPEX_INC_DIR . 'integration/wpbakery/classes/class-vcex-vc-column-config.php';
		require_once WPEX_INC_DIR . 'integration/wpbakery/classes/class-vcex-single-image-config.php';
		require_once WPEX_INC_DIR . 'integration/wpbakery/classes/class-vcex-vc-column-text-config.php';
		require_once WPEX_INC_DIR . 'integration/wpbakery/classes/class-vcex-vc-tabs-config.php';
		require_once WPEX_INC_DIR . 'integration/wpbakery/classes/class-vcex-parse-row-atts.php';

		WPBakery\BG_Overlays::instance();
		WPBakery\Video_Backgrounds::instance();

		if ( apply_filters( 'vcex_supports_advanced_parallax', true ) ) {
			WPBakery\Advanced_Parallax::instance();
		}

		if ( apply_filters( 'vcex_supports_shape_dividers', true ) ) {
			WPBakery\Shape_Dividers::instance();
		}

		// Theme mode.
		if ( wpex_vc_theme_mode_check() ) {

			WPBakery\Disable_Template_Library::instance();
			WPBakery\Disable_Custom_CSS_Page::instance();
			WPBakery\Disable_Product_License_Page::instance();

			if ( get_theme_mod( 'wpbakery_design_options_enable', false ) ) {
				WPBakery\Enable_Design_Options::instance();
			} else {
				WPBakery\Disable_Design_Options::instance();
			}

			require_once WPEX_INC_DIR . 'integration/wpbakery/actions/disable-updates.php';

		}

	}

	/**
	 * Hook into actions and filters.
	 */
	public function init_hooks() {

		// Run on init.
		add_action( 'init', array( $this, 'init' ), 20 );
		add_action( 'admin_init', array( $this, 'admin_init' ), 20 );

		// Tweak scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'load_composer_front_css' ), 0 );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'wp_footer', array( $this, 'remove_footer_scripts' ) );
		add_action( 'wp_footer', array( $this, 'enqueue_dependent_scripts' ) );

		// Admin/iFrame scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'vc_load_iframe_jscss', array( $this, 'iframe_scripts' ) );
		add_action( 'vc_inline_editor_page_view', array( $this, 'editor_scripts' ), PHP_INT_MAX );

		// Popup scripts.
		add_action( 'vc_frontend_editor_enqueue_js_css', array( $this, 'popup_scripts' ) );
		add_action( 'vc_backend_editor_enqueue_js_css', array( $this, 'popup_scripts' ) );

		// Add Customizer settings.
		add_filter( 'wpex_customizer_panels', array( $this, 'customizer_settings' ) );

		// Remove default templates => Do not edit due to extension plugin and snippets.
		add_filter( 'vc_load_default_templates', '__return_empty_array' );

		// Add new background styles.
		add_filter( 'vc_css_editor_background_style_options_data', array( $this, 'background_styles' ) );

		// Disable builder completely on admin post types.
		add_filter( 'vc_is_valid_post_type_be', array( $this, 'disable_editor' ), 10, 2 );

		// Add custom params to vc iframe URL.
		add_filter( 'vc_frontend_editor_iframe_url', array( $this, 'vc_frontend_editor_iframe_url' ) );

		// Add typography settings.
		add_filter( 'wpex_typography_settings', array( $this, 'typography_settings' ) );

		// Add noscript tag for stretched rows.
		if ( true === apply_filters( 'wpex_noscript_tags', true ) ) {
			add_action( 'wp_head', array( $this, 'noscript' ), 60 );
		}

	}

	/**
	 * Functions that run on init.
	 */
	public function init() {

		if ( function_exists( 'visual_composer' ) ) {
			remove_action( 'wp_head', array( visual_composer(), 'addMetaData' ) );
		}

		if ( function_exists( 'vc_set_default_editor_post_types' ) ) {
			vc_set_default_editor_post_types( array( 'page', 'portfolio', 'staff' ) );
		}

	}

	/**
	 * Functions that run on admin_init.
	 */
	public function admin_init() {

		// Tweak VC logo - remove's their link.
		add_filter( 'vc_nav_front_logo', array( $this, 'editor_nav_logo' ) );

		// Remove purchase notice.
		wpex_remove_class_filter( 'admin_notices', 'Vc_License', 'adminNoticeLicenseActivation', 10 );

	}

	/**
	 * Override editor logo.
	 */
	public function editor_nav_logo() {
		return '<div id="vc_logo" class="vc_navbar-brand" aria-hidden="true"></div>';
	}

	/**
	 * Load js_composer_front CSS early on for easier modification.
	 */
	public function load_composer_front_css() {
		wp_enqueue_style( 'js_composer_front' );
	}

	/**
	 * Register scripts for later loading.
	 */
	public function register_scripts() {

		wp_register_script(
			'wpex-vc_tabs-accessibility',
			wpex_asset_url( 'js/dynamic/wpbakery/wpex-vc_tabs-accessibility.min.js' ),
			array( 'vc_tabs_script' ),
			WPEX_THEME_VERSION,
			true
		);

	}

	/**
	 * Remove scripts hooked in too late for me to remove on wp_enqueue_scripts.
	 */
	public function remove_footer_scripts() {

		/**
		 * Remove extra owl carousel if the Total carousel CSS has been loaded.
		 *
		 * @todo can this be removed at some point?
		 */
		if ( wp_style_is( 'wpex-owl-carousel', 'enqueued' ) ) {
			wp_deregister_style( 'vc_pageable_owl-carousel-css' );
			wp_dequeue_style( 'vc_pageable_owl-carousel-css' );
		}

	}

	/**
	 * Enqueues scripts dependent on other WPBakery scripts.
	 */
	public function enqueue_dependent_scripts() {
		if ( wp_script_is( 'vc_tabs_script' ) ) {
			wp_enqueue_script( 'wpex-vc_tabs-accessibility' );
		}
	}

	/**
	 * Admin Scripts.
	 */
	public function admin_scripts( $hook ) {

		$hooks = array(
			'edit.php',
			'post.php',
			'post-new.php',
			'widgets.php',
			'toolset_page_ct-editor', // Support VC widget plugin.
		);

		if ( ! in_array( $hook, $hooks ) ) {
			return;
		}

		wp_enqueue_style(
			'wpex-wpbakery-admin',
			wpex_asset_url( 'css/wpex-wpbakery-admin.css' ),
			array(),
			WPEX_THEME_VERSION
		);

		if ( is_rtl() ) {

			wp_enqueue_style(
				'wpex-wpbakery-admin-rtl',
				wpex_asset_url( 'css/wpex-wpbakery-admin-rtl.css' ),
				array(),
				WPEX_THEME_VERSION
			);

		}

	}

	/**
	 * iFrame Scripts.
	 */
	public function iframe_scripts() {

		wp_enqueue_style(
			'wpex-wpbakery-iframe',
			wpex_asset_url( 'css/wpex-wpbakery-iframe.css' ),
			array(),
			WPEX_THEME_VERSION
		);

	}

	/**
	 * Editor Scripts.
	 */
	public function editor_scripts() {

		wp_enqueue_script(
			'wpex-vc_reload',
			wpex_asset_url( 'js/dynamic/wpbakery/wpex-vc_reload.min.js' ),
			array( 'jquery' ),
			WPEX_THEME_VERSION,
			true
		);

	}

	/**
	 * Popup Window Scripts.
	 */
	public function popup_scripts() {

		wp_enqueue_script(
			'wpex-chosen',
			wpex_asset_url( 'lib/chosen/chosen.jquery.min.js' ),
			array( 'jquery' ),
			'1.4.1',
			true
		);

		wp_enqueue_style(
			'wpex-chosen',
			wpex_asset_url( 'lib/chosen/chosen.min.css' ),
			false,
			'1.4.1'
		);

		wp_enqueue_style(
			'wpex-wpbakery-admin',
			wpex_asset_url( 'css/wpex-wpbakery-admin.css' ),
			array(),
			WPEX_THEME_VERSION
		);

	}

	/**
	 * Adds Customizer settings for VC.
	 */
	public function customizer_settings( $panels ) {
		$panels['visual_composer'] = array(
			'title'      => esc_html__( 'WPBakery Builder', 'total' ),
			'settings'   => WPEX_INC_DIR . 'integration/wpbakery/customizer-settings.php',
			'is_section' => true,
		);
		return $panels;
	}

	/**
	 * Add noscript tag for stretched rows.
	 */
	public function noscript() {
		echo '<noscript><style>body:not(.content-full-screen) .wpex-vc-row-stretched[data-vc-full-width-init="false"]{visibility:visible;}</style></noscript>';
	}

	/**
	 * Add new background style options.
	 */
	public function background_styles( $styles ) {
		$styles[esc_html__( 'Repeat-x', 'total' )] = 'repeat-x';
		$styles[esc_html__( 'Repeat-y', 'total' )] = 'repeat-y';
		return $styles;
	}

	/**
	 * Disable builder completely on admin post types.
	 */
	public function disable_editor( $check, $type ) {

		$excluded_types = array(
			'attachment',
			'acf',
			'wpex_sidebars',
			'acf-field-group',
			'elementor_library',
			'wpex_font',
			'wpex_color_palette',
		);

		if ( in_array( $type, $excluded_types ) ) {
			return false;
		}

		return $check;

	}

	/**
	 * Parse vc_frontend_editor_iframe_url to allow footer/header builder templates.
	 */
	public function vc_frontend_editor_iframe_url( $url ) {
		if ( $url ) {
			if ( isset( $_GET[ 'wpex_inline_header_template_editor' ] ) ) {
				$url = esc_url( $url . '&wpex_inline_header_template_editor=' . absint( $_GET[ 'wpex_inline_header_template_editor' ] ) );
			}
			if ( isset( $_GET[ 'wpex_inline_footer_template_editor' ] ) ) {
				$url = esc_url( $url . '&wpex_inline_footer_template_editor=' . absint( $_GET[ 'wpex_inline_footer_template_editor' ] ) );
			}
		}
		return $url;
	}

	/**
	 * Add custom Typography settings for WPBakery.
	 *
	 * @todo Move to Typography panel with conditional.
	 */
	public static function typography_settings( $settings ) {
		$settings['vcex_heading'] = array(
			'label'  => esc_html__( 'Heading Element', 'total' ),
			'target' => '.vcex-heading',
			'margin' => true,
		);
		return $settings;
	}

}