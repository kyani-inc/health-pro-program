<?php
namespace TotalTheme\Integration;

defined( 'ABSPATH' ) || exit;

/**
 * W3 Total Cache Configuration Class.
 *
 * @package TotalTheme
 * @subpackage Integration
 * @version 5.3.1
 */
final class W3_Total_cache {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of W3_Total_cache.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
		}

		return static::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 5.0.6
	 */
	public function __construct() {
		add_filter( 'w3tc_minify_css_do_tag_minification', array( $this, 'exclude_css_from_minify' ), 10, 3 );
	}

	/**
	 * Exclude certain theme files from the minification process.
	 *
	 * @since 5.0.6
	 */
	public function exclude_css_from_minify( $do_tag_minification, $style_tag, $file ) {

		if ( ! empty( $file ) ) {

			$exclude_files = array(
				'wpex-mobile-menu-breakpoint-max',
				'wpex-mobile-menu-breakpoint-min',
			);

			foreach ( $exclude_files as $excluded_file ) {

				if ( false !== strpos( $file, $excluded_file ) ) {
					return false;
				}

			}

		}

		return $do_tag_minification;

	}

}