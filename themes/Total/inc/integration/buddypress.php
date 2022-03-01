<?php
namespace TotalTheme\Integration;

defined( 'ABSPATH' ) || exit;

/**
 * bbPress Integration.
 *
 * @package TotalTheme
 * @subpackage BuddyPress
 * @version 5.2
 */
final class BuddyPress {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of BuddyPress.
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
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 20 );
		add_filter( 'wpex_post_layout_class', array( $this, 'layouts' ), 11 ); // on 11 due to bbPress issues
	}

	/**
	 * Load custom CSS.
	 *
	 * @since  4.0
	 */
	public function scripts() {
		wp_enqueue_style(
			'wpex-buddypress',
			wpex_asset_url( 'css/wpex-buddypress.css' ),
			array(),
			WPEX_THEME_VERSION
		);
	}

	/**
	 * Set layouts.
	 *
	 * @version 4.5
	 */
	public function layouts( $layout ) {
		if ( is_buddypress() ) {
			//$layout = get_theme_mod( 'bp_layout', 'left-sidebar' );
			if ( bp_is_user() ) {
				$layout = get_theme_mod( 'bp_user_layout', wpex_get_default_content_area_layout() );
			}
		}
		return $layout;
	}

}