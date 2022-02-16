<?php
namespace TotalTheme\Integration;

defined( 'ABSPATH' ) || exit;

/**
 * Contact Form 7 Integration.
 *
 * @package TotalTheme
 * @subpackage TotalTheme\Integration
 * @version 5.2
 */
final class Contact_Form_7 {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Contact_Form_7.
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

		if ( wpex_is_request( 'frontend' ) ) {
			$this->frontend_hooks();
		}

	}

	/**
	 * Frontend Hooks.
	 */
	public function frontend_hooks() {

		if ( ! defined( 'CF7MSM_PLUGIN' )
			&& function_exists( 'wpcf7_enqueue_scripts' )
			&& apply_filters( 'wpex_conditional_wpcf7_scripts', true )
		) {

			// Remove CSS Completely - theme adds styles.
			add_filter( 'wpcf7_load_css', '__return_false' );

			// Remove JS.
			add_filter( 'wpcf7_load_js', '__return_false' );

			// Conditionally load scripts.
			add_action( 'wpcf7_contact_form', array( $this, 'enqueue_scripts' ), 1 );

		}


	}

	/**
	 * Load scripts only as needed.
	 */
	public function enqueue_scripts() {

		// Load core JS
		wpcf7_enqueue_scripts();
		//wpcf7_enqueue_styles();

		// Load theme CSS
		$this->enqueue_theme_css();

	}

	/**
	 * Enqueues theme CSS for contact form 7.
	 */
	public function enqueue_theme_css() {

		wp_enqueue_style(
			'wpex-contact-form-7',
			wpex_asset_url( 'css/wpex-contact-form-7.css' ),
			array(),
			WPEX_THEME_VERSION
		);


		// Add action since we are loading our own styles incase any 3rd party plugin hooks in here.
		do_action( 'wpcf7_enqueue_styles' );

	}

}