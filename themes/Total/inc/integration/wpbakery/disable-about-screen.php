<?php
namespace TotalTheme\Integration\WPBakery;

defined( 'ABSPATH' ) || exit;

final class Disable_About_Screen {

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

		if ( ! is_admin() ) {
			return;
		}

		remove_action( 'vc_activation_hook', 'vc_page_welcome_set_redirect' );
		remove_action( 'init', 'vc_page_welcome_redirect' );
		remove_action( 'admin_init', 'vc_page_welcome_redirect' );
		add_action( 'admin_menu', __CLASS__ . '::remove_admin_menu', 999 );

	}

	/**
	 * Remove admin menu.
	 */
	public static function remove_admin_menu() {

		// Non admin user.
		if ( defined( 'VC_PAGE_MAIN_SLUG' ) && 'vc-welcome' === VC_PAGE_MAIN_SLUG ) {
			//remove_menu_page( 'vc-welcome' ); // hides the grid and template pages, can't use.
		}

		// Admin user.
		else {
			remove_submenu_page( 'vc-general', 'vc-welcome' );
			remove_submenu_page( 'admin', 'vc-welcome' );
		}
	}

}