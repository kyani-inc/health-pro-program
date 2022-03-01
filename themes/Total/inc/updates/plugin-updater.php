<?php
namespace TotalTheme\Updates;

defined( 'ABSPATH' ) || exit;

/**
 * Displays plugin update notifications for some bundled theme plugins to make it easier for the end user.
 *
 * @package TotalTheme
 * @subpackage Updates
 * @version 5.1
 */
final class Plugin_Updater {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Plugin_Updater.
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

		// For testing purposes only !!!
		//set_site_transient( 'update_plugins', array() );

		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_for_updates' ) );
	}

	/**
	 * Returns list of plugins to check
	 */
	public function get_plugins_to_check() {

			if ( ! function_exists( 'wpex_recommended_plugins' ) ) {
				return;
			}

			$recommended_plugins = (array) wpex_recommended_plugins();

			if ( empty( $recommended_plugins ) ) {
				return;
			}

			$plugins_to_check = array(
				'total-theme-core',
			);

			foreach ( $plugins_to_check as $k => $v ) {

				if ( array_key_exists( $v, $recommended_plugins ) ) {

					$plugin = $recommended_plugins[$v];

					if ( empty( $plugin[ 'package' ] ) && isset( $plugin[ 'source' ] ) ) {
						$plugin[ 'package' ] = $plugin[ 'source' ];
						unset( $plugin[ 'source' ] );
					}

					$plugin[ 'id' ]     = $this->get_plugin_base( $plugin );
					$plugin[ 'plugin' ] = $plugin['id'];

				} else {
					unset( $plugins_to_check[$k] );
				}

				$plugins_to_check[$k] = $plugin;

			}

			return $plugins_to_check;

	}

	/**
	 * Check transients
	 */
	public function check_for_updates( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		// Get plugins to check
		$plugins_to_check = $this->get_plugins_to_check();

		if ( empty( $plugins_to_check ) ) {
			return $transient;
		}

		// Return array of installed plugins
		$installed_plugins = $this->get_installed_plugins();

		// No plugins installed
		if ( empty( $installed_plugins ) || ! is_array( $installed_plugins ) ) {
			return $transient;
		}

		// Loop through plugins and check if an update is available
		foreach ( $plugins_to_check as $plugin ) {

			if ( $this->is_plugin_installed( $plugin, $installed_plugins ) ) {

				$has_update = $this->has_update( $plugin, $installed_plugins );

				if ( $has_update ) {

					$response = (object) array(
						'id'            => $plugin[ 'id' ],
						'slug'          => $plugin[ 'slug' ],
						'plugin'        => $plugin[ 'plugin' ],
						'new_version'   => $plugin[ 'version' ],
						'package'       => $plugin[ 'package' ],
						'url'           => '',
						'icons'         => array(),
						'banners'       => array(),
						'banners_rtl'   => array(),
						'tested'        => '',
						'requires_php'  => '',
						'compatibility' => '',

					);

					$transient->response[ $plugin[ 'id' ] ] = $response;

				} elseif ( isset( $transient->no_update ) ) {

					$item = (object) array(
						'id'            => $plugin[ 'id' ],
						'slug'          => $plugin[ 'slug' ],
						'plugin'        => $plugin[ 'plugin' ],
						'new_version'   => $plugin[ 'version' ],
						'package'       => '',
						'url'           => '',
						'icons'         => array(),
						'banners'       => array(),
						'banners_rtl'   => array(),
						'tested'        => '',
						'requires_php'  => '',
						'compatibility' => '',
					);

					$transient->no_update[ $plugin[ 'id' ] ] = $item;

				}

			}

		}

		//var_dump( $transient );

		// Return transient
		return $transient;

	}

	/**
	 * Get list of installed plugins.
	 */
	public function get_installed_plugins( $plugin_folder = '' ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		return get_plugins( $plugin_folder );
	}

	/**
	 * Check if a specific plugin is installed.
	 */
	public function is_plugin_installed( $plugin, $installed_plugins ) {
		return array_key_exists( $this->get_plugin_base( $plugin ), $installed_plugins );
	}

	/**
	 * Check if a plugin has an update available
	 */
	private function has_update( $plugin, $installed_plugins ) {
		$base = $this->get_plugin_base( $plugin );
		if ( ! empty( $installed_plugins[$base]['Version'] ) ) {
			return version_compare( $plugin['version'], $installed_plugins[$base]['Version'], '>' );
		}
	}

	/**
	 * Returns plugin base from slug
	 */
	private function get_plugin_base( $plugin ) {
		return $plugin['slug'] . '/' . $plugin['slug'] . '.php';
	}

}