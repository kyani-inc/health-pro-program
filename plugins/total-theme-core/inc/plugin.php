<?php
namespace TotalThemeCore;

defined( 'ABSPATH' ) || exit;

final class Plugin {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Plugin.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
			static::$instance->register_autoloader();
			static::$instance->global_components();
			static::$instance->init_hooks();
		}

		return static::$instance;
	}

	/**
	 * Include autoloader class.
	 */
	public function register_autoloader() {
		require_once TTC_PLUGIN_DIR_PATH . 'inc/autoloader.php';
		Autoloader::run();
	}

	/**
	 * Include global components.
	 */
	public function global_components() {

		// Include plugin functions.
		require_once TTC_PLUGIN_DIR_PATH . 'inc/functions/helpers.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/functions/deprecated.php';

		// Register plugin scripts.
		Register_Scripts::instance();

	}

	/**
	 * Hook into actions and filters (Theme Setup & Theme Action Hooks).
	 */
	public function init_hooks() {
		add_action( 'after_setup_theme', array( $this, 'init_components' ) );
		add_action( 'init', array( $this, 'maybe_flush_rewrite_rules' ), 50 );
	}

	/**
	 * Initialize all plugin components.
	 */
	public function init_components() {

		// Don't load on older versions of Total to prevent issues with customers potentially downgrading.
		if ( defined( 'WPEX_THEME_VERSION' ) && version_compare( '4.9', WPEX_THEME_VERSION, '>' ) ) {
			return;
		}

		// Demo importer.
		if ( get_theme_mod( 'demo_importer_enable', true ) ) {
			require_once TTC_PLUGIN_DIR_PATH . 'inc/demo-importer/demo-importer.php';
		}

		// Color Palette.
		if ( get_theme_mod( 'color_palette_enable', true ) && defined( 'TOTAL_THEME_ACTIVE' ) ) {
			require_once TTC_PLUGIN_DIR_PATH . 'inc/lib/wpex-color-palette/class-wpex-color-palette.php';
		}

		// Font Manager.
		if ( get_theme_mod( 'font_manager_enable', true ) && defined( 'TOTAL_THEME_ACTIVE' ) ) {
			require_once TTC_PLUGIN_DIR_PATH . 'inc/lib/wpex-font-manager/class-wpex-font-manager.php';
		}

		// Register Custom shortcodes.
		Register_Shortcodes::instance();

		// Shortcodes Editor Button.
		if ( get_theme_mod( 'editor_shortcodes_enable', true ) ) {
			Mce_Buttons::instance();
		}

		// Custom Widgets.
		if ( get_theme_mod( 'custom_widgets_enable', true ) ) {
			Register_Widgets::instance();
		}

		// Widget Areas.
		if ( get_theme_mod( 'widget_areas_enable', true ) ) {
			require_once TTC_PLUGIN_DIR_PATH . 'inc/lib/wpex-widget-areas/class-wpex-widget-areas.php';
		}

		// Vcex Shortcodes.
		if ( get_theme_mod( 'extend_visual_composer', true ) ) {
			Vcex\Init::instance();
		}

		// WPBakery integration.
		if ( function_exists( 'vc_lean_map' ) ) {
			if ( get_theme_mod( 'extend_visual_composer', true )
				|| ( function_exists( 'wpex_has_vc_mods' ) && wpex_has_vc_mods() )
			) {
				WPBakery\Init::instance();
			}
		}

		// Admin only classes.
		if ( is_admin() ) {

			if ( get_theme_mod( 'custom_attachment_fields', true ) ) {
				Meta\Attachment_Settings::instance();
			}

			if ( apply_filters( 'wpex_metaboxes', true ) ) {
				Meta\Theme_Settings::instance();
			}

			if ( apply_filters( 'wpex_card_metabox', true ) ) {
				Meta\Card_Settings::instance();
			}

			if ( apply_filters( 'wpex_add_user_social_options', true ) ) {
				Meta\User_Settings::instance();
			}

			if ( get_theme_mod( 'gallery_metabox_enable', true ) ) {
				Meta\Gallery_Metabox::instance();
			}

			Meta\Term_Settings::instance();

		}

		// Term Colors.
		if ( get_theme_mod( 'term_colors_enable', true ) ) {
			Term_Colors::instance();
		}

		// Term Thumbnails.
		if ( get_theme_mod( 'term_thumbnails_enable', true ) ) {
			Term_Thumbnails::instance();
		}

		// Category settings.
		if ( apply_filters( 'wpex_category_settings', get_theme_mod( 'category_settings_enable', true ) ) ) {
			Meta\Category_Settings::instance();
		}

		// Portfolio post type.
		if ( get_theme_mod( 'portfolio_enable', true ) ) {
			Cpt\Portfolio::instance();
		}

		// Staff post type.
		if ( get_theme_mod( 'staff_enable', true ) ) {
			Cpt\Staff::instance();
		}

		// Testimonials post type.
		if ( get_theme_mod( 'testimonials_enable', true ) ) {
			Cpt\Testimonials::instance();
		}

		// Post series.
		if ( get_theme_mod( 'post_series_enable', true ) ) {
			Taxonomies\Post_Series::instance();
		}

		// Custom CSS panel.
		if ( defined( 'TOTAL_THEME_ACTIVE' ) && get_theme_mod( 'custom_css_enable', true ) ) {
			CSS_Panel::instance();
		}

	}

	/**
	 * Maybe flush rewrite rules.
	 */
	public function maybe_flush_rewrite_rules() {
		if ( get_option( 'ttc_flush_rewrite_rules_flag' ) ) {
			flush_rewrite_rules();
			delete_option( 'ttc_flush_rewrite_rules_flag' );
		}
	}

}
Plugin::instance();