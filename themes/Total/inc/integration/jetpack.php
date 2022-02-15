<?php
namespace TotalTheme\Integration;

defined( 'ABSPATH' ) || exit;

/**
 * JetPack Configuration Class.
 *
 * @package TotalTheme
 * @subpackage Integration
 * @version 5.2
 */
final class Jetpack {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Jetpack.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
		}

		return static::$instance;
	}

	/**
	 * Hook into actions and filters.
	 */
	public function __construct() {
		$this->sharedaddy_support();
		$this->carousel_support();
	}

	/**
	 * Adds support for sharedaddy.
	 *
	 * @version 5.2
	 */
	public function sharedaddy_support() {

		if ( ! \Jetpack::is_module_active( 'sharedaddy' ) ) {
			return;
		}

		if ( wpex_is_request( 'frontend' ) ) {

			// Remove default filters.
			add_action( 'loop_start', array( $this, 'remove_share' ) );

			// Social share should always be enabled & disabled via blocks/theme filter.
			add_filter( 'sharing_show', '__return_true' );

			// Enqueue scripts if social share is enabled.
			add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );

			// Replace social share.
			add_filter( 'wpex_custom_social_share', array( $this, 'alter_share' ) );

		}

		// Remove Customizer settings.
		add_filter( 'wpex_customizer_sections', array( $this, 'remove_customizer_settings' ), 40 );

	}

	/**
	 * Adds support for carousels.
	 *
	 * @version 5.2
	 */
	public function carousel_support() {

		if ( \Jetpack::is_module_active( 'carousel' ) || \Jetpack::is_module_active( 'tiled-gallery' ) ) {
			add_filter( 'wpex_custom_wp_gallery', '__return_false' );
		}

	}

	/**
	 * Removes jetpack default loop filters.
	 *
	 * @version 3.3.5
	 */
	public function remove_share() {
		remove_filter( 'the_content', 'sharing_display', 19 );
		remove_filter( 'the_excerpt', 'sharing_display', 19 );
	}

	/**
	 * Enqueue scripts if social share is enabled.
	 *
	 * @version 3.3.5
	 */
	public function load_scripts() {
		if ( wpex_has_social_share() ) {
			add_filter( 'sharing_enqueue_scripts', '__return_true' );
		}
	}

	/**
	 * Replace Total social share with sharedaddy.
	 *
	 * @version 3.3.5
	 */
	public function alter_share() {
		if ( function_exists( 'sharing_display' ) ) {
			return sharing_display( '', false ); // text, echo
		}
	}

	/**
	 * Remove Customizer settings.
	 *
	 * @version 3.3.5
	 */
	public function remove_customizer_settings( $array ) {
		unset( $array['wpex_social_sharing'] );
		return $array;
	}

}