<?php
namespace TotalTheme\Integration\WPBakery;

defined( 'ABSPATH' ) || exit;

final class Disable_Template_Library {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Disable_Template_Library.
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

		if ( is_admin() ) {
			add_filter( 'vc_get_all_templates', __CLASS__ . '::remove_templates', 99 );
		}

	}

	/**
	 * Remove templates.
	 */
	public static function remove_templates( $data ) {
		if ( $data && is_array( $data ) ) {
			foreach( $data as $key => $val ) {
				if ( isset( $val['category'] ) && 'shared_templates' === $val['category'] ) {
					unset( $data[$key] );
				}
			}
		}
		return $data;
	}

}