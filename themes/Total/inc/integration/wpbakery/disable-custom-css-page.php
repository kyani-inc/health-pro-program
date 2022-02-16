<?php
namespace TotalTheme\Integration\WPBakery;

defined( 'ABSPATH' ) || exit;

final class Disable_Custom_CSS_Page {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Disable_About_Screen.
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
		add_filter( 'vc_settings_tabs', __Class__ . '::filter_tabs' );
	}

	/**
	 * Remove vc custom css admin page.
	 */
	public static function filter_tabs( $tabs ) {
		if ( is_array( $tabs ) ) {
			unset( $tabs['vc-custom_css'] );
		}
		return $tabs;
	}

}