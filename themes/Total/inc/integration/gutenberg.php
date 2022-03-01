<?php
namespace TotalTheme\Integration;

defined( 'ABSPATH' ) || exit;

/**
 * Gutenberg Integration Class.
 *
 * @package TotalTheme
 * @subpackage Integration
 * @version 5.3.1
 */
final class Gutenberg {

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
		add_action( 'after_setup_theme', __CLASS__ . '::add_theme_support', 10 );
		add_action( 'init', __CLASS__ . '::init', 10 );
	}

	/**
	 * Checks if the block editor is enabled.
	 */
	private static function is_block_editor_enabled() {
		if ( class_exists( 'Classic_Editor' ) || ( WPEX_VC_ACTIVE && get_option( 'wpb_js_gutenberg_disable' ) ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Define theme support for Gutenberg via add_theme_support.
	 */
	public static function add_theme_support() {
		if ( self::is_block_editor_enabled() ) {
			add_theme_support( 'gutenberg-editor' );
		}
	}

	/**
	 * Runs on init.
	 */
	public static function init() {
		if ( current_theme_supports( 'gutenberg-editor' ) ) {
			self::enabled();
		} else {
			self::disabled();
		}
	}

	/**
	 * Runs when Gutenberg is enabled.
	 */
	public static function enabled() {

		// Add lightbox support for the Gallery block.
		if ( apply_filters( 'wpex_has_block_gallery_lightbox_integration', true ) ) {
			Gutenberg\Block_Gallery_Lightbox::instance();
		}

	}

	/**
	 * Runs when Gutenberg is disabled.
	 */
	public static function disabled() {

		// Remove Gutenberg scripts if Gutenberg is disabled.
		if ( apply_filters( 'wpex_remove_block_library_css', true ) ) {
			Gutenberg\Remove_Scripts::instance();
		}

	}

}