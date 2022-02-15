<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Image_Before_After_Shortcode;

defined( 'ABSPATH' ) || exit;
/**
 * Class registers the vcex_image_before_after shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.3
 */
final class Vcex_Image_Before_After {

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
		vc_lean_map( 'vcex_image_ba', array( $this, 'map' ) );
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'             => esc_html__( 'Image Before/After', 'total-theme-core' ),
			'description'      => esc_html__( 'Visual difference between two images', 'total-theme-core' ),
			'base'             => 'vcex_image_ba',
			'icon'             => 'vcex_element-icon vcex_element-icon--before-after',
			'category'         => vcex_shortcodes_branding(),
			'params'           => VCEX_Image_Before_After_Shortcode::get_params(),
			'admin_enqueue_js' => vcex_wpbakery_asset_url( 'js/backend-editor/vcex-image-before-after-view.min.js' ),
			'js_view'          => 'vcexBackendViewImageBeforeAfter',
		);
	}

}