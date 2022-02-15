<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Page_Title_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_page_title shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Page_Title {

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
		vc_lean_map( 'vcex_page_title', array( $this, 'map' ) );
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Page Title', 'total-theme-core' ),
			'description' => esc_html__( 'Current page title', 'total-theme-core' ),
			'base'        => 'vcex_page_title',
			'icon'        => 'vcex_element-icon vcex_element-icon--page-title',
			'category'    => vcex_shortcodes_branding(),
			'params'      => VCEX_Page_Title_Shortcode::get_params(),
		);
	}

}