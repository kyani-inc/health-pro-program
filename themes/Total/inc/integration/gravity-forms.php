<?php
namespace TotalTheme\Integration;

defined( 'ABSPATH' ) || exit;

/**
 * Gravity Forms Integration.
 *
 * @package TotalTheme
 * @subpackage Integration
 * @version 5.2
 *
 * @todo double check styles incase they need updating.
 */
final class Gravity_Forms {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Gravity_Forms.
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

		if ( wpex_is_request( 'frontend' ) && apply_filters( 'wpex_gravity_forms_css', true ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'gravity_forms_css' ), 40 );
		}

	}

	/**
	 * Loads Gravity Forms stylesheet.
	 *
	 * @since 4.6.5
	 */
	public function gravity_forms_css() {

		global $post;

		if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'gravityform' ) ) {

			wp_enqueue_style(
				'wpex-gravity-forms',
				wpex_asset_url( 'css/wpex-gravity-forms.css' ),
				array(),
				WPEX_THEME_VERSION
			);

		}

	}

}