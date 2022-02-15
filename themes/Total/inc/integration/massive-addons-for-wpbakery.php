<?php
namespace TotalTheme\Integration;

defined( 'ABSPATH' ) || exit;

/**
 * Massive Addons Tweaks.
 *
 * @package TotalTheme
 * @subpackage Integration
 * @version 5.3
 */
final class Massive_Addons_For_WPBakery {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of MassiveAddons.
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
		add_filter( 'vcex_supports_advanced_parallax', '__return_false' );
	}

}