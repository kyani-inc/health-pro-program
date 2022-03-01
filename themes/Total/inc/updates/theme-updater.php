<?php
namespace TotalTheme\Updates;

defined( 'ABSPATH' ) || exit;

/**
 * Provides updates for the Total theme.
 *
 * @package TotalTheme
 * @subpackage Updates
 * @version 5.1.3
 */
final class Theme_Updater {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Theme_Updater.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
			static::$instance->init_hooks();
		}

		return static::$instance;
	}

	/**
	 * Total theme updater API url.
	 */
	public $api_url = 'https://wpexplorer-updates.com/api/v1/';

	/**
	 * Active theme license.
	 *
	 * @return string
	 */
	public $theme_license = '';

	/**
	 * Run action hooks.
	 */
	public function init_hooks() {

		// This is for testing only !!!!
		//set_site_transient( 'update_themes', null );

		$this->theme_license = wpex_get_theme_license();

		if ( $this->theme_license ) {
			add_filter( 'pre_set_site_transient_update_themes', array( $this, 'check_for_update' ) );
		}

	}

	/**
	 * Makes a call to the API.
	 */
	protected function call_api( $action, $params ) {

		$api = add_query_arg( $params, $this->api_url . $action );

		$request = wp_safe_remote_get( $api );

		if ( is_wp_error( $request ) ) {
			return false;
		}

		$body = wp_remote_retrieve_body( $request );

		return json_decode( $body );

	}

	/**
	 * Checks the API response to see if there was an error.
	 */
	protected function is_api_error( $response ) {

		if ( $response === false || ! is_object( $response ) || isset( $response->error ) ) {
			return true;
		}

		return false;

	}

	/**
	 * Calls the License Manager API to get the license information for the
	 * current product.
	 */
	protected function get_license_info() {

		return $this->call_api( 'info', array(
			'theme'   => 'Total',
			'license' => urlencode( trim( wp_strip_all_tags( $this->theme_license ) ) ),
		) );

	}

	/**
	 * Check for updates.
	 */
	protected function update_request() {

		$license_info = $this->get_license_info();

		if ( $this->is_api_error( $license_info ) ) {
			return false;
		}

		return $license_info;

	}

	/**
	 * The filter that checks if there are updates to the theme.
	 */
	public function check_for_update( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		// Query API for updates.
		$update = $this->update_request();

		if ( $this->is_api_error( $update ) ) {
			return $transient;
		}

		$theme = wp_get_theme( 'Total' );

		// Update is available.
		if ( isset( $update->version )
			&& isset( $update->package )
			&& version_compare( $theme->get( 'Version' ), $update->version, '<' )
		) {

			$transient->response[ 'Total' ] = array(
				'theme'        => 'Total',
				'new_version'  => $update->version,
				'package'      => $update->package,
				'url'          => WPEX_THEME_CHANGELOG_URL,
				'requires'     => '', // @todo update API to return this.
				'requires_php' => '', // @todo update API to return this.
			);

		}

		return $transient;
	}

}