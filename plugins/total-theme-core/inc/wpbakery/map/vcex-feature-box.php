<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Feature_Box_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_feature_box shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Feature_Box {

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
		vc_lean_map( 'vcex_feature_box', array( $this, 'map' ) );
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Feature Box', 'total-theme-core' ),
			'description' => esc_html__( 'A feature content box', 'total-theme-core' ),
			'base'        => 'vcex_feature_box',
			'category'    => vcex_shortcodes_branding(),
			'icon'        => 'vcex_element-icon vcex_element-icon--feature-box',
			'params'      => VCEX_Feature_Box_Shortcode::get_params(),
		);
	}

}