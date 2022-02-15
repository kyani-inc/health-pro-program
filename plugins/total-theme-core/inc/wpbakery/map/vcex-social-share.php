<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Social_Share_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_social_share shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Social_Share {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the class instance.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
			static::$instance->init_hooks();
		}

		return static::$instance;
	}

	/**
	 * Hook into actions and filters.
	 */
	public function init_hooks() {
		add_action( 'vc_after_mapping', array( $this, 'vc_after_mapping' ) );
	}

	/**
	 * Run functions on vc_after_mapping hook.
	 */
	public function vc_after_mapping() {
		vc_lean_map( 'vcex_social_share', array( $this, 'map' ) );
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Social Share', 'total-theme-core' ),
			'description' => esc_html__( 'Display post social share.', 'total-theme-core' ),
			'base'        => 'vcex_social_share',
			'icon'        => 'vcex_element-icon vcex_element-icon--social-share',
			'category'    => vcex_shortcodes_branding(),
			'params'      => VCEX_Social_Share_Shortcode::get_params(),
		);
	}

}