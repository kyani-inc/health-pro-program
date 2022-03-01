<?php
namespace TotalTheme\Updates;

defined( 'ABSPATH' ) || exit;

/**
 * Disable updates for built-in plugins/addons (not currently in use).
 *
 * @package TotalTheme
 * @subpackage Updates
 * @version 5.1
 */
final class Disable_Auto_Updates {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Disable_Auto_Updates.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
			static::$instance->init_hooks();
		}

		return static::$instance;
	}

	/**
	 * Run action hooks.
	 */
	public function init_hooks() {
		add_filter( 'auto_update_plugin', array( $this, 'plugin_auto_updates' ), 10, 2 );
	}

	/**
	 * Filter plugin auto updates.
	 */
	public function plugin_auto_updates( $update, $item ) {

		/*if ( 'total-theme-core' == $item->slug ) {
			return false;
		}*/

		return $update;

	}

}